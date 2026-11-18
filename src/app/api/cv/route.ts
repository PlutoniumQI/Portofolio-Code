import type { NextRequest } from 'next/server';
import { getPortfolio } from '@/lib/api';
import { isLocale } from '@/lib/i18n';

/**
 * Downloads `profile.cv_url` instead of opening it in a new tab. The `download`
 * attribute is ignored for cross-origin URLs, so the file has to be streamed
 * from our own origin with a Content-Disposition header.
 *
 * The remote URL is never taken from the request, only from the portfolio
 * payload, so this route cannot be used as an open proxy.
 */
export async function GET(request: NextRequest) {
  const requested = request.nextUrl.searchParams.get('locale') ?? 'id';
  const locale = isLocale(requested) ? requested : 'id';

  const portfolio = await getPortfolio(locale);
  const remote = portfolio.profile?.cv_url;

  if (!remote) {
    return Response.json({ message: 'No CV has been published yet.' }, { status: 404 });
  }

  let upstream: Response;

  try {
    upstream = await fetch(remote, {
      cache: 'no-store',
      redirect: 'follow',
      signal: AbortSignal.timeout(20_000),
    });
  } catch {
    return Response.json({ message: 'The CV file is temporarily unavailable.' }, { status: 503 });
  }

  if (!upstream.ok || !upstream.body) {
    return Response.json({ message: 'The CV file could not be fetched.' }, { status: 502 });
  }

  const fallbackName = `cv-${locale}.pdf`;
  const fileName = decodeURIComponent(remote.split('/').pop()?.split('?')[0] || fallbackName);
  const contentType = (upstream.headers.get('content-type') ?? 'application/pdf').split(';')[0];

  return new Response(upstream.body, {
    headers: {
      'Content-Type': contentType,
      'Content-Disposition': `attachment; filename="${fileName.replace(/"/g, '')}"`,
      'Cache-Control': 'private, max-age=0, must-revalidate',
    },
  });
}

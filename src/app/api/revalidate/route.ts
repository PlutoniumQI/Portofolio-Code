import { revalidatePath, revalidateTag } from 'next/cache';
import type { NextRequest } from 'next/server';

const locales = ['id', 'en'];

/**
 * On-demand revalidation called by the Laravel admin ("Publish changes").
 * Expects `X-Revalidate-Secret` to match FRONTEND_REVALIDATE_SECRET.
 */
export async function POST(request: NextRequest) {
  const secret = process.env.FRONTEND_REVALIDATE_SECRET;
  const provided = request.headers.get('x-revalidate-secret');

  if (!secret || provided !== secret) {
    return Response.json({ message: 'Unauthorized' }, { status: 401 });
  }

  let body: { paths?: unknown } = {};

  try {
    body = (await request.json()) as { paths?: unknown };
  } catch {
    body = {};
  }

  const requestedPaths =
    Array.isArray(body.paths) && body.paths.length > 0
      ? body.paths.filter((path): path is string => typeof path === 'string')
      : ['/'];

  revalidateTag('portfolio', 'max');
  revalidateTag('guestbook', 'max');

  const localizedPaths = new Set<string>();

  for (const path of requestedPaths) {
    const clean = path.startsWith('/') ? path : `/${path}`;
    const suffix = clean === '/' ? '' : clean;

    for (const locale of locales) {
      localizedPaths.add(`/${locale}${suffix}`);
    }

    revalidatePath(clean);
  }

  for (const path of localizedPaths) {
    revalidatePath(path);
  }

  // Detail routes are dynamic; invalidate every generated slug as well.
  revalidatePath('/[locale]/projects/[slug]', 'page');
  revalidatePath('/[locale]/blog/[slug]', 'page');

  return Response.json({
    revalidated: true,
    paths: Array.from(localizedPaths),
    now: Date.now(),
  });
}

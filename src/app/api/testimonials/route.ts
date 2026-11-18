import type { NextRequest } from 'next/server';

/**
 * Same-origin proxy for public testimonial submissions. The Laravel API is
 * never called directly from the browser, so no CORS or public API URL is
 * required. Submissions always land in the admin review queue.
 */
const apiUrl = (process.env.API_URL ?? process.env.NEXT_PUBLIC_API_URL ?? 'http://127.0.0.1:8000').replace(
  /\/+$/,
  '',
);

export async function POST(request: NextRequest) {
  let body: unknown;

  try {
    body = await request.json();
  } catch {
    return Response.json({ message: 'Invalid JSON body.' }, { status: 422 });
  }

  try {
    const response = await fetch(`${apiUrl}/api/testimonials`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(body),
      cache: 'no-store',
      signal: AbortSignal.timeout(15_000),
    });

    const data = (await response.json().catch(() => null)) as unknown;

    return Response.json(data ?? { message: response.statusText }, { status: response.status });
  } catch {
    return Response.json(
      { message: 'The testimonial service is temporarily unavailable.' },
      { status: 503 },
    );
  }
}

import type { Locale } from './types';

export interface TestimonialSubmissionPayload {
  name: string;
  email: string;
  role?: string;
  company?: string;
  category: string;
  rating: number;
  quote: string;
  locale: Locale;
  website?: string;
}

export interface TestimonialSubmissionResult {
  ok: boolean;
  message?: string;
  errors?: Record<string, string[]>;
}

/**
 * Testimonials are posted to the same-origin `/api/testimonials` route, which
 * forwards to Laravel server-side. No CORS and no public API URL are required.
 */
export async function submitTestimonial(
  payload: TestimonialSubmissionPayload,
): Promise<TestimonialSubmissionResult> {
  try {
    const response = await fetch('/api/testimonials', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (response.ok) {
      return { ok: true };
    }

    const data = (await response.json().catch(() => null)) as
      | { message?: string; errors?: Record<string, string[]> }
      | null;

    return {
      ok: false,
      message: data?.message,
      errors: data?.errors,
    };
  } catch {
    return { ok: false };
  }
}

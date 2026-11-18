export interface MessagePayload {
  type: 'contact';
  name: string;
  email: string;
  subject?: string;
  message: string;
  website?: string;
}

export interface MessageResult {
  ok: boolean;
  message?: string;
  errors?: Record<string, string[]>;
}

/**
 * Submissions go through the same-origin `/api/messages` route, which forwards
 * to Laravel server-side. No CORS and no public API URL are required.
 */
export async function submitMessage(payload: MessagePayload): Promise<MessageResult> {
  try {
    const response = await fetch('/api/messages', {
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

'use client';

import { useState } from 'react';
import type { ChangeEvent, FormEvent } from 'react';
import { Icon } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { submitMessage } from '@/lib/messages';

type Status = 'idle' | 'sending' | 'success' | 'error';

const initialValues = {
  name: '',
  email: '',
  subject: '',
  message: '',
  website: '',
};

export function ContactForm({ dictionary }: { dictionary: Dictionary }) {
  const [values, setValues] = useState(initialValues);
  const [errors, setErrors] = useState<Record<string, string | undefined>>({});
  const [status, setStatus] = useState<Status>('idle');
  const [generalError, setGeneralError] = useState('');

  function update(field: keyof typeof initialValues) {
    return (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
      setValues((current) => ({ ...current, [field]: event.target.value }));
      setErrors((current) => ({ ...current, [field]: undefined }));
    };
  }

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    const nextErrors: Record<string, string | undefined> = {};

    if (values.name.trim().length < 2) {
      nextErrors.name = dictionary.contact.required;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(values.email)) {
      nextErrors.email = dictionary.contact.invalidEmail;
    }

    if (values.message.trim().length < 5) {
      nextErrors.message = dictionary.contact.tooShort;
    }

    setErrors(nextErrors);

    if (Object.keys(nextErrors).length > 0) {
      return;
    }

    setStatus('sending');
    setGeneralError('');

    const result = await submitMessage({
      type: 'contact',
      name: values.name.trim(),
      email: values.email.trim(),
      subject: values.subject.trim(),
      message: values.message.trim(),
      website: values.website,
    });

    if (result.ok) {
      setStatus('success');
      setValues(initialValues);

      return;
    }

    setStatus('error');
    setGeneralError(dictionary.contact.error);

    if (result.errors) {
      setErrors(
        Object.fromEntries(
          Object.entries(result.errors).map(([key, messages]) => [key, messages[0]]),
        ),
      );
    }
  }

  if (status === 'success') {
    return (
      <div className="flex h-full flex-col items-center justify-center rounded-2xl border border-accent-line bg-accent-soft/50 p-8 text-center">
        <span className="flex h-14 w-14 items-center justify-center rounded-full border border-accent-line bg-bg/50 text-accent">
          <Icon name="check" size={26} />
        </span>
        <h3 className="mt-5 text-lg font-semibold text-ink">{dictionary.contact.success}</h3>
        <p className="mt-2 max-w-sm text-sm leading-relaxed text-muted">
          {dictionary.contact.responseTime}
        </p>
        <button
          type="button"
          onClick={() => setStatus('idle')}
          className="btn-ghost mt-6 !py-2.5 text-xs"
        >
          {dictionary.contact.sendAnother}
          <Icon name="send" size={13} />
        </button>
      </div>
    );
  }

  return (
    <form onSubmit={onSubmit} noValidate className="rounded-2xl border border-line bg-elevated/60 p-5 sm:p-6">
      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label htmlFor="contact-name" className="field-label">
            {dictionary.contact.name}
          </label>
          <input
            id="contact-name"
            name="name"
            type="text"
            autoComplete="name"
            value={values.name}
            onChange={update('name')}
            placeholder={dictionary.contact.namePlaceholder}
            aria-invalid={Boolean(errors.name)}
            aria-describedby={errors.name ? 'contact-name-error' : undefined}
            className="field-input"
          />
          {errors.name && (
            <p id="contact-name-error" className="field-error">
              {errors.name}
            </p>
          )}
        </div>

        <div>
          <label htmlFor="contact-email" className="field-label">
            {dictionary.contact.email}
          </label>
          <input
            id="contact-email"
            name="email"
            type="email"
            autoComplete="email"
            value={values.email}
            onChange={update('email')}
            placeholder={dictionary.contact.emailPlaceholder}
            aria-invalid={Boolean(errors.email)}
            aria-describedby={errors.email ? 'contact-email-error' : undefined}
            className="field-input"
          />
          {errors.email && (
            <p id="contact-email-error" className="field-error">
              {errors.email}
            </p>
          )}
        </div>
      </div>

      <div className="mt-4">
        <label htmlFor="contact-subject" className="field-label">
          {dictionary.contact.subject}
        </label>
        <input
          id="contact-subject"
          name="subject"
          type="text"
          value={values.subject}
          onChange={update('subject')}
          placeholder={dictionary.contact.subjectPlaceholder}
          aria-invalid={Boolean(errors.subject)}
          className="field-input"
        />
        {errors.subject && <p className="field-error">{errors.subject}</p>}
      </div>

      <div className="mt-4">
        <label htmlFor="contact-message" className="field-label">
          {dictionary.contact.message}
        </label>
        <textarea
          id="contact-message"
          name="message"
          rows={5}
          value={values.message}
          onChange={update('message')}
          placeholder={dictionary.contact.messagePlaceholder}
          aria-invalid={Boolean(errors.message)}
          aria-describedby={errors.message ? 'contact-message-error' : undefined}
          className="field-input resize-y"
        />
        {errors.message && (
          <p id="contact-message-error" className="field-error">
            {errors.message}
          </p>
        )}
      </div>

      <div className="honeypot" aria-hidden="true">
        <label htmlFor="contact-website">Website</label>
        <input
          id="contact-website"
          name="website"
          type="text"
          tabIndex={-1}
          autoComplete="off"
          value={values.website}
          onChange={update('website')}
        />
      </div>

      {generalError && (
        <div className="mt-4 flex items-start gap-2 rounded-lg border border-red-500/30 bg-red-500/5 px-3 py-2.5 text-xs text-red-500">
          <Icon name="close" size={14} className="mt-0.5 shrink-0" />
          {generalError}
        </div>
      )}

      <div className="mt-5 flex flex-wrap items-center justify-between gap-3">
        <p className="text-[11px] text-faint">{dictionary.contact.privacy}</p>
        <button type="submit" disabled={status === 'sending'} className="btn-primary disabled:opacity-60">
          {status === 'sending' ? dictionary.contact.sending : dictionary.contact.send}
          <Icon name={status === 'sending' ? 'refresh' : 'send'} size={14} />
        </button>
      </div>
    </form>
  );
}

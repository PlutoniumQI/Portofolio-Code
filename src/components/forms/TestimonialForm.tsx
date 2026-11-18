'use client';

import { useState } from 'react';
import type { ChangeEvent, FormEvent } from 'react';
import { Icon } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { submitTestimonial } from '@/lib/testimonials';
import type { Locale } from '@/lib/types';

type Status = 'idle' | 'sending' | 'success' | 'error';

interface Values {
  name: string;
  email: string;
  role: string;
  company: string;
  category: string;
  rating: number;
  quote: string;
  website: string;
}

const initialValues: Values = {
  name: '',
  email: '',
  role: '',
  company: '',
  category: 'client',
  rating: 5,
  quote: '',
  website: '',
};

export function TestimonialForm({
  dictionary,
  locale,
}: {
  dictionary: Dictionary;
  locale: Locale;
}) {
  const [values, setValues] = useState<Values>(initialValues);
  const [errors, setErrors] = useState<Record<string, string | undefined>>({});
  const [status, setStatus] = useState<Status>('idle');
  const [generalError, setGeneralError] = useState('');

  const copy = dictionary.testimonials.write;

  function update(field: keyof Values) {
    return (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
      setValues((current) => ({ ...current, [field]: event.target.value }));
      setErrors((current) => ({ ...current, [field]: undefined }));
    };
  }

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    const nextErrors: Record<string, string | undefined> = {};

    if (values.name.trim().length < 2) {
      nextErrors.name = copy.required;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(values.email.trim())) {
      nextErrors.email = copy.invalidEmail;
    }

    if (values.quote.trim().length < 10) {
      nextErrors.quote = copy.tooShort;
    }

    setErrors(nextErrors);

    if (Object.values(nextErrors).some(Boolean)) {
      return;
    }

    setStatus('sending');
    setGeneralError('');

    const result = await submitTestimonial({
      name: values.name.trim(),
      email: values.email.trim(),
      role: values.role.trim() || undefined,
      company: values.company.trim() || undefined,
      category: values.category,
      rating: values.rating,
      quote: values.quote.trim(),
      locale,
      website: values.website,
    });

    if (result.ok) {
      setStatus('success');
      setValues(initialValues);

      return;
    }

    setStatus('error');
    setGeneralError(copy.error);

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
        <h3 className="mt-5 text-lg font-semibold text-ink">{copy.successTitle}</h3>
        <p className="mt-2 max-w-sm text-sm leading-relaxed text-muted">{copy.successDesc}</p>
        <button
          type="button"
          onClick={() => setStatus('idle')}
          className="btn-ghost mt-6 !py-2.5 text-xs"
        >
          {copy.addAnother}
          <Icon name="quote" size={13} />
        </button>
      </div>
    );
  }

  return (
    <form
      onSubmit={onSubmit}
      noValidate
      className="rounded-2xl border border-line bg-elevated/60 p-5 sm:p-6"
    >
      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label htmlFor="testimonial-name" className="field-label">
            {copy.name}
          </label>
          <input
            id="testimonial-name"
            name="name"
            type="text"
            autoComplete="name"
            value={values.name}
            onChange={update('name')}
            placeholder={copy.namePlaceholder}
            aria-invalid={Boolean(errors.name)}
            aria-describedby={errors.name ? 'testimonial-name-error' : undefined}
            className="field-input"
          />
          {errors.name && (
            <p id="testimonial-name-error" className="field-error">
              {errors.name}
            </p>
          )}
        </div>

        <div>
          <label htmlFor="testimonial-email" className="field-label">
            {copy.email}
          </label>
          <input
            id="testimonial-email"
            name="email"
            type="email"
            autoComplete="email"
            value={values.email}
            onChange={update('email')}
            placeholder={copy.emailPlaceholder}
            aria-invalid={Boolean(errors.email)}
            aria-describedby={errors.email ? 'testimonial-email-error' : undefined}
            className="field-input"
          />
          {errors.email && (
            <p id="testimonial-email-error" className="field-error">
              {errors.email}
            </p>
          )}
        </div>

        <div>
          <label htmlFor="testimonial-role" className="field-label">
            {copy.role}
          </label>
          <input
            id="testimonial-role"
            name="role"
            type="text"
            value={values.role}
            onChange={update('role')}
            placeholder={copy.rolePlaceholder}
            className="field-input"
          />
        </div>

        <div>
          <label htmlFor="testimonial-company" className="field-label">
            {copy.company}
          </label>
          <input
            id="testimonial-company"
            name="company"
            type="text"
            value={values.company}
            onChange={update('company')}
            placeholder={copy.companyPlaceholder}
            className="field-input"
          />
        </div>

        <div>
          <label htmlFor="testimonial-category" className="field-label">
            {copy.category}
          </label>
          <select
            id="testimonial-category"
            name="category"
            value={values.category}
            onChange={update('category')}
            className="field-input"
          >
            {Object.entries(dictionary.testimonialCategories).map(([value, label]) => (
              <option key={value} value={value}>
                {label}
              </option>
            ))}
          </select>
        </div>

        <div>
          <span className="field-label">{copy.rating}</span>
          <div
            className="flex items-center gap-1.5"
            role="radiogroup"
            aria-label={copy.rating}
          >
            {[1, 2, 3, 4, 5].map((value) => (
              <button
                key={value}
                type="button"
                role="radio"
                aria-checked={values.rating === value}
                aria-label={`${value} / 5`}
                onClick={() => setValues((current) => ({ ...current, rating: value }))}
                className={`flex h-9 w-9 items-center justify-center rounded-lg border transition-colors ${
                  values.rating >= value
                    ? 'border-accent-line bg-accent-soft text-accent'
                    : 'border-line text-faint hover:border-accent-line hover:text-accent'
                }`}
              >
                <Icon name="star" size={16} />
              </button>
            ))}
          </div>
        </div>
      </div>

      <div className="mt-4">
        <label htmlFor="testimonial-quote" className="field-label">
          {copy.quote}
        </label>
        <textarea
          id="testimonial-quote"
          name="quote"
          rows={5}
          value={values.quote}
          onChange={update('quote')}
          placeholder={copy.quotePlaceholder}
          aria-invalid={Boolean(errors.quote)}
          aria-describedby={errors.quote ? 'testimonial-quote-error' : undefined}
          className="field-input resize-y"
        />
        {errors.quote && (
          <p id="testimonial-quote-error" className="field-error">
            {errors.quote}
          </p>
        )}
      </div>

      <div className="honeypot" aria-hidden="true">
        <label htmlFor="testimonial-website">Website</label>
        <input
          id="testimonial-website"
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
        <p className="text-[11px] text-faint">{copy.privacy}</p>
        <button type="submit" disabled={status === 'sending'} className="btn-primary disabled:opacity-60">
          {status === 'sending' ? copy.sending : copy.submit}
          <Icon name={status === 'sending' ? 'refresh' : 'send'} size={14} />
        </button>
      </div>
    </form>
  );
}

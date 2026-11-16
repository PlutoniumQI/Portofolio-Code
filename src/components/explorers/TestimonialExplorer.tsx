'use client';

import { useMemo, useState } from 'react';
import { EmptyState } from '@/components/ui/EmptyState';
import { TestimonialCard } from '@/components/ui/TestimonialCard';
import type { Dictionary } from '@/lib/dictionaries';
import { testimonialCategoryLabel } from '@/lib/dictionaries';
import type { Testimonial } from '@/lib/types';

export function TestimonialExplorer({
  testimonials,
  dictionary,
}: {
  testimonials: Testimonial[];
  dictionary: Dictionary;
}) {
  const [active, setActive] = useState<string | null>(null);

  const categories = useMemo(
    () => Array.from(new Set(testimonials.map((testimonial) => testimonial.category))),
    [testimonials],
  );

  const filtered = active
    ? testimonials.filter((testimonial) => testimonial.category === active)
    : testimonials;

  return (
    <div>
      <div className="flex flex-wrap gap-2">
        <button
          type="button"
          onClick={() => setActive(null)}
          aria-pressed={active === null}
          className={`rounded-full border px-3.5 py-1.5 text-xs transition-colors ${
            active === null
              ? 'border-accent-line bg-accent-soft text-accent'
              : 'border-line text-muted hover:border-accent-line hover:text-accent'
          }`}
        >
          {dictionary.testimonials.all}
          <span className="ml-1.5 font-mono text-[9px] opacity-60">{testimonials.length}</span>
        </button>

        {categories.map((category) => {
          const count = testimonials.filter((testimonial) => testimonial.category === category).length;

          return (
            <button
              key={category}
              type="button"
              onClick={() => setActive(category)}
              aria-pressed={active === category}
              className={`rounded-full border px-3.5 py-1.5 text-xs transition-colors ${
                active === category
                  ? 'border-accent-line bg-accent-soft text-accent'
                  : 'border-line text-muted hover:border-accent-line hover:text-accent'
              }`}
            >
              {testimonialCategoryLabel(dictionary, category)}
              <span className="ml-1.5 font-mono text-[9px] opacity-60">{count}</span>
            </button>
          );
        })}
      </div>

      {filtered.length > 0 ? (
        <div className="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {filtered.map((testimonial) => (
            <div key={testimonial.id} className="reveal visible">
              <TestimonialCard testimonial={testimonial} dictionary={dictionary} />
            </div>
          ))}
        </div>
      ) : (
        <div className="mt-8">
          <EmptyState
            icon="quote"
            title={dictionary.testimonials.empty}
            action={
              <button type="button" onClick={() => setActive(null)} className="btn-ghost !py-2 text-xs">
                {dictionary.common.reset}
              </button>
            }
          />
        </div>
      )}
    </div>
  );
}

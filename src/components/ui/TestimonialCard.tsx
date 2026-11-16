import { Avatar } from '@/components/ui/Avatar';
import { Icon } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { testimonialCategoryLabel } from '@/lib/dictionaries';
import type { Testimonial } from '@/lib/types';

export function TestimonialCard({
  testimonial,
  dictionary,
}: {
  testimonial: Testimonial;
  dictionary: Dictionary;
}) {
  const rating = Math.max(0, Math.min(5, testimonial.rating || 5));

  return (
    <figure className="relative flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-elevated/70 p-5 transition-colors hover:border-accent-line">
      <div className="flex items-start justify-between gap-3">
        <span className="flex h-9 w-9 items-center justify-center rounded-full border border-accent-line bg-accent-soft text-accent">
          <Icon name="quote" size={15} />
        </span>
        <span className="chip">{testimonialCategoryLabel(dictionary, testimonial.category)}</span>
      </div>

      <blockquote className="mt-4 flex-1 text-sm leading-relaxed text-ink-soft">
        “{testimonial.quote}”
      </blockquote>

      <div className="mt-5 flex items-center gap-1 text-accent" aria-label={`${dictionary.testimonials.rating}: ${rating}/5`}>
        {Array.from({ length: 5 }).map((_, index) => (
          <Icon key={index} name="star" size={12} className={index < rating ? 'opacity-100' : 'opacity-20'} />
        ))}
      </div>

      <figcaption className="mt-4 flex items-center gap-3 border-t border-line pt-4">
        <Avatar src={testimonial.avatar_url} name={testimonial.name} size="md" />
        <div className="min-w-0">
          <p className="truncate text-sm font-medium text-ink">{testimonial.name}</p>
          <p className="truncate text-xs text-muted">
            {[testimonial.role, testimonial.company].filter(Boolean).join(' · ')}
          </p>
        </div>
      </figcaption>
    </figure>
  );
}

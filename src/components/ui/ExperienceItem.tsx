import { Icon, type IconName } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { experienceTypeLabel } from '@/lib/dictionaries';
import type { Experience } from '@/lib/types';

const typeIcons: Record<string, IconName> = {
  work: 'briefcase',
  organization: 'users',
  competition: 'trophy',
  internship: 'briefcase',
  education: 'academic-cap',
};

export function ExperienceItem({
  experience,
  dictionary,
}: {
  experience: Experience;
  dictionary: Dictionary;
}) {
  const typeLabel = experienceTypeLabel(dictionary, experience.type);
  const icon = typeIcons[experience.type] ?? 'sparkles';

  return (
    <article className="exp-item group grid gap-3 pb-10 last:pb-0 md:grid-cols-[7rem_1fr] md:gap-10">
      <div className="hidden md:block md:pr-5 md:text-right">
        <p className="pt-1 font-mono text-[10px] uppercase leading-relaxed tracking-[0.08em] text-accent">
          {experience.date_range}
        </p>
      </div>

      <div className="relative">
        <span
          className="absolute -left-[1.95rem] top-6 hidden h-2.5 w-2.5 -translate-x-1/2 rounded-full border border-accent bg-bg md:block"
          aria-hidden="true"
        />

        <div className="rounded-2xl border border-line bg-elevated/60 p-5 transition-colors duration-300 group-hover:border-accent-line sm:p-6">
          <div className="flex flex-wrap items-center justify-between gap-2">
            <span className="inline-flex items-center gap-1.5 rounded-full border border-accent-line bg-accent-soft px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.14em] text-accent">
              <Icon name={icon} size={11} />
              {typeLabel}
            </span>
            <span className="font-mono text-[10px] uppercase tracking-[0.08em] text-faint md:hidden">
              {experience.date_range}
            </span>
          </div>

          <h3 className="mt-3 text-base font-semibold tracking-tight text-ink sm:text-lg">
            {experience.title}
          </h3>

          <p className="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-muted">
            <span className="font-medium text-ink-soft">{experience.company}</span>
            {experience.location && (
              <>
                <span className="h-1 w-1 rounded-full bg-line-strong" aria-hidden="true" />
                <span className="inline-flex items-center gap-1">
                  <Icon name="map-pin" size={12} />
                  {experience.location}
                </span>
              </>
            )}
          </p>

          {experience.description && (
            <p className="mt-3 text-sm leading-relaxed text-muted">{experience.description}</p>
          )}

          {experience.tags.length > 0 && (
            <div className="mt-4 flex flex-wrap gap-1.5">
              {experience.tags.map((tag) => (
                <span key={tag} className="chip">
                  {tag}
                </span>
              ))}
            </div>
          )}

          {experience.url && (
            <a
              href={experience.url}
              target="_blank"
              rel="noreferrer noopener"
              className="mt-4 inline-flex items-center gap-1.5 text-xs font-medium text-accent transition-opacity hover:opacity-75"
            >
              {dictionary.common.visitSite}
              <Icon name="arrow-up-right" size={12} />
            </a>
          )}
        </div>
      </div>
    </article>
  );
}

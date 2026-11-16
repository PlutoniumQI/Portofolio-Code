'use client';

import { useMemo, useState } from 'react';
import { ExperienceItem } from '@/components/ui/ExperienceItem';
import { EmptyState } from '@/components/ui/EmptyState';
import type { Dictionary } from '@/lib/dictionaries';
import { experienceTypeLabel } from '@/lib/dictionaries';
import type { Experience } from '@/lib/types';

const typeOrder = ['internship', 'work', 'organization', 'competition', 'education'];

export function ExperienceExplorer({
  experiences,
  dictionary,
}: {
  experiences: Experience[];
  dictionary: Dictionary;
}) {
  const [active, setActive] = useState<string | null>(null);

  const types = useMemo(() => {
    const unique = Array.from(new Set(experiences.map((experience) => experience.type)));

    return unique.sort((a, b) => {
      const aIndex = typeOrder.indexOf(a);
      const bIndex = typeOrder.indexOf(b);

      return (aIndex === -1 ? 99 : aIndex) - (bIndex === -1 ? 99 : bIndex);
    });
  }, [experiences]);

  const filtered = active
    ? experiences.filter((experience) => experience.type === active)
    : experiences;

  return (
    <div>
      <div className="flex flex-wrap items-center gap-2">
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
          {dictionary.experience.allTypes}
          <span className="ml-1.5 font-mono text-[9px] opacity-60">{experiences.length}</span>
        </button>

        {types.map((type) => {
          const count = experiences.filter((experience) => experience.type === type).length;

          return (
            <button
              key={type}
              type="button"
              onClick={() => setActive(type)}
              aria-pressed={active === type}
              className={`rounded-full border px-3.5 py-1.5 text-xs transition-colors ${
                active === type
                  ? 'border-accent-line bg-accent-soft text-accent'
                  : 'border-line text-muted hover:border-accent-line hover:text-accent'
              }`}
            >
              {experienceTypeLabel(dictionary, type)}
              <span className="ml-1.5 font-mono text-[9px] opacity-60">{count}</span>
            </button>
          );
        })}
      </div>

      {filtered.length > 0 ? (
        <div className="mt-9">
          {filtered.map((experience) => (
            <ExperienceItem key={experience.id} experience={experience} dictionary={dictionary} />
          ))}
        </div>
      ) : (
        <div className="mt-9">
          <EmptyState
            icon="briefcase"
            title={dictionary.experience.empty}
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

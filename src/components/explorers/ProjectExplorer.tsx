'use client';

import { useMemo, useState } from 'react';
import { Icon } from '@/components/ui/Icons';
import { ProjectCard } from '@/components/ui/ProjectCard';
import { EmptyState } from '@/components/ui/EmptyState';
import type { Dictionary } from '@/lib/dictionaries';
import type { CategoryOption, Locale, Project } from '@/lib/types';

export function ProjectExplorer({
  projects,
  categories,
  locale,
  dictionary,
}: {
  projects: Project[];
  categories: CategoryOption[];
  locale: Locale;
  dictionary: Dictionary;
}) {
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('all');

  const filtered = useMemo(() => {
    const search = query.trim().toLowerCase();

    return projects.filter((project) => {
      if (category !== 'all' && project.category !== category) {
        return false;
      }

      if (!search) {
        return true;
      }

      return [project.title, project.excerpt, project.description, project.category_label, project.category, ...project.tags]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(search);
    });
  }, [projects, query, category]);

  return (
    <div>
      <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <label className="relative block w-full lg:max-w-sm">
          <span className="sr-only">{dictionary.projects.searchPlaceholder}</span>
          <Icon
            name="search"
            size={16}
            className="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-faint"
          />
          <input
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder={dictionary.projects.searchPlaceholder}
            className="field-input !pl-10"
          />
        </label>

        <p className="font-mono text-[11px] uppercase tracking-[0.12em] text-faint">
          {filtered.length} {dictionary.projects.count}
        </p>
      </div>

      <div className="mt-5 flex flex-wrap gap-2">
        <button
          type="button"
          onClick={() => setCategory('all')}
          aria-pressed={category === 'all'}
          className={`rounded-full border px-3.5 py-1.5 text-xs transition-colors ${
            category === 'all'
              ? 'border-accent-line bg-accent-soft text-accent'
              : 'border-line text-muted hover:border-accent-line hover:text-accent'
          }`}
        >
          {dictionary.projects.allCategories}
          <span className="ml-1.5 font-mono text-[9px] opacity-60">{projects.length}</span>
        </button>

        {categories.map((option) => (
          <button
            key={option.value}
            type="button"
            onClick={() => setCategory(option.value)}
            aria-pressed={category === option.value}
            className={`rounded-full border px-3.5 py-1.5 text-xs transition-colors ${
              category === option.value
                ? 'border-accent-line bg-accent-soft text-accent'
                : 'border-line text-muted hover:border-accent-line hover:text-accent'
            }`}
          >
            {option.label}
            {typeof option.count === 'number' && (
              <span className="ml-1.5 font-mono text-[9px] opacity-60">{option.count}</span>
            )}
          </button>
        ))}
      </div>

      {filtered.length > 0 ? (
        <div className="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {filtered.map((project, index) => (
            <div key={project.id} className="reveal visible">
              <ProjectCard
                project={project}
                locale={locale}
                dictionary={dictionary}
                index={index}
              />
            </div>
          ))}
        </div>
      ) : (
        <div className="mt-8">
          <EmptyState
            icon="search"
            title={dictionary.projects.empty}
            action={
              <button
                type="button"
                onClick={() => {
                  setQuery('');
                  setCategory('all');
                }}
                className="btn-ghost !py-2 text-xs"
              >
                {dictionary.common.reset}
                <Icon name="refresh" size={13} />
              </button>
            }
          />
        </div>
      )}
    </div>
  );
}

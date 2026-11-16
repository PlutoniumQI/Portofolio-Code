'use client';

import { useMemo, useState } from 'react';
import { EmptyState } from '@/components/ui/EmptyState';
import { Icon } from '@/components/ui/Icons';
import { PostCard } from '@/components/ui/PostCard';
import type { Dictionary } from '@/lib/dictionaries';
import type { Locale, Post } from '@/lib/types';

export function BlogExplorer({
  posts,
  locale,
  dictionary,
}: {
  posts: Post[];
  locale: Locale;
  dictionary: Dictionary;
}) {
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('all');

  const categories = useMemo(
    () => Array.from(new Set(posts.map((post) => post.category).filter(Boolean))) as string[],
    [posts],
  );

  const filtered = useMemo(() => {
    const search = query.trim().toLowerCase();

    return posts.filter((post) => {
      if (category !== 'all' && post.category !== category) {
        return false;
      }

      if (!search) {
        return true;
      }

      return [post.title, post.excerpt, post.category, ...post.tags]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(search);
    });
  }, [posts, query, category]);

  return (
    <div>
      <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <label className="relative block w-full lg:max-w-sm">
          <span className="sr-only">{dictionary.blog.searchPlaceholder}</span>
          <Icon
            name="search"
            size={16}
            className="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-faint"
          />
          <input
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder={dictionary.blog.searchPlaceholder}
            className="field-input !pl-10"
          />
        </label>

        <p className="font-mono text-[11px] uppercase tracking-[0.12em] text-faint">
          {filtered.length} {dictionary.common.results}
        </p>
      </div>

      {categories.length > 0 && (
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
            {dictionary.blog.allCategories}
          </button>
          {categories.map((option) => (
            <button
              key={option}
              type="button"
              onClick={() => setCategory(option)}
              aria-pressed={category === option}
              className={`rounded-full border px-3.5 py-1.5 text-xs transition-colors ${
                category === option
                  ? 'border-accent-line bg-accent-soft text-accent'
                  : 'border-line text-muted hover:border-accent-line hover:text-accent'
              }`}
            >
              {option}
            </button>
          ))}
        </div>
      )}

      {filtered.length > 0 ? (
        <div className="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {filtered.map((post) => (
            <div key={post.id} className="reveal visible">
              <PostCard post={post} locale={locale} dictionary={dictionary} />
            </div>
          ))}
        </div>
      ) : (
        <div className="mt-8">
          <EmptyState
            icon="book"
            title={dictionary.blog.empty}
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

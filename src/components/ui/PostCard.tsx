import Link from 'next/link';
import { Icon } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { formatDate, localePath } from '@/lib/i18n';
import type { Locale, Post } from '@/lib/types';

export function PostCard({
  post,
  locale,
  dictionary,
}: {
  post: Post;
  locale: Locale;
  dictionary: Dictionary;
}) {
  return (
    <Link
      href={localePath(locale, `/blog/${encodeURIComponent(post.slug)}`)}
      className="group flex flex-col overflow-hidden rounded-2xl border border-line bg-elevated/70 transition-all duration-300 hover:-translate-y-1 hover:border-accent-line"
    >
      <div className="relative aspect-[16/9] overflow-hidden border-b border-line">
        {post.cover_url ? (
          <img
            src={post.cover_url}
            alt={post.title}
            loading="lazy"
            className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
          />
        ) : (
          <div className="project-art relative flex h-full items-center justify-center">
            <div className="art-grid absolute inset-0 opacity-60" aria-hidden="true" />
            <div className="relative flex h-16 w-16 -rotate-6 items-center justify-center rounded-2xl border border-line-strong bg-elevated/70 text-accent transition-transform duration-500 group-hover:rotate-0">
              <Icon name="book" size={26} />
            </div>
            <span className="absolute bottom-4 right-5 font-mono text-[10px] uppercase tracking-[0.2em] text-faint">
              {post.category ?? 'notes'}
            </span>
          </div>
        )}

        {post.is_featured && (
          <span className="absolute right-3 top-3 rounded-full border border-accent-line bg-accent-soft px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.14em] text-accent backdrop-blur-sm">
            {dictionary.common.featured}
          </span>
        )}
      </div>

      <div className="flex flex-1 flex-col p-5">
        <div className="flex flex-wrap items-center gap-2 font-mono text-[10px] uppercase tracking-[0.12em] text-faint">
          <span className="text-accent">{post.category ?? dictionary.nav.blog}</span>
          <span className="h-1 w-1 rounded-full bg-line-strong" />
          <time dateTime={post.published_at ?? undefined}>
            {formatDate(post.published_at, locale)}
          </time>
          <span className="h-1 w-1 rounded-full bg-line-strong" />
          <span>
            {post.reading_time} {dictionary.common.minutes}
          </span>
        </div>

        <h3 className="mt-2.5 text-lg font-semibold leading-snug tracking-tight text-ink transition-colors group-hover:text-accent">
          {post.title}
        </h3>

        {post.excerpt && (
          <p className="mt-2 line-clamp-2 text-sm leading-relaxed text-muted">{post.excerpt}</p>
        )}

        <div className="mt-auto flex items-center justify-between pt-5 text-[11px] text-faint">
          <span className="inline-flex items-center gap-1.5 font-medium text-ink transition-colors group-hover:text-accent">
            {dictionary.common.readMore}
            <Icon
              name="arrow-right"
              size={13}
              className="transition-transform group-hover:translate-x-1"
            />
          </span>
          <div className="flex gap-1.5">
            {post.tags.slice(0, 2).map((tag) => (
              <span key={tag} className="chip">
                {tag}
              </span>
            ))}
          </div>
        </div>
      </div>
    </Link>
  );
}

import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { Icon } from '@/components/ui/Icons';
import { JsonLd } from '@/components/ui/JsonLd';
import { PostCard } from '@/components/ui/PostCard';
import { Prose } from '@/components/ui/Prose';
import { Container, Section, SectionHeading } from '@/components/ui/Section';
import { findPost, getPortfolio, getSiteUrl } from '@/lib/api';
import { getDictionary } from '@/lib/dictionaries';
import { formatDate, isLocale, localePath } from '@/lib/i18n';
import type { Locale } from '@/lib/types';

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string; slug: string }>;
}): Promise<Metadata> {
  const { locale, slug } = await params;

  if (!isLocale(locale)) {
    return {};
  }

  const portfolio = await getPortfolio(locale);
  const post = findPost(portfolio, slug);

  if (!post) {
    // Fail before the streaming shell is flushed, so the response is a real 404.
    notFound();
  }

  const canonical = localePath(locale, `/blog/${post.slug}`);
  const description = post.excerpt ?? post.title;

  return {
    title: post.title,
    description,
    alternates: {
      canonical,
      languages: {
        id: `/id/blog/${post.slug}`,
        en: `/en/blog/${post.slug}`,
        'x-default': `/id/blog/${post.slug}`,
      },
    },
    openGraph: {
      type: 'article',
      url: canonical,
      title: post.title,
      description,
      publishedTime: post.published_at ?? undefined,
      images: post.cover_url ? [{ url: post.cover_url }] : undefined,
    },
  };
}

export default async function BlogPostPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string }>;
}) {
  const { locale: rawLocale, slug } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale: Locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const post = findPost(portfolio, slug);

  if (!post) {
    notFound();
  }

  const related = portfolio.posts.filter((item) => item.id !== post.id).slice(0, 2);

  return (
    <>
      <JsonLd
        data={{
          '@context': 'https://schema.org',
          '@type': 'BlogPosting',
          headline: post.title,
          description: post.excerpt ?? undefined,
          datePublished: post.published_at ?? undefined,
          image: post.cover_url ?? undefined,
          articleSection: post.category ?? undefined,
          keywords: post.tags.join(', '),
          wordCount: undefined,
          author: { '@type': 'Person', name: portfolio.profile.full_name },
          publisher: { '@type': 'Person', name: portfolio.profile.full_name },
          mainEntityOfPage: `${getSiteUrl()}${localePath(locale, `/blog/${post.slug}`)}`,
        }}
      />

      <div className="border-b border-line">
        <Container className="flex items-center justify-between gap-4 py-5">
          <Link
            href={localePath(locale, '/blog')}
            className="inline-flex items-center gap-2 text-xs text-muted transition-colors hover:text-accent"
          >
            <Icon name="arrow-left" size={14} />
            {dictionary.blog.backToBlog}
          </Link>
          <span className="font-mono text-[10px] uppercase tracking-[0.16em] text-faint">
            {post.reading_time} {dictionary.common.minutes}
          </span>
        </Container>
      </div>

      <Container className="py-10 sm:py-14">
        <article className="mx-auto max-w-3xl">
          <div className="flex flex-wrap items-center gap-2 font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
            {post.category && <span className="text-accent">{post.category}</span>}
            {post.published_at && (
              <>
                <span className="h-1 w-1 rounded-full bg-line-strong" />
                <time dateTime={post.published_at}>{formatDate(post.published_at, locale)}</time>
              </>
            )}
            <span className="h-1 w-1 rounded-full bg-line-strong" />
            <span>
              {post.reading_time} {dictionary.common.minutes}
            </span>
          </div>

          <h1 className="mt-4 text-3xl font-semibold leading-[1.12] tracking-tight text-ink sm:text-5xl">
            {post.title}
          </h1>

          {post.excerpt && (
            <p className="mt-5 text-base leading-relaxed text-muted sm:text-lg">{post.excerpt}</p>
          )}

          <div className="mt-8 overflow-hidden rounded-2xl border border-line sm:rounded-3xl">
            {post.cover_url ? (
              <img
                src={post.cover_url}
                alt={post.title}
                className="aspect-[16/9] w-full object-cover"
              />
            ) : (
              <div className="project-art relative flex aspect-[16/7] items-center justify-center">
                <div className="art-grid absolute inset-0 opacity-60" aria-hidden="true" />
                <div className="relative flex h-20 w-20 -rotate-6 items-center justify-center rounded-2xl border border-line-strong bg-elevated/70 text-accent">
                  <Icon name="book" size={32} />
                </div>
                <span className="absolute bottom-5 right-6 font-mono text-[10px] uppercase tracking-[0.24em] text-faint">
                  re:SKY notes
                </span>
              </div>
            )}
          </div>

          {post.body ? (
            <Prose html={post.body} className="mt-9" />
          ) : (
            <p className="mt-9 rounded-2xl border border-dashed border-line-strong bg-elevated/40 p-6 text-sm text-muted">
              {dictionary.blog.noContent}
            </p>
          )}

          <div className="mt-10 flex flex-col gap-4 border-t border-line pt-6 sm:flex-row sm:items-center sm:justify-between">
            <div className="flex flex-wrap gap-1.5">
              {post.tags.map((tag) => (
                <span key={tag} className="chip">
                  {tag}
                </span>
              ))}
            </div>
            <Link
              href={localePath(locale, '/blog')}
              className="inline-flex items-center gap-2 text-xs font-medium text-accent transition-opacity hover:opacity-75"
            >
              <Icon name="arrow-left" size={13} />
              {dictionary.blog.backToBlog}
            </Link>
          </div>
        </article>
      </Container>

      {related.length > 0 && (
        <Section className="border-t border-line bg-elevated/20">
          <SectionHeading
            eyebrow={dictionary.blog.relatedTitle}
            title={dictionary.blog.relatedTitle}
            action={
              <Link href={localePath(locale, '/blog')} className="btn-ghost">
                {dictionary.common.viewAll}
                <Icon name="arrow-right" size={14} />
              </Link>
            }
          />
          <div className="mt-8 grid gap-5 sm:grid-cols-2">
            {related.map((item) => (
              <PostCard key={item.id} post={item} locale={locale} dictionary={dictionary} />
            ))}
          </div>
        </Section>
      )}
    </>
  );
}

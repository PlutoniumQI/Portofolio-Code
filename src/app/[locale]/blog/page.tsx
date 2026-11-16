import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { BlogExplorer } from '@/components/explorers/BlogExplorer';
import { PageHero, Section } from '@/components/ui/Section';
import { getPortfolio } from '@/lib/api';
import { getDictionary } from '@/lib/dictionaries';
import { isLocale, localePath } from '@/lib/i18n';

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;

  if (!isLocale(locale)) {
    return {};
  }

  const dictionary = getDictionary(locale);

  return {
    title: dictionary.meta.blog.title,
    description: dictionary.meta.blog.description,
    alternates: {
      canonical: localePath(locale, '/blog'),
      languages: { id: '/id/blog', en: '/en/blog', 'x-default': '/id/blog' },
    },
  };
}

export default async function BlogPage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);

  return (
    <>
      <PageHero
        eyebrow={dictionary.blog.eyebrow}
        title={dictionary.blog.title}
        description={dictionary.blog.description}
      >
        <span className="chip chip-accent">
          {portfolio.posts.length} {dictionary.nav.blog}
        </span>
      </PageHero>

      <Section>
        <BlogExplorer posts={portfolio.posts} locale={locale} dictionary={dictionary} />
      </Section>
    </>
  );
}

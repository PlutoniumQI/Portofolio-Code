import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { ProjectExplorer } from '@/components/explorers/ProjectExplorer';
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
    title: dictionary.meta.projects.title,
    description: dictionary.meta.projects.description,
    alternates: {
      canonical: localePath(locale, '/projects'),
      languages: { id: '/id/projects', en: '/en/projects', 'x-default': '/id/projects' },
    },
  };
}

export default async function ProjectsPage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const featured = portfolio.projects.filter((project) => project.is_featured).length;

  return (
    <>
      <PageHero
        eyebrow={dictionary.projects.eyebrow}
        title={dictionary.projects.title}
        description={dictionary.projects.description}
      >
        <div className="flex flex-wrap gap-2">
          <span className="chip chip-accent">
            {portfolio.projects.length} {dictionary.projects.count}
          </span>
          {featured > 0 && (
            <span className="chip">
              {featured} {dictionary.common.featured}
            </span>
          )}
          <span className="chip">{portfolio.project_categories.length} {dictionary.common.categories}</span>
        </div>
      </PageHero>

      <Section>
        <ProjectExplorer
          projects={portfolio.projects}
          categories={portfolio.project_categories}
          locale={locale}
          dictionary={dictionary}
        />
      </Section>
    </>
  );
}

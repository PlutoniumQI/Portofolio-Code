import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { ExperienceExplorer } from '@/components/explorers/ExperienceExplorer';
import { Icon } from '@/components/ui/Icons';
import { PageHero, Section } from '@/components/ui/Section';
import { getPortfolio } from '@/lib/api';
import { getDictionary } from '@/lib/dictionaries';
import { experienceTypeLabel } from '@/lib/dictionaries';
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
    title: dictionary.meta.experience.title,
    description: dictionary.meta.experience.description,
    alternates: {
      canonical: localePath(locale, '/experience'),
      languages: { id: '/id/experience', en: '/en/experience', 'x-default': '/id/experience' },
    },
  };
}

export default async function ExperiencePage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const { experiences } = portfolio;
  const internship = experiences.find((experience) => experience.type === 'internship');

  return (
    <>
      <PageHero
        eyebrow={dictionary.experience.eyebrow}
        title={dictionary.experience.title}
        description={dictionary.experience.description}
      >
        <div className="flex flex-wrap gap-2">
          {Array.from(new Set(experiences.map((experience) => experience.type))).map((type) => (
            <span key={type} className="chip">
              {experienceTypeLabel(dictionary, type)}
            </span>
          ))}
        </div>
      </PageHero>

      {internship && (
        <Section className="pb-0">
          <div className="relative overflow-hidden rounded-3xl border border-accent-line bg-accent-soft/60 p-6 sm:p-8">
            <div className="hero-orb -right-32 -top-32 opacity-60" aria-hidden="true" />
            <div className="relative grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
              <div className="flex items-start gap-4">
                <span className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-accent-line bg-bg/50 text-accent">
                  <Icon name="briefcase" size={22} />
                </span>
                <div>
                  <p className="font-mono text-[10px] uppercase tracking-[0.18em] text-accent">
                    {dictionary.experience.plkTitle}
                  </p>
                  <h2 className="mt-1 text-lg font-semibold text-ink sm:text-xl">
                    {internship.title} · {internship.company}
                  </h2>
                  <p className="mt-1.5 max-w-2xl text-sm leading-relaxed text-muted">
                    {internship.description ?? dictionary.experience.plkDesc}
                  </p>
                  <p className="mt-3 font-mono text-[10px] uppercase tracking-[0.12em] text-faint">
                    {internship.date_range}
                    {internship.location ? ` · ${internship.location}` : ''}
                  </p>
                </div>
              </div>
              <Link
                href={localePath(locale, '/projects')}
                className="btn-ghost shrink-0 justify-self-start lg:justify-self-end"
              >
                {dictionary.common.viewProjects}
                <Icon name="arrow-right" size={14} />
              </Link>
            </div>
          </div>
        </Section>
      )}

      <Section>
        <ExperienceExplorer experiences={experiences} dictionary={dictionary} />
      </Section>
    </>
  );
}

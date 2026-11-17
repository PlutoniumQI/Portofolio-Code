import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { Icon } from '@/components/ui/Icons';
import { PageHero, Section, SectionHeading } from '@/components/ui/Section';
import { skillIcon, skillLevelClass } from '@/components/ui/SkillItem';
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
    title: dictionary.meta.skills.title,
    description: dictionary.meta.skills.description,
    alternates: {
      canonical: localePath(locale, '/skills'),
      languages: { id: '/id/skills', en: '/en/skills', 'x-default': '/id/skills' },
    },
  };
}

export default async function SkillsPage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const { skills } = portfolio;

  return (
    <>
      <PageHero
        eyebrow={dictionary.skills.eyebrow}
        title={dictionary.skills.title}
        description={dictionary.skills.description}
      >
        <div className="flex flex-wrap gap-2">
          <span className="chip chip-accent">
            {skills.hard.length} {dictionary.skills.hardTitle}
          </span>
          <span className="chip">
            {skills.soft.length} {dictionary.skills.softTitle}
          </span>
        </div>
      </PageHero>

      <Section>
        <SectionHeading
          eyebrow={dictionary.skills.hardTitle}
          title={dictionary.skills.hardTitle}
          description={dictionary.skills.hardDesc}
        />

        <div className="mt-9 grid gap-4 sm:grid-cols-2">
          {skills.hard.map((skill, index) => (
            <article
              key={skill.id}
              className={`reveal reveal-delay-${Math.min((index % 4) + 1, 4)} rounded-2xl border border-line bg-elevated/60 p-5 transition-colors hover:border-accent-line sm:p-6`}
            >
              <div className="flex items-start justify-between gap-4">
                <div className="flex items-center gap-3">
                  <span className="flex h-10 w-10 items-center justify-center rounded-xl border border-accent-line bg-accent-soft text-accent">
                    <Icon name={skillIcon(skill.name)} size={19} />
                  </span>
                  <h3 className="text-sm font-semibold text-ink">{skill.name}</h3>
                </div>
                <span
                  className={`shrink-0 rounded-full border px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.12em] ${skillLevelClass(skill.level)}`}
                >
                  {dictionary.skills.levels[skill.level]}
                </span>
              </div>

              {skill.description && (
                <p className="mt-4 text-xs leading-relaxed text-muted">{skill.description}</p>
              )}
            </article>
          ))}
        </div>
      </Section>

      <Section className="border-y border-line bg-elevated/20">
        <SectionHeading
          eyebrow={dictionary.skills.softTitle}
          title={dictionary.skills.softTitle}
          description={dictionary.skills.softDesc}
        />

        <div className="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {skills.soft.map((skill, index) => (
            <article
              key={skill.id}
              className={`reveal reveal-delay-${Math.min((index % 4) + 1, 4)} group rounded-2xl border border-line bg-elevated/60 p-5 transition-all hover:-translate-y-1 hover:border-accent-line`}
            >
              <div className="flex items-center justify-between">
                <span className="flex h-9 w-9 items-center justify-center rounded-full border border-line bg-bg/50 text-accent">
                  <Icon name="sparkles" size={15} />
                </span>
                <span
                  className={`rounded-full border px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.12em] ${skillLevelClass(skill.level)}`}
                >
                  {dictionary.skills.levels[skill.level]}
                </span>
              </div>
              <h3 className="mt-4 text-sm font-semibold text-ink">{skill.name}</h3>
              {skill.description && (
                <p className="mt-3 text-xs leading-relaxed text-muted">{skill.description}</p>
              )}
            </article>
          ))}
        </div>

        <p className="mt-8 flex items-center gap-2 text-[11px] text-faint">
          <Icon name="refresh" size={13} className="text-accent" />
          {dictionary.skills.note}
        </p>
      </Section>

      <Section>
        <div className="flex flex-col items-center justify-between gap-5 rounded-3xl border border-line bg-elevated/60 p-7 sm:flex-row sm:p-9">
          <div>
            <h2 className="text-xl font-semibold tracking-tight text-ink sm:text-2xl">
              {dictionary.home.featured.title}
            </h2>
            <p className="mt-2 max-w-lg text-sm text-muted">{dictionary.projects.description}</p>
          </div>
          <div className="flex shrink-0 flex-wrap gap-3">
            <Link href={localePath(locale, '/projects')} className="btn-primary">
              {dictionary.common.viewProjects}
              <Icon name="arrow-right" size={14} />
            </Link>
            <Link href={localePath(locale, '/contact')} className="btn-ghost">
              {dictionary.common.contactMe}
            </Link>
          </div>
        </div>
      </Section>
    </>
  );
}

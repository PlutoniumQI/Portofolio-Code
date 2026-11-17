import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { Avatar } from '@/components/ui/Avatar';
import { Icon } from '@/components/ui/Icons';
import { Prose } from '@/components/ui/Prose';
import { PageHero, Section, SectionHeading } from '@/components/ui/Section';
import { SkillItem } from '@/components/ui/SkillItem';
import { SocialLinks } from '@/components/ui/SocialLinks';
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
    title: dictionary.meta.about.title,
    description: dictionary.meta.about.description,
    alternates: {
      canonical: localePath(locale, '/about'),
      languages: { id: '/id/about', en: '/en/about', 'x-default': '/id/about' },
    },
  };
}

export default async function AboutPage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const { profile, educations, skills } = portfolio;

  return (
    <>
      <PageHero
        eyebrow={dictionary.about.eyebrow}
        title={dictionary.about.title}
        description={dictionary.about.description}
      >
        <div className="flex flex-wrap items-center gap-2">
          <span className="inline-flex items-center gap-2 rounded-full border border-accent-line bg-accent-soft px-3 py-1.5 text-[11px] text-accent">
            <span className="h-1.5 w-1.5 rounded-full bg-accent" />
            {profile.availability ?? dictionary.common.available}
          </span>
          {profile.location && (
            <span className="inline-flex items-center gap-1.5 rounded-full border border-line px-3 py-1.5 text-[11px] text-muted">
              <Icon name="map-pin" size={12} />
              {profile.location}
            </span>
          )}
        </div>
      </PageHero>

      <Section>
        <div className="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
          <div className="reveal">
            <div className="glass-card relative overflow-hidden rounded-3xl p-6 sm:p-8">
              <div className="hero-orb -left-32 -top-32 opacity-60" aria-hidden="true" />
              <div className="relative flex flex-col items-center text-center">
                <Avatar src={profile.avatar_url} name={profile.full_name} size="xl" />
                <h2 className="mt-5 text-xl font-semibold tracking-tight text-ink">
                  {profile.full_name}
                </h2>
                <p className="mt-1 text-sm text-muted">
                  {profile.headline ?? dictionary.home.roleFallback}
                </p>
                {profile.intro && (
                  <p className="mt-4 text-xs leading-relaxed text-muted">{profile.intro}</p>
                )}
                <SocialLinks socials={profile.socials} className="mt-5 justify-center" />
              </div>

              <dl className="relative mt-7 space-y-1 border-t border-line pt-5 text-xs">
                {profile.email && (
                  <div className="flex items-center justify-between gap-3 py-1.5">
                    <dt className="text-faint">{dictionary.common.email}</dt>
                    <dd>
                      <a href={`mailto:${profile.email}`} className="text-ink-soft hover:text-accent">
                        {profile.email}
                      </a>
                    </dd>
                  </div>
                )}
                {profile.phone && (
                  <div className="flex items-center justify-between gap-3 py-1.5">
                    <dt className="text-faint">{dictionary.common.phone}</dt>
                    <dd className="text-ink-soft">{profile.phone}</dd>
                  </div>
                )}
                {profile.location && (
                  <div className="flex items-center justify-between gap-3 py-1.5">
                    <dt className="text-faint">{dictionary.common.location}</dt>
                    <dd className="text-right text-ink-soft">{profile.location}</dd>
                  </div>
                )}
              </dl>

              <div className="relative mt-6 flex gap-2">
                <Link href={localePath(locale, '/contact')} className="btn-primary flex-1 !px-4 !py-2.5 text-xs">
                  {dictionary.common.contactMe}
                  <Icon name="send" size={13} />
                </Link>
                {profile.cv_url ? (
                  <a
                    href={profile.cv_url}
                    target="_blank"
                    rel="noreferrer noopener"
                    className="btn-ghost !px-4 !py-2.5 text-xs"
                  >
                    <Icon name="download" size={13} />
                    {dictionary.common.downloadCv}
                  </a>
                ) : (
                  <Link href={localePath(locale, '/cv')} className="btn-ghost !px-4 !py-2.5 text-xs">
                    <Icon name="document" size={13} />
                    {dictionary.nav.cv}
                  </Link>
                )}
              </div>
            </div>
          </div>

          <div className="reveal reveal-delay-1">
            <p className="section-eyebrow">{dictionary.about.profileTitle}</p>
            <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink sm:text-3xl">
              {dictionary.home.about.title}
            </h2>

            {profile.about ? (
              <Prose html={profile.about} className="mt-6" />
            ) : (
              <p className="mt-6 text-sm leading-relaxed text-muted">{dictionary.about.description}</p>
            )}

            <div className="mt-8 grid gap-3 sm:grid-cols-2">
              <div className="soft-card rounded-2xl p-4">
                <p className="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">
                  {dictionary.about.focusTitle}
                </p>
                <p className="mt-2 text-sm leading-relaxed text-ink-soft">{dictionary.about.focusDesc}</p>
              </div>
              <div className="soft-card rounded-2xl p-4">
                <p className="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">
                  {dictionary.about.availability}
                </p>
                <p className="mt-2 text-sm leading-relaxed text-ink-soft">{dictionary.about.openToWork}</p>
              </div>
            </div>

            <div className="mt-8 grid grid-cols-3 gap-3">
              {[
                { value: profile.stats.years_learning, label: dictionary.home.stats.years },
                { value: profile.stats.projects_completed, label: dictionary.home.stats.projects },
                { value: profile.stats.tools_mastered, label: dictionary.home.stats.tools },
              ].map((stat) => (
                <div key={stat.label} className="rounded-2xl border border-line bg-elevated/50 p-4 text-center">
                  <p className="font-mono text-2xl font-semibold text-accent">{stat.value}</p>
                  <p className="mt-1 text-[10px] uppercase tracking-[0.1em] text-faint">{stat.label}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </Section>

      <Section className="border-y border-line bg-elevated/20">
        <SectionHeading
          eyebrow={dictionary.about.educationTitle}
          title={dictionary.about.educationTitle}
          description={dictionary.about.educationDesc}
        />

        <div className="mt-9 grid gap-4 lg:grid-cols-2">
          {educations.map((education, index) => {
            const end =
              !education.end_date || education.end_date.toLowerCase() === 'now'
                ? dictionary.common.present
                : education.end_date;

            return (
              <article
                key={education.id}
                className={`reveal reveal-delay-${Math.min(index + 1, 4)} relative overflow-hidden rounded-2xl border border-line bg-elevated/60 p-5 sm:p-6`}
              >
                <div className="flex items-start gap-4">
                  <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-accent-line bg-accent-soft text-accent">
                    <Icon name="academic-cap" size={20} />
                  </span>
                  <div className="min-w-0">
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="font-mono text-[10px] uppercase tracking-[0.12em] text-accent">
                        {education.start_date} — {end}
                      </span>
                    </div>
                    <h3 className="mt-1.5 text-base font-semibold text-ink">{education.institution}</h3>
                    <p className="mt-0.5 text-sm text-muted">
                      {[education.degree, education.field].filter(Boolean).join(' · ')}
                    </p>
                    {education.description && (
                      <p className="mt-2 text-xs leading-relaxed text-faint">{education.description}</p>
                    )}
                  </div>
                </div>
              </article>
            );
          })}
        </div>
      </Section>

      <Section>
        <div className="grid gap-10 lg:grid-cols-[1fr_1fr] lg:gap-16">
          <div className="reveal">
            <SectionHeading
              eyebrow={dictionary.about.softSkillsTitle}
              title={dictionary.about.softSkillsTitle}
              description={dictionary.about.softSkillsDesc}
            />
            <div className="mt-7 grid gap-x-8 gap-y-5 sm:grid-cols-2">
              {skills.soft.map((skill) => (
                <SkillItem
                  key={skill.id}
                  skill={skill}
                  levelLabel={dictionary.skills.levels[skill.level]}
                  compact
                />
              ))}
            </div>
          </div>

          <div className="reveal reveal-delay-1">
            <p className="section-eyebrow">{dictionary.about.languagesTitle}</p>
            <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink">
              {dictionary.about.languagesTitle}
            </h2>

            <div className="mt-7 space-y-3">
              <div className="soft-card flex items-center justify-between rounded-2xl px-4 py-4">
                <div className="flex items-center gap-3">
                  <span className="flex h-9 w-9 items-center justify-center rounded-full border border-line bg-bg/60 font-mono text-[10px] text-accent">
                    ID
                  </span>
                  <span className="text-sm font-medium text-ink">{dictionary.about.indonesian}</span>
                </div>
                <span className="chip chip-accent">{dictionary.about.native}</span>
              </div>
              <div className="soft-card flex items-center justify-between rounded-2xl px-4 py-4">
                <div className="flex items-center gap-3">
                  <span className="flex h-9 w-9 items-center justify-center rounded-full border border-line bg-bg/60 font-mono text-[10px] text-accent">
                    EN
                  </span>
                  <span className="text-sm font-medium text-ink">{dictionary.about.english}</span>
                </div>
                <span className="chip">{dictionary.about.intermediate}</span>
              </div>
            </div>

            <div className="mt-6 rounded-2xl border border-accent-line bg-accent-soft p-4 text-xs leading-relaxed text-ink-soft">
              {dictionary.about.openToWork}
            </div>

            <div className="mt-6 flex flex-wrap gap-3">
              <Link href={localePath(locale, '/cv')} className="btn-ghost">
                {dictionary.nav.cv}
                <Icon name="document" size={14} />
              </Link>
              <Link href={localePath(locale, '/contact')} className="btn-primary">
                {dictionary.common.contactMe}
                <Icon name="send" size={14} />
              </Link>
            </div>
          </div>
        </div>
      </Section>
    </>
  );
}

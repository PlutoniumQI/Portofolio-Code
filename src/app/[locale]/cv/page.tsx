import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { PrintButton } from '@/components/site/PrintButton';
import { Avatar } from '@/components/ui/Avatar';
import { Icon } from '@/components/ui/Icons';
import { Container } from '@/components/ui/Section';
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
    title: dictionary.meta.cv.title,
    description: dictionary.meta.cv.description,
    alternates: {
      canonical: localePath(locale, '/cv'),
      languages: { id: '/id/cv', en: '/en/cv', 'x-default': '/id/cv' },
    },
  };
}

export default async function CvPage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const { profile, experiences, educations, skills, certificates } = portfolio;

  return (
    <div className="print-page">
      <Container className="max-w-4xl py-10 sm:py-14">
        <div className="no-print mb-6 flex items-center justify-between gap-4">
          <Link
            href={localePath(locale, '/about')}
            className="inline-flex items-center gap-2 text-xs text-muted transition-colors hover:text-accent"
          >
            <Icon name="arrow-left" size={14} />
            {dictionary.nav.about}
          </Link>
          <span className="font-mono text-[10px] uppercase tracking-[0.16em] text-faint">
            {dictionary.cv.eyebrow}
          </span>
        </div>

        <header className="print-card rounded-2xl border border-line bg-elevated/60 p-6 sm:p-8">
          <div className="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
            <div className="flex items-start gap-5">
              <Avatar src={profile.avatar_url} name={profile.full_name} size="lg" />
              <div>
                <p className="font-mono text-[10px] uppercase tracking-[0.2em] text-accent">
                  {dictionary.cv.title}
                </p>
                <h1 className="mt-1.5 text-2xl font-semibold tracking-tight text-ink sm:text-3xl">
                  {profile.full_name}
                </h1>
                <p className="mt-1 text-sm text-muted">
                  {profile.headline ?? dictionary.home.roleFallback}
                </p>
                <div className="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-[11px] text-muted">
                  {profile.email && (
                    <a href={`mailto:${profile.email}`} className="inline-flex items-center gap-1.5 hover:text-accent">
                      <Icon name="mail" size={12} className="text-accent" />
                      {profile.email}
                    </a>
                  )}
                  {profile.phone && (
                    <span className="inline-flex items-center gap-1.5">
                      <Icon name="phone" size={12} className="text-accent" />
                      {profile.phone}
                    </span>
                  )}
                  {profile.location && (
                    <span className="inline-flex items-center gap-1.5">
                      <Icon name="map-pin" size={12} className="text-accent" />
                      {profile.location}
                    </span>
                  )}
                </div>
              </div>
            </div>

            <div className="no-print flex shrink-0 flex-wrap gap-2">
              <PrintButton label={dictionary.cv.print} />
              {profile.cv_url && (
                <a
                  href={`/api/cv?locale=${locale}`}
                  download
                  className="btn-ghost"
                >
                  <Icon name="download" size={14} />
                  {dictionary.cv.download}
                </a>
              )}
            </div>
          </div>

          {Object.values(profile.socials).some(Boolean) && (
            <div className="mt-5 flex flex-wrap gap-x-4 gap-y-1 border-t border-line pt-4 text-[11px] text-faint">
              {Object.entries(profile.socials)
                .filter(([, url]) => Boolean(url))
                .map(([key, url]) => (
                  <a key={key} href={url} target="_blank" rel="noreferrer noopener" className="hover:text-accent">
                    {key}: {url}
                  </a>
                ))}
            </div>
          )}
        </header>

        <div className="mt-8 grid gap-8 md:grid-cols-[1.55fr_1fr] md:gap-10">
          <div className="space-y-8">
            <section className="print-card">
              <h2 className="flex items-center gap-2 border-b border-line pb-2 font-mono text-[10px] uppercase tracking-[0.2em] text-accent">
                <Icon name="user" size={13} />
                {dictionary.cv.summary}
              </h2>
              <p className="mt-3 text-sm leading-relaxed text-muted">
                {profile.intro ?? dictionary.about.description}
              </p>
            </section>

            <section className="print-card">
              <h2 className="flex items-center gap-2 border-b border-line pb-2 font-mono text-[10px] uppercase tracking-[0.2em] text-accent">
                <Icon name="briefcase" size={13} />
                {dictionary.cv.experience}
              </h2>
              <div className="mt-4 space-y-4">
                {experiences.map((experience) => (
                  <div key={experience.id} className="break-inside-avoid">
                    <div className="flex flex-wrap items-baseline justify-between gap-2">
                      <h3 className="text-sm font-semibold text-ink">{experience.title}</h3>
                      <span className="font-mono text-[10px] text-faint">{experience.date_range}</span>
                    </div>
                    <p className="text-xs text-muted">
                      {experience.company}
                      {experience.location ? ` · ${experience.location}` : ''}
                    </p>
                    {experience.description && (
                      <p className="mt-1 text-xs leading-relaxed text-faint">{experience.description}</p>
                    )}
                  </div>
                ))}
              </div>
            </section>

            <section className="print-card">
              <h2 className="flex items-center gap-2 border-b border-line pb-2 font-mono text-[10px] uppercase tracking-[0.2em] text-accent">
                <Icon name="academic-cap" size={13} />
                {dictionary.cv.education}
              </h2>
              <div className="mt-4 space-y-4">
                {educations.map((education) => {
                  const end =
                    !education.end_date || education.end_date.toLowerCase() === 'now'
                      ? dictionary.cv.present
                      : education.end_date;

                  return (
                    <div key={education.id} className="break-inside-avoid">
                      <div className="flex flex-wrap items-baseline justify-between gap-2">
                        <h3 className="text-sm font-semibold text-ink">{education.institution}</h3>
                        <span className="font-mono text-[10px] text-faint">
                          {education.start_date} — {end}
                        </span>
                      </div>
                      <p className="text-xs text-muted">
                        {[education.degree, education.field].filter(Boolean).join(' · ')}
                      </p>
                    </div>
                  );
                })}
              </div>
            </section>
          </div>

          <div className="space-y-8">
            <section className="print-card">
              <h2 className="flex items-center gap-2 border-b border-line pb-2 font-mono text-[10px] uppercase tracking-[0.2em] text-accent">
                <Icon name="sparkles" size={13} />
                {dictionary.cv.skills}
              </h2>
              <div className="mt-4 space-y-3">
                {skills.hard.map((skill) => (
                  <div key={skill.id} className="flex items-baseline justify-between gap-3 text-xs">
                    <span className="text-ink-soft">{skill.name}</span>
                    <span className="font-mono text-[9px] uppercase tracking-[0.12em] text-faint">
                      {dictionary.skills.levels[skill.level]}
                    </span>
                  </div>
                ))}
                <div className="flex flex-wrap gap-1.5 pt-2">
                  {skills.soft.map((skill) => (
                    <span key={skill.id} className="chip">
                      {skill.name}
                    </span>
                  ))}
                </div>
              </div>
            </section>

            <section className="print-card">
              <h2 className="flex items-center gap-2 border-b border-line pb-2 font-mono text-[10px] uppercase tracking-[0.2em] text-accent">
                <Icon name="trophy" size={13} />
                {dictionary.cv.certificates}
              </h2>
              <div className="mt-4 space-y-3">
                {certificates.map((certificate) => (
                  <div key={certificate.id} className="break-inside-avoid">
                    <p className="text-xs font-semibold leading-snug text-ink">{certificate.title}</p>
                    <p className="text-[11px] text-muted">
                      {certificate.issuer}
                      {certificate.issued_at ? ` · ${certificate.issued_at}` : ''}
                    </p>
                  </div>
                ))}
              </div>
            </section>

            <section className="print-card">
              <h2 className="flex items-center gap-2 border-b border-line pb-2 font-mono text-[10px] uppercase tracking-[0.2em] text-accent">
                <Icon name="globe" size={13} />
                {dictionary.cv.languages}
              </h2>
              <div className="mt-3 space-y-2 text-xs">
                <div className="flex items-center justify-between">
                  <span className="text-ink-soft">{dictionary.about.indonesian}</span>
                  <span className="text-faint">{dictionary.about.native}</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-ink-soft">{dictionary.about.english}</span>
                  <span className="text-faint">{dictionary.about.intermediate}</span>
                </div>
              </div>
            </section>
          </div>
        </div>

        <p className="no-print mt-8 flex items-start gap-2 text-[11px] leading-relaxed text-faint">
          <Icon name="printer" size={13} className="mt-0.5 shrink-0 text-accent" />
          {dictionary.cv.note}
        </p>
      </Container>
    </div>
  );
}

import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { Counter } from '@/components/site/Counter';
import { Typewriter } from '@/components/site/Typewriter';
import { Avatar } from '@/components/ui/Avatar';
import { ExperienceItem } from '@/components/ui/ExperienceItem';
import { Icon } from '@/components/ui/Icons';
import { JsonLd } from '@/components/ui/JsonLd';
import { PostCard } from '@/components/ui/PostCard';
import { ProjectCard } from '@/components/ui/ProjectCard';
import { Container, Section, SectionHeading } from '@/components/ui/Section';
import { SkillBar } from '@/components/ui/SkillBar';
import { SocialLinks } from '@/components/ui/SocialLinks';
import { TestimonialCard } from '@/components/ui/TestimonialCard';
import { getPortfolio, getSiteUrl } from '@/lib/api';
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
  const portfolio = await getPortfolio(locale);
  const canonical = localePath(locale);
  const image = portfolio.profile.avatar_url ?? undefined;

  return {
    title: { absolute: dictionary.meta.home.title },
    description: dictionary.meta.home.description,
    alternates: {
      canonical,
      languages: { id: '/id', en: '/en', 'x-default': '/id' },
    },
    openGraph: {
      type: 'website',
      url: canonical,
      title: dictionary.meta.home.title,
      description: dictionary.meta.home.description,
      locale: locale === 'id' ? 'id_ID' : 'en_GB',
      images: image ? [{ url: image }] : undefined,
    },
  };
}

export default async function HomePage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const { profile, skills, projects, experiences, testimonials, posts, certificates } = portfolio;

  const nameParts = profile.full_name.trim().split(/\s+/);
  const surname = nameParts.length > 1 ? nameParts.pop() : '';
  const givenName = nameParts.join(' ');
  const headline = profile.headline ?? dictionary.home.roleFallback;

  const stats = [
    { value: profile.stats.years_learning, label: dictionary.home.stats.years, suffix: '+' },
    { value: profile.stats.projects_completed, label: dictionary.home.stats.projects, suffix: '' },
    { value: profile.stats.tools_mastered, label: dictionary.home.stats.tools, suffix: '+' },
    { value: certificates.length, label: dictionary.home.stats.certificates, suffix: '' },
  ];

  return (
    <>
      <JsonLd
        data={{
          '@context': 'https://schema.org',
          '@type': 'Person',
          name: profile.full_name,
          jobTitle: headline,
          description: profile.intro ?? dictionary.meta.home.description,
          url: `${getSiteUrl()}${localePath(locale)}`,
          email: profile.email ?? undefined,
          address: profile.location ?? undefined,
          sameAs: Object.values(profile.socials).filter(Boolean),
          knowsAbout: skills.hard.map((skill) => skill.name),
        }}
      />

      {/* ── HERO ───────────────────────────────────────────────────────── */}
      <section className="relative overflow-hidden">
        <div className="hero-orb left-[62%] top-[18%] opacity-90" aria-hidden="true" />
        <Container className="relative grid min-h-[calc(100dvh-72px)] items-center gap-14 py-14 lg:grid-cols-[1.12fr_0.88fr] lg:gap-10 lg:py-20">
          <div>
            <div className="reveal flex flex-wrap items-center gap-3">
              <span className="inline-flex items-center gap-2 rounded-full border border-line bg-elevated/60 px-3 py-1.5 text-[11px] text-muted">
                <span className="relative flex h-1.5 w-1.5">
                  {profile.is_available && (
                    <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-60" />
                  )}
                  <span
                    className={`relative inline-flex h-1.5 w-1.5 rounded-full ${
                      profile.is_available ? 'bg-accent' : 'bg-faint'
                    }`}
                  />
                </span>
                {profile.availability ?? dictionary.common.available}
              </span>
              <span className="font-mono text-[10px] uppercase tracking-[0.18em] text-faint">
                {dictionary.home.eyebrow}
              </span>
            </div>

            <p className="reveal reveal-delay-1 mt-8 font-mono text-sm text-muted">
              {dictionary.home.greeting}
            </p>

            <h1 className="reveal reveal-delay-1 mt-2 text-[2.6rem] font-semibold leading-[1.02] tracking-[-0.03em] text-ink sm:text-6xl lg:text-[4.4rem]">
              {givenName} <span className="text-accent">{surname}</span>
            </h1>

            <p className="reveal reveal-delay-2 mt-4 font-mono text-sm text-muted sm:text-base">
              <span className="text-faint">&gt; </span>
              <Typewriter words={dictionary.home.roles} />
            </p>

            <p className="reveal reveal-delay-2 mt-6 max-w-xl text-sm leading-relaxed text-muted sm:text-base">
              {profile.intro ?? dictionary.home.about.description}
            </p>

            <div className="reveal reveal-delay-3 mt-8 flex flex-wrap items-center gap-3">
              <Link href={localePath(locale, '/projects')} className="btn-primary">
                {dictionary.home.heroCta}
                <Icon name="arrow-right" size={15} />
              </Link>
              <Link href={localePath(locale, '/about')} className="btn-ghost">
                {dictionary.home.heroSecondary}
                <Icon name="arrow-up-right" size={14} />
              </Link>
            </div>

            <div className="reveal reveal-delay-3 mt-8 flex flex-wrap items-center gap-4">
              <SocialLinks socials={profile.socials} />
              {profile.email && (
                <a
                  href={`mailto:${profile.email}`}
                  className="font-mono text-[11px] text-faint transition-colors hover:text-accent"
                >
                  {profile.email}
                </a>
              )}
            </div>
          </div>

          <div className="reveal reveal-delay-2 relative mx-auto w-full max-w-[330px] sm:max-w-[360px]">
            <div
              className="absolute inset-x-8 top-8 hidden h-[88%] rounded-[2.5rem] border border-accent-line/40 sm:block"
              aria-hidden="true"
            />
            <div className="relative rounded-[2.2rem] border border-line bg-elevated/60 p-3 shadow-2xl shadow-black/10 backdrop-blur-xl dark:shadow-black/40">
              <div className="project-art relative overflow-hidden rounded-[1.7rem] border border-line px-6 py-6">
                <div className="art-grid absolute inset-0 opacity-60" aria-hidden="true" />
                <div className="relative flex items-center justify-between font-mono text-[9px] uppercase tracking-[0.2em] text-faint">
                  <span>portfolio / 26</span>
                  <span className="inline-flex items-center gap-1.5 text-accent">
                    <span className="h-1.5 w-1.5 rounded-full bg-accent" />
                    online
                  </span>
                </div>

                <div className="relative mt-9 flex justify-center">
                  <div className="relative">
                    <span
                      className="absolute -inset-3 rounded-full border border-accent-line/50"
                      aria-hidden="true"
                    />
                    <span
                      className="absolute -inset-6 rounded-full border border-line"
                      aria-hidden="true"
                    />
                    <Avatar src={profile.avatar_url} name={profile.full_name} size="xl" />
                  </div>
                </div>

                <div className="relative mt-9 text-center">
                  <p className="font-mono text-[9px] uppercase tracking-[0.2em] text-accent">
                    {dictionary.home.eyebrow}
                  </p>
                  <p className="mt-2 text-lg font-semibold tracking-tight text-ink">
                    {profile.full_name}
                  </p>
                  <p className="mt-1 text-xs text-muted">{headline}</p>
                </div>

                <div className="relative mt-6 flex items-center justify-between border-t border-line pt-4 text-[10px] text-faint">
                  <span className="inline-flex items-center gap-1.5">
                    <Icon name="map-pin" size={12} className="text-accent" />
                    {profile.location ?? 'Indonesia'}
                  </span>
                  <span className="font-mono uppercase tracking-[0.16em]">
                    {locale === 'id' ? 'Indonesia' : 'English'}
                  </span>
                </div>
              </div>
            </div>

            <span className="fl absolute -left-4 top-20 hidden rounded-full border border-line bg-elevated/90 px-3 py-1.5 font-mono text-[10px] text-ink shadow-lg backdrop-blur sm:inline-flex">
              <Icon name="figma" size={14} className="mr-1.5 text-accent" />
              Figma
            </span>
            <span className="fl2 absolute -right-4 top-1/2 hidden rounded-full border border-line bg-elevated/90 px-3 py-1.5 font-mono text-[10px] text-ink shadow-lg backdrop-blur sm:inline-flex">
              <Icon name="code" size={14} className="mr-1.5 text-accent" />
              Next.js
            </span>
            <span className="fl3 absolute -left-2 bottom-14 hidden rounded-full border border-line bg-elevated/90 px-3 py-1.5 font-mono text-[10px] text-ink shadow-lg backdrop-blur sm:inline-flex">
              <Icon name="layers" size={14} className="mr-1.5 text-accent" />
              Laravel
            </span>
          </div>

          <a
            href="#stats"
            className="scroll-indicator absolute bottom-5 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 font-mono text-[9px] uppercase tracking-[0.2em] text-faint lg:flex"
          >
            {dictionary.home.scroll}
            <span className="flex h-8 w-5 items-start justify-center rounded-full border border-line-strong p-1">
              <span className="h-1.5 w-1 rounded-full bg-accent" />
            </span>
          </a>
        </Container>
      </section>

      {/* ── MARQUEE ────────────────────────────────────────────────────── */}
      <div className="marquee-mask overflow-hidden border-y border-line bg-elevated/30 py-3.5">
        <div className="mq flex items-center gap-8">
          {[...dictionary.home.marquee, ...dictionary.home.marquee, ...dictionary.home.marquee, ...dictionary.home.marquee].map(
            (item, index) => (
              <span
                key={`${item}-${index}`}
                className="inline-flex items-center gap-8 font-mono text-[10px] uppercase tracking-[0.24em] text-muted"
              >
                {item}
                <span className="text-accent" aria-hidden="true">
                  ✦
                </span>
              </span>
            ),
          )}
        </div>
      </div>

      {/* ── STATS ──────────────────────────────────────────────────────── */}
      <section id="stats" className="scroll-mt-24 py-10 sm:py-12">
        <Container>
          <div className="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
            {stats.map((stat, index) => (
              <div
                key={stat.label}
                className={`reveal reveal-delay-${index + 1} rounded-2xl border border-line bg-elevated/50 p-5 text-center sm:p-6`}
              >
                <p className="font-mono text-3xl font-semibold tracking-tight text-accent sm:text-4xl">
                  <Counter value={stat.value} suffix={stat.suffix} />
                </p>
                <p className="mt-2 text-[11px] uppercase tracking-[0.12em] text-faint">{stat.label}</p>
              </div>
            ))}
          </div>
        </Container>
      </section>

      {/* ── ABOUT PREVIEW ──────────────────────────────────────────────── */}
      <Section className="border-t border-line">
        <div className="grid gap-10 lg:grid-cols-[0.92fr_1.08fr] lg:gap-16">
          <div className="reveal">
            <div className="glass-card relative overflow-hidden rounded-3xl p-6 sm:p-8">
              <div className="hero-orb -right-32 -top-32 opacity-70" aria-hidden="true" />
              <div className="relative flex items-center justify-between">
                <span className="section-eyebrow">{dictionary.about.eyebrow}</span>
                <span className="font-mono text-[10px] text-faint">01 / 04</span>
              </div>
              <p className="relative mt-8 text-xl font-medium leading-snug text-ink sm:text-2xl">
                “{profile.intro ?? dictionary.home.about.description}”
              </p>
              <div className="relative mt-8 grid gap-2 sm:grid-cols-2">
                {profile.location && (
                  <div className="soft-card flex items-center gap-2 rounded-xl px-3 py-2.5 text-xs text-muted">
                    <Icon name="map-pin" size={14} className="text-accent" />
                    {profile.location}
                  </div>
                )}
                {profile.email && (
                  <a
                    href={`mailto:${profile.email}`}
                    className="soft-card flex items-center gap-2 rounded-xl px-3 py-2.5 text-xs text-muted transition-colors hover:text-accent"
                  >
                    <Icon name="mail" size={14} className="text-accent" />
                    <span className="truncate">{profile.email}</span>
                  </a>
                )}
              </div>
              <div className="relative mt-5 flex flex-wrap gap-1.5">
                {[dictionary.about.focusDesc, dictionary.about.availability].map((item) => (
                  <span key={item} className="chip">
                    {item}
                  </span>
                ))}
              </div>
            </div>
          </div>

          <div className="reveal reveal-delay-1 flex flex-col justify-center">
            <p className="section-eyebrow">{dictionary.home.about.eyebrow}</p>
            <h2 className="mt-3 text-2xl font-semibold leading-tight tracking-tight text-ink sm:text-3xl lg:text-[2.35rem]">
              {dictionary.home.about.title}
            </h2>
            <p className="mt-4 text-sm leading-relaxed text-muted sm:text-base">
              {dictionary.about.description}
            </p>

            <ul className="mt-6 space-y-3">
              {[dictionary.about.focusDesc, dictionary.about.openToWork, headline].map((item) => (
                <li key={item} className="flex items-start gap-2.5 text-sm text-ink-soft">
                  <span className="mt-1.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-accent-soft text-accent">
                    <Icon name="check" size={10} />
                  </span>
                  {item}
                </li>
              ))}
            </ul>

            <div className="mt-7">
              <Link href={localePath(locale, '/about')} className="btn-ghost">
                {dictionary.common.readMore}
                <Icon name="arrow-right" size={14} />
              </Link>
            </div>
          </div>
        </div>
      </Section>

      {/* ── FEATURED PROJECTS ─────────────────────────────────────────── */}
      <Section className="border-t border-line bg-elevated/20">
        <SectionHeading
          eyebrow={dictionary.home.featured.eyebrow}
          title={dictionary.home.featured.title}
          description={dictionary.home.featured.description}
          action={
            <Link href={localePath(locale, '/projects')} className="btn-ghost">
              {dictionary.common.viewAll}
              <Icon name="arrow-right" size={14} />
            </Link>
          }
        />

        <div className="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {projects.slice(0, 3).map((project, index) => (
            <div key={project.id} className={`reveal reveal-delay-${Math.min(index + 1, 4)}`}>
              <ProjectCard
                project={project}
                locale={locale}
                dictionary={dictionary}
                index={index}
              />
            </div>
          ))}
        </div>
      </Section>

      {/* ── SKILLS PREVIEW ────────────────────────────────────────────── */}
      <Section className="border-y border-line">
        <div className="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
          <div className="reveal">
            <p className="section-eyebrow">{dictionary.home.skills.eyebrow}</p>
            <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink sm:text-3xl">
              {dictionary.home.skills.title}
            </h2>
            <p className="mt-3 text-sm leading-relaxed text-muted">
              {dictionary.home.skills.description}
            </p>

            <div className="mt-6 flex flex-wrap gap-1.5">
              {skills.soft.slice(0, 5).map((skill) => (
                <span key={skill.id} className="chip">
                  {skill.name}
                </span>
              ))}
            </div>

            <div className="mt-7">
              <Link href={localePath(locale, '/skills')} className="btn-ghost">
                {dictionary.common.viewAll}
                <Icon name="arrow-right" size={14} />
              </Link>
            </div>
          </div>

          <div className="reveal reveal-delay-1 grid gap-x-10 gap-y-6 sm:grid-cols-2">
            {skills.hard.slice(0, 6).map((skill) => (
              <SkillBar key={skill.id} skill={skill} compact />
            ))}
          </div>
        </div>
      </Section>

      {/* ── EXPERIENCE PREVIEW ────────────────────────────────────────── */}
      <Section>
        <SectionHeading
          eyebrow={dictionary.home.experience.eyebrow}
          title={dictionary.home.experience.title}
          description={dictionary.home.experience.description}
          action={
            <Link href={localePath(locale, '/experience')} className="btn-ghost">
              {dictionary.common.viewAll}
              <Icon name="arrow-right" size={14} />
            </Link>
          }
        />

        <div className="mt-9">
          {experiences.slice(0, 4).map((experience) => (
            <ExperienceItem key={experience.id} experience={experience} dictionary={dictionary} />
          ))}
        </div>
      </Section>

      {/* ── TESTIMONIALS ──────────────────────────────────────────────── */}
      <Section className="border-y border-line bg-elevated/20">
        <SectionHeading
          eyebrow={dictionary.home.testimonials.eyebrow}
          title={dictionary.home.testimonials.title}
          description={dictionary.home.testimonials.description}
          action={
            <Link href={localePath(locale, '/testimonials')} className="btn-ghost">
              {dictionary.common.viewAll}
              <Icon name="arrow-right" size={14} />
            </Link>
          }
        />

        <div className="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          {testimonials.slice(0, 4).map((testimonial, index) => (
            <div key={testimonial.id} className={`reveal reveal-delay-${Math.min(index + 1, 4)}`}>
              <TestimonialCard testimonial={testimonial} dictionary={dictionary} />
            </div>
          ))}
        </div>
      </Section>

      {/* ── BLOG PREVIEW ──────────────────────────────────────────────── */}
      {posts.length > 0 && (
        <Section>
          <SectionHeading
            eyebrow={dictionary.home.blog.eyebrow}
            title={dictionary.home.blog.title}
            description={dictionary.home.blog.description}
            action={
              <Link href={localePath(locale, '/blog')} className="btn-ghost">
                {dictionary.common.viewAll}
                <Icon name="arrow-right" size={14} />
              </Link>
            }
          />

          <div className="mt-9 grid gap-5 sm:grid-cols-2">
            {posts.slice(0, 2).map((post, index) => (
              <div key={post.id} className={`reveal reveal-delay-${index + 1}`}>
                <PostCard post={post} locale={locale} dictionary={dictionary} />
              </div>
            ))}
          </div>
        </Section>
      )}

      {/* ── CONTACT CTA ───────────────────────────────────────────────── */}
      <Section className="pb-4">
        <div className="relative overflow-hidden rounded-3xl border border-line bg-elevated/60 p-7 sm:p-10 lg:p-12">
          <div className="hero-orb -right-44 -top-44 opacity-80" aria-hidden="true" />
          <div className="relative grid gap-9 lg:grid-cols-[1.25fr_0.75fr] lg:items-center">
            <div>
              <p className="section-eyebrow">{dictionary.home.contact.eyebrow}</p>
              <h2 className="mt-3 text-2xl font-semibold leading-tight tracking-tight text-ink sm:text-3xl lg:text-4xl">
                {dictionary.home.contact.title}
              </h2>
              <p className="mt-3 max-w-xl text-sm leading-relaxed text-muted">
                {dictionary.home.contact.description}
              </p>
              <div className="mt-6 flex flex-wrap gap-3">
                <Link href={localePath(locale, '/contact')} className="btn-primary">
                  {dictionary.common.contactMe}
                  <Icon name="send" size={14} />
                </Link>
                <Link href={localePath(locale, '/guestbook')} className="btn-ghost">
                  {dictionary.guestbook.title}
                  <Icon name="message" size={14} />
                </Link>
              </div>
            </div>

            <div className="soft-card rounded-2xl p-5">
              <div className="flex items-center gap-2 text-[11px] text-muted">
                <span className={`h-1.5 w-1.5 rounded-full ${profile.is_available ? 'bg-accent' : 'bg-faint'}`} />
                {profile.availability ?? dictionary.common.available}
              </div>
              <div className="mt-4 space-y-3 text-sm">
                {profile.email && (
                  <a
                    href={`mailto:${profile.email}`}
                    className="flex items-center gap-2.5 text-ink-soft transition-colors hover:text-accent"
                  >
                    <Icon name="mail" size={15} className="text-accent" />
                    <span className="truncate">{profile.email}</span>
                  </a>
                )}
                {profile.location && (
                  <p className="flex items-center gap-2.5 text-ink-soft">
                    <Icon name="map-pin" size={15} className="text-accent" />
                    {profile.location}
                  </p>
                )}
              </div>
              <div className="mt-5 border-t border-line pt-4">
                <SocialLinks socials={profile.socials} />
              </div>
            </div>
          </div>
        </div>
      </Section>
    </>
  );
}

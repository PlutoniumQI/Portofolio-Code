import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { ContactForm } from '@/components/forms/ContactForm';
import { Icon } from '@/components/ui/Icons';
import { PageHero, Section } from '@/components/ui/Section';
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
    title: dictionary.meta.contact.title,
    description: dictionary.meta.contact.description,
    alternates: {
      canonical: localePath(locale, '/contact'),
      languages: { id: '/id/contact', en: '/en/contact', 'x-default': '/id/contact' },
    },
  };
}

export default async function ContactPage({ params }: { params: Promise<{ locale: string }> }) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const { profile } = portfolio;

  return (
    <>
      <PageHero
        eyebrow={dictionary.contact.eyebrow}
        title={dictionary.contact.title}
        description={dictionary.contact.description}
      />

      <Section>
        <div className="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:gap-12">
          <div className="space-y-4">
            <div className="glass-card relative overflow-hidden rounded-3xl p-6 sm:p-7">
              <div className="hero-orb -left-32 -top-32 opacity-50" aria-hidden="true" />
              <div className="relative">
                <p className="section-eyebrow">{dictionary.home.contact.eyebrow}</p>
                <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink">
                  {dictionary.common.getInTouch}
                </h2>
                <p className="mt-2 text-sm leading-relaxed text-muted">
                  {dictionary.home.contact.description}
                </p>

                <div className="mt-6 space-y-2.5">
                  {profile.email && (
                    <a
                      href={`mailto:${profile.email}`}
                      className="soft-card flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm text-ink-soft transition-colors hover:border-accent-line hover:text-accent"
                    >
                      <span className="flex h-8 w-8 items-center justify-center rounded-full border border-line bg-bg/50 text-accent">
                        <Icon name="mail" size={14} />
                      </span>
                      <span className="min-w-0">
                        <span className="block font-mono text-[9px] uppercase tracking-[0.16em] text-faint">
                          {dictionary.common.email}
                        </span>
                        <span className="block truncate">{profile.email}</span>
                      </span>
                    </a>
                  )}

                  {profile.phone && (
                    <a
                      href={`tel:${profile.phone.replace(/\s+/g, '')}`}
                      className="soft-card flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm text-ink-soft transition-colors hover:border-accent-line hover:text-accent"
                    >
                      <span className="flex h-8 w-8 items-center justify-center rounded-full border border-line bg-bg/50 text-accent">
                        <Icon name="phone" size={14} />
                      </span>
                      <span>
                        <span className="block font-mono text-[9px] uppercase tracking-[0.16em] text-faint">
                          {dictionary.common.phone}
                        </span>
                        <span className="block">{profile.phone}</span>
                      </span>
                    </a>
                  )}

                  {profile.location && (
                    <div className="soft-card flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm text-ink-soft">
                      <span className="flex h-8 w-8 items-center justify-center rounded-full border border-line bg-bg/50 text-accent">
                        <Icon name="map-pin" size={14} />
                      </span>
                      <span>
                        <span className="block font-mono text-[9px] uppercase tracking-[0.16em] text-faint">
                          {dictionary.contact.basedIn}
                        </span>
                        <span className="block">{profile.location}</span>
                      </span>
                    </div>
                  )}
                </div>

                <div className="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-5">
                  <SocialLinks socials={profile.socials} />
                  <span className="inline-flex items-center gap-1.5 text-[11px] text-faint">
                    <Icon name="clock" size={12} className="text-accent" />
                    {dictionary.contact.responseTime}
                  </span>
                </div>
              </div>
            </div>

            <div className="rounded-2xl border border-line bg-elevated/50 p-5">
              <p className="text-sm text-ink-soft">{dictionary.contact.testimonialCta}</p>
              <Link
                href={`${localePath(locale, '/testimonials')}#write`}
                className="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-accent transition-opacity hover:opacity-75"
              >
                {dictionary.contact.testimonialLink}
                <Icon name="arrow-right" size={13} />
              </Link>
            </div>
          </div>

          <div>
            <p className="section-eyebrow">{dictionary.contact.eyebrow}</p>
            <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink">
              {dictionary.contact.formTitle}
            </h2>
            <p className="mt-2 text-sm leading-relaxed text-muted">{dictionary.contact.formDesc}</p>

            <div className="mt-5">
              <ContactForm dictionary={dictionary} />
            </div>
          </div>
        </div>
      </Section>
    </>
  );
}

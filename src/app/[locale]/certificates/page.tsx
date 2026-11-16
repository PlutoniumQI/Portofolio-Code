import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { CertificateGallery } from '@/components/explorers/CertificateGallery';
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
    title: dictionary.meta.certificates.title,
    description: dictionary.meta.certificates.description,
    alternates: {
      canonical: localePath(locale, '/certificates'),
      languages: {
        id: '/id/certificates',
        en: '/en/certificates',
        'x-default': '/id/certificates',
      },
    },
  };
}

export default async function CertificatesPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
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
        eyebrow={dictionary.certificates.eyebrow}
        title={dictionary.certificates.title}
        description={dictionary.certificates.description}
      >
        <span className="chip chip-accent">
          {portfolio.certificates.length} {dictionary.nav.certificates}
        </span>
      </PageHero>

      <Section>
        <CertificateGallery
          certificates={portfolio.certificates}
          locale={locale}
          dictionary={dictionary}
        />
      </Section>
    </>
  );
}

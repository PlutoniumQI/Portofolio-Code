import type { Metadata, Viewport } from 'next';
import { Inter, JetBrains_Mono } from 'next/font/google';
import { notFound } from 'next/navigation';
import '../globals.css';
import { BackToTop } from '@/components/site/BackToTop';
import { PageTransition } from '@/components/site/PageTransition';
import { SiteEffects } from '@/components/site/SiteEffects';
import { SiteFooter } from '@/components/site/SiteFooter';
import { SiteHeader } from '@/components/site/SiteHeader';
import { ThemeScript } from '@/components/site/ThemeScript';
import { getPortfolio, getSiteUrl } from '@/lib/api';
import { getDictionary } from '@/lib/dictionaries';
import { isLocale, locales, localePath } from '@/lib/i18n';

const inter = Inter({
  subsets: ['latin'],
  weight: ['300', '400', '500', '600', '700'],
  variable: '--font-inter',
  display: 'swap',
});

const jetbrainsMono = JetBrains_Mono({
  subsets: ['latin'],
  weight: ['400', '500'],
  variable: '--font-jetbrains',
  display: 'swap',
});

export const revalidate = 300;

export const viewport: Viewport = {
  width: 'device-width',
  initialScale: 1,
  themeColor: [
    { media: '(prefers-color-scheme: dark)', color: '#08080a' },
    { media: '(prefers-color-scheme: light)', color: '#f6f6f2' },
  ],
};

export function generateStaticParams() {
  return locales.map((locale) => ({ locale }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    return {};
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const siteUrl = getSiteUrl();

  return {
    metadataBase: new URL(siteUrl),
    title: {
      default: dictionary.meta.home.title,
      template: `%s | ${portfolio.profile.full_name}`,
    },
    description: dictionary.meta.home.description,
    applicationName: `re:SKY — ${portfolio.profile.full_name}`,
    authors: [{ name: portfolio.profile.full_name }],
    creator: portfolio.profile.full_name,
    alternates: {
      canonical: localePath(locale),
      languages: {
        id: '/id',
        en: '/en',
        'x-default': '/id',
      },
    },
    openGraph: {
      type: 'website',
      siteName: `re:SKY — ${portfolio.profile.full_name}`,
      title: dictionary.meta.home.title,
      description: dictionary.meta.home.description,
      url: localePath(locale),
      locale: locale === 'id' ? 'id_ID' : 'en_GB',
    },
    twitter: {
      card: 'summary',
      title: dictionary.meta.home.title,
      description: dictionary.meta.home.description,
    },
    icons: {
      icon: [{ url: '/favicon.ico' }],
    },
  };
}

export default async function LocaleLayout({
  children,
  params,
}: {
  children: React.ReactNode;
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
    <html
      lang={locale}
      className={`${inter.variable} ${jetbrainsMono.variable}`}
      suppressHydrationWarning
    >
      <head>
        <ThemeScript />
      </head>
      <body>
        <a href="#main" className="skip-link">
          {locale === 'id' ? 'Lewati ke konten' : 'Skip to content'}
        </a>

        <div id="glow" style={{ left: '68%', top: '18%' }} aria-hidden="true" />

        <SiteHeader locale={locale} dictionary={dictionary} />

        <PageTransition>
          <main id="main">{children}</main>
        </PageTransition>

        <SiteFooter locale={locale} dictionary={dictionary} profile={portfolio.profile} />

        <BackToTop label={dictionary.footer.backToTop} />
        <SiteEffects />
      </body>
    </html>
  );
}

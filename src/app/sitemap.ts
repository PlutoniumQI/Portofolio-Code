import type { MetadataRoute } from 'next';
import fallbackFile from '@/data/portfolio-fallback.json';
import { getSiteUrl } from '@/lib/api';
import { locales, localePath } from '@/lib/i18n';
import type { Portfolio } from '@/lib/types';

const fallback = fallbackFile as unknown as Record<'id' | 'en', Portfolio>;

const staticPaths = [
  '',
  '/about',
  '/skills',
  '/projects',
  '/experience',
  '/certificates',
  '/testimonials',
  '/blog',
  '/cv',
  '/contact',
  '/guestbook',
];

export default function sitemap(): MetadataRoute.Sitemap {
  const base = getSiteUrl();
  const lastModified = new Date();
  const entries: MetadataRoute.Sitemap = [];

  for (const locale of locales) {
    for (const path of staticPaths) {
      entries.push({
        url: `${base}${localePath(locale, path)}`,
        lastModified,
        changeFrequency: path === '' ? 'weekly' : 'monthly',
        priority: path === '' ? 1 : 0.7,
        alternates: {
          languages: {
            id: `${base}${localePath('id', path)}`,
            en: `${base}${localePath('en', path)}`,
          },
        },
      });
    }

    for (const project of fallback[locale].projects) {
      const path = `/projects/${project.slug}`;

      entries.push({
        url: `${base}${localePath(locale, path)}`,
        lastModified,
        changeFrequency: 'monthly',
        priority: 0.8,
        alternates: {
          languages: {
            id: `${base}${localePath('id', path)}`,
            en: `${base}${localePath('en', path)}`,
          },
        },
      });
    }

    for (const post of fallback[locale].posts) {
      const path = `/blog/${post.slug}`;

      entries.push({
        url: `${base}${localePath(locale, path)}`,
        lastModified,
        changeFrequency: 'yearly',
        priority: 0.6,
        alternates: {
          languages: {
            id: `${base}${localePath('id', path)}`,
            en: `${base}${localePath('en', path)}`,
          },
        },
      });
    }
  }

  return entries;
}

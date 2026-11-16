import type { Locale } from './types';

export const locales = ['id', 'en'] as const;

export const defaultLocale: Locale = 'id';

export const localeLabels: Record<Locale, { short: string; long: string; html: string }> = {
  id: { short: 'ID', long: 'Indonesia', html: 'id' },
  en: { short: 'EN', long: 'English', html: 'en' },
};

export function isLocale(value: string): value is Locale {
  return (locales as readonly string[]).includes(value);
}

/** Build a locale-prefixed path, e.g. localePath('en', '/projects') -> '/en/projects'. */
export function localePath(locale: Locale, path = ''): string {
  const clean = path === '/' ? '' : path;
  const withSlash = clean && !clean.startsWith('/') ? `/${clean}` : clean;

  return `/${locale}${withSlash}`;
}

export function localeFromPathname(pathname: string): Locale {
  const segment = pathname.split('/').filter(Boolean)[0];

  return segment === 'en' ? 'en' : 'id';
}

/** Swap the locale segment while keeping the rest of the path and query string. */
export function switchLocalePath(pathname: string, target: Locale): string {
  const parts = pathname.split('/').filter(Boolean);

  if (parts[0] === 'id' || parts[0] === 'en') {
    parts[0] = target;
  } else {
    parts.unshift(target);
  }

  return `/${parts.join('/')}`;
}

export function formatDate(value: string | null, locale: Locale, options?: Intl.DateTimeFormatOptions): string {
  if (!value) {
    return '';
  }

  const date = new Date(value);

  if (Number.isNaN(date.getTime())) {
    return value;
  }

  return new Intl.DateTimeFormat(locale === 'id' ? 'id-ID' : 'en-GB', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    ...options,
  }).format(date);
}

export function formatMonth(value: string | null, locale: Locale): string {
  if (!value) {
    return '';
  }

  const date = new Date(`${value}-01`);

  if (Number.isNaN(date.getTime())) {
    return value;
  }

  return new Intl.DateTimeFormat(locale === 'id' ? 'id-ID' : 'en-GB', {
    month: 'short',
    year: 'numeric',
  }).format(date);
}

export function initials(name: string): string {
  return name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part.charAt(0).toUpperCase())
    .join('');
}

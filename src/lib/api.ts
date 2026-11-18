import fallbackFile from '@/data/portfolio-fallback.json';
import type { Locale, Portfolio, Post, Project } from './types';

const fallback = fallbackFile as unknown as Record<Locale, Portfolio>;

const apiUrl = (process.env.API_URL ?? process.env.NEXT_PUBLIC_API_URL ?? 'http://127.0.0.1:8000').replace(
  /\/+$/,
  '',
);

const revalidateSeconds = Number(process.env.PORTFOLIO_REVALIDATE_SECONDS ?? 60);

/**
 * During `next build`, use the committed snapshot instead of the API. Vercel
 * builds should never depend on the backend being awake; ISR fetches live
 * content on the first revalidation after deployment.
 */
const isProductionBuild = process.env.NEXT_PHASE === 'phase-production-build';

export function getSiteUrl(): string {
  return (process.env.NEXT_PUBLIC_SITE_URL ?? 'http://localhost:3000').replace(/\/+$/, '');
}

async function apiFetch<T>(path: string, tags: string[], fallbackValue: T, revalidate = revalidateSeconds): Promise<T> {
  if (!apiUrl || isProductionBuild) {
    return fallbackValue;
  }

  try {
    const response = await fetch(`${apiUrl}${path}`, {
      headers: { Accept: 'application/json' },
      next: { revalidate, tags },
      signal: AbortSignal.timeout(10_000),
    });

    if (!response.ok) {
      return fallbackValue;
    }

    return (await response.json()) as T;
  } catch {
    return fallbackValue;
  }
}

/**
 * Localized portfolio payload. Falls back to the committed snapshot when the
 * backend is unreachable, so pages always render.
 */
export async function getPortfolio(locale: Locale): Promise<Portfolio> {
  const data = await apiFetch<Portfolio>(
    `/api/portfolio?locale=${locale}`,
    ['portfolio'],
    fallback[locale],
  );

  return data?.profile ? { ...data, locale } : fallback[locale];
}

export function findProject(portfolio: Portfolio, slug: string): Project | undefined {
  return portfolio.projects.find((project) => project.slug === slug);
}

/**
 * Route params arrive URL-encoded, so a legacy slug stored with spaces or
 * capitals ("Exam Platform") would never match the API payload. Decode first.
 */
export function decodeSlug(value: string): string {
  try {
    return decodeURIComponent(value);
  } catch {
    return value;
  }
}

export function findPost(portfolio: Portfolio, slug: string): Post | undefined {
  return portfolio.posts.find((post) => post.slug === slug);
}

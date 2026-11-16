'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Icon } from '@/components/ui/Icons';
import { getDictionary } from '@/lib/dictionaries';
import { localeFromPathname, localePath } from '@/lib/i18n';

export default function NotFound() {
  const pathname = usePathname();
  const locale = localeFromPathname(pathname);
  const dictionary = getDictionary(locale);

  return (
    <div className="relative flex min-h-[70vh] items-center justify-center overflow-hidden px-5 py-20">
      <div className="hero-orb left-1/2 top-1/3 opacity-70" aria-hidden="true" />
      <div className="relative text-center">
        <p className="font-mono text-[11px] uppercase tracking-[0.3em] text-accent">404</p>
        <h1 className="mt-4 text-3xl font-semibold tracking-tight text-ink sm:text-5xl">
          {dictionary.notFound.title}
        </h1>
        <p className="mx-auto mt-4 max-w-md text-sm leading-relaxed text-muted">
          {dictionary.notFound.description}
        </p>
        <div className="mt-8 flex flex-wrap justify-center gap-3">
          <Link href={localePath(locale)} className="btn-primary">
            <Icon name="home" size={14} />
            {dictionary.common.backHome}
          </Link>
          <Link href={localePath(locale, '/projects')} className="btn-ghost">
            {dictionary.common.viewProjects}
            <Icon name="arrow-right" size={14} />
          </Link>
        </div>
      </div>
    </div>
  );
}

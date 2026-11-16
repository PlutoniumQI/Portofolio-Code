'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useEffect, useState } from 'react';
import { Icon } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { localeLabels, localePath, switchLocalePath } from '@/lib/i18n';
import type { Locale } from '@/lib/types';
import { ThemeToggle } from './ThemeToggle';

interface NavItem {
  key: keyof Dictionary['nav'];
  href: string;
}

const primaryNav: NavItem[] = [
  { key: 'home', href: '' },
  { key: 'about', href: '/about' },
  { key: 'projects', href: '/projects' },
  { key: 'experience', href: '/experience' },
  { key: 'blog', href: '/blog' },
  { key: 'contact', href: '/contact' },
];

const secondaryNav: NavItem[] = [
  { key: 'skills', href: '/skills' },
  { key: 'certificates', href: '/certificates' },
  { key: 'testimonials', href: '/testimonials' },
  { key: 'cv', href: '/cv' },
  { key: 'guestbook', href: '/guestbook' },
];

export function SiteHeader({ locale, dictionary }: { locale: Locale; dictionary: Dictionary }) {
  const pathname = usePathname();
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 16);

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    if (!open) {
      document.body.style.overflow = '';

      return;
    }

    document.body.style.overflow = 'hidden';

    const onKey = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        setOpen(false);
      }
    };

    window.addEventListener('keydown', onKey);

    return () => {
      document.body.style.overflow = '';
      window.removeEventListener('keydown', onKey);
    };
  }, [open]);

  function isActive(href: string): boolean {
    const full = localePath(locale, href);

    if (href === '') {
      return pathname === full;
    }

    return pathname === full || pathname.startsWith(`${full}/`);
  }

  return (
    <header
      id="navbar"
      className={`sticky top-0 z-50 transition-colors duration-300 ${
        scrolled || open ? 'border-b border-line bg-bg/85 backdrop-blur-xl' : 'border-b border-transparent'
      }`}
    >
      <div className="mx-auto flex h-[72px] w-full max-w-6xl items-center justify-between gap-4 px-5 sm:px-8">
        <Link
          href={localePath(locale)}
          className="group flex items-center gap-2 font-mono text-sm tracking-[0.16em] text-ink"
          aria-label={`re:SKY — ${dictionary.nav.home}`}
        >
          <span className="relative flex h-8 w-8 items-center justify-center rounded-full border border-accent-line bg-accent-soft text-[11px] font-semibold text-accent">
            RS
            <span className="absolute -right-0.5 -top-0.5 h-1.5 w-1.5 rounded-full bg-accent" />
          </span>
          <span>
            re<span className="text-accent">:</span>SKY
          </span>
        </Link>

        <nav className="hidden items-center gap-7 text-[13px] lg:flex" aria-label={dictionary.nav.menu}>
          {primaryNav.map((item) => (
            <Link
              key={item.key}
              href={localePath(locale, item.href)}
              className="nav-link"
              aria-current={isActive(item.href) ? 'page' : undefined}
            >
              {dictionary.nav[item.key]}
            </Link>
          ))}

          <div className="group relative">
            <button
              type="button"
              className="nav-link flex items-center gap-1 text-[13px]"
              aria-haspopup="true"
            >
              {dictionary.nav.more}
              <Icon name="chevron-down" size={12} className="transition-transform group-hover:rotate-180" />
            </button>
            <div className="invisible absolute left-1/2 top-full z-50 w-48 -translate-x-1/2 pt-4 opacity-0 transition-all duration-200 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
              <div className="overflow-hidden rounded-xl border border-line bg-elevated p-1.5 shadow-2xl shadow-black/20">
                {secondaryNav.map((item) => (
                  <Link
                    key={item.key}
                    href={localePath(locale, item.href)}
                    className={`flex items-center justify-between rounded-lg px-3 py-2 text-[13px] transition-colors hover:bg-accent-soft hover:text-accent ${
                      isActive(item.href) ? 'text-accent' : 'text-muted'
                    }`}
                    aria-current={isActive(item.href) ? 'page' : undefined}
                  >
                    {dictionary.nav[item.key]}
                    <Icon name="arrow-up-right" size={12} />
                  </Link>
                ))}
              </div>
            </div>
          </div>
        </nav>

        <div className="flex items-center gap-2">
          <div
            className="hidden items-center rounded-full border border-line p-0.5 font-mono text-[10px] tracking-widest sm:flex"
            role="group"
            aria-label={dictionary.nav.language}
          >
            <Link
              href={switchLocalePath(pathname, 'id')}
              hrefLang="id"
              title={localeLabels.id.long}
              className={`rounded-full px-2.5 py-1 transition-colors ${
                locale === 'id' ? 'bg-accent-soft text-accent' : 'text-faint hover:text-ink'
              }`}
            >
              ID
            </Link>
            <Link
              href={switchLocalePath(pathname, 'en')}
              hrefLang="en"
              title={localeLabels.en.long}
              className={`rounded-full px-2.5 py-1 transition-colors ${
                locale === 'en' ? 'bg-accent-soft text-accent' : 'text-faint hover:text-ink'
              }`}
            >
              EN
            </Link>
          </div>

          <ThemeToggle label={dictionary.nav.theme} />

          <Link
            href={localePath(locale, '/contact')}
            className="hidden items-center gap-1.5 rounded-full bg-accent px-4 py-2 text-[12px] font-semibold text-on-accent transition-transform hover:-translate-y-0.5 xl:inline-flex"
          >
            {dictionary.common.getInTouch}
            <Icon name="arrow-up-right" size={13} />
          </Link>

          <button
            id="menuToggle"
            type="button"
            onClick={() => setOpen((value) => !value)}
            aria-expanded={open}
            aria-controls="mobileMenu"
            aria-label={open ? dictionary.nav.close : dictionary.nav.menu}
            className={`flex h-9 w-9 flex-col items-center justify-center gap-[5px] rounded-full border border-line text-ink transition-colors hover:border-accent-line lg:hidden ${
              open ? 'active' : ''
            }`}
          >
            <span className="hamburger-line" />
            <span className="hamburger-line" />
            <span className="hamburger-line" />
          </button>
        </div>
      </div>

      <div
        id="mobileMenu"
        className={`fixed inset-x-0 bottom-0 top-[72px] z-40 overflow-y-auto border-t border-line bg-bg/95 backdrop-blur-xl lg:hidden ${
          open ? 'open' : ''
        }`}
        aria-hidden={!open}
      >
        <div className="mx-auto flex min-h-full w-full max-w-6xl flex-col px-5 pb-10 pt-6 sm:px-8">
          <div className="flex items-center justify-between pb-5">
            <p className="section-eyebrow">{dictionary.nav.menu}</p>
            <div
              className="flex items-center rounded-full border border-line p-0.5 font-mono text-[10px] tracking-widest"
              role="group"
              aria-label={dictionary.nav.language}
            >
              <Link
                href={switchLocalePath(pathname, 'id')}
                hrefLang="id"
                onClick={() => setOpen(false)}
                className={`rounded-full px-3 py-1 ${
                  locale === 'id' ? 'bg-accent-soft text-accent' : 'text-faint'
                }`}
              >
                ID
              </Link>
              <Link
                href={switchLocalePath(pathname, 'en')}
                hrefLang="en"
                onClick={() => setOpen(false)}
                className={`rounded-full px-3 py-1 ${
                  locale === 'en' ? 'bg-accent-soft text-accent' : 'text-faint'
                }`}
              >
                EN
              </Link>
            </div>
          </div>

          <nav className="flex flex-col" aria-label={dictionary.nav.menu}>
            {[...primaryNav, ...secondaryNav].map((item, index) => (
              <Link
                key={item.key}
                href={localePath(locale, item.href)}
                onClick={() => setOpen(false)}
                className="mobile-nav-link group flex items-center justify-between border-b border-line py-4 text-lg text-ink"
                style={{ transitionDelay: open ? `${index * 35}ms` : '0ms' }}
                aria-current={isActive(item.href) ? 'page' : undefined}
              >
                <span className={isActive(item.href) ? 'text-accent' : undefined}>
                  {dictionary.nav[item.key]}
                </span>
                <Icon
                  name="arrow-up-right"
                  size={18}
                  className="text-faint transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent"
                />
              </Link>
            ))}
          </nav>

          <div className="mt-auto pt-8">
            <Link
              href={localePath(locale, '/contact')}
              onClick={() => setOpen(false)}
              className="btn-primary w-full"
            >
              {dictionary.common.getInTouch}
              <Icon name="arrow-up-right" size={15} />
            </Link>
          </div>
        </div>
      </div>
    </header>
  );
}

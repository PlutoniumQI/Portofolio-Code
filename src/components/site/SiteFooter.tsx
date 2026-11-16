import Link from 'next/link';
import { Icon, type IconName } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { localePath } from '@/lib/i18n';
import type { Locale, Profile } from '@/lib/types';

const socialIcons: Record<string, IconName> = {
  linkedin: 'linkedin',
  github: 'github',
  instagram: 'instagram',
  website: 'globe',
};

const socialLabels: Record<string, string> = {
  linkedin: 'LinkedIn',
  github: 'GitHub',
  instagram: 'Instagram',
  website: 'Website',
};

export function SiteFooter({
  locale,
  dictionary,
  profile,
}: {
  locale: Locale;
  dictionary: Dictionary;
  profile: Profile;
}) {
  const year = new Date().getFullYear();
  const socials = Object.entries(profile.socials).filter(([, url]) => Boolean(url));

  return (
    <footer className="relative z-10 mt-20 border-t border-line bg-elevated/40">
      <div className="mx-auto w-full max-w-6xl px-5 py-14 sm:px-8">
        <div className="grid gap-10 lg:grid-cols-[1.6fr_0.8fr_0.8fr_1.1fr]">
          <div className="max-w-sm">
            <Link
              href={localePath(locale)}
              className="inline-flex items-center gap-2 font-mono text-sm tracking-[0.16em]"
            >
              <span className="flex h-8 w-8 items-center justify-center rounded-full border border-accent-line bg-accent-soft text-[11px] font-semibold text-accent">
                RS
              </span>
              <span>
                re<span className="text-accent">:</span>SKY
              </span>
            </Link>
            <p className="mt-4 text-sm leading-relaxed text-muted">{dictionary.footer.tagline}</p>
            <div className="mt-5 inline-flex items-center gap-2 rounded-full border border-line px-3 py-1.5 text-[11px] text-muted">
              <span
                className={`h-1.5 w-1.5 rounded-full ${profile.is_available ? 'bg-accent' : 'bg-faint'}`}
              />
              {profile.is_available ? dictionary.common.available : dictionary.common.unavailable}
              {profile.availability ? ` — ${profile.availability}` : ''}
            </div>
          </div>

          <nav aria-label={dictionary.common.quickLinks}>
            <h2 className="font-mono text-[10px] uppercase tracking-[0.2em] text-faint">
              {dictionary.common.quickLinks}
            </h2>
            <ul className="mt-4 space-y-2.5 text-sm">
              {[
                ['home', ''],
                ['about', '/about'],
                ['skills', '/skills'],
                ['projects', '/projects'],
                ['experience', '/experience'],
              ].map(([key, href]) => (
                <li key={key}>
                  <Link
                    href={localePath(locale, href)}
                    className="text-muted transition-colors hover:text-accent"
                  >
                    {dictionary.nav[key as keyof Dictionary['nav']]}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>

          <nav aria-label={dictionary.nav.more}>
            <h2 className="font-mono text-[10px] uppercase tracking-[0.2em] text-faint">
              {dictionary.nav.more}
            </h2>
            <ul className="mt-4 space-y-2.5 text-sm">
              {[
                ['certificates', '/certificates'],
                ['testimonials', '/testimonials'],
                ['blog', '/blog'],
                ['cv', '/cv'],
                ['guestbook', '/guestbook'],
              ].map(([key, href]) => (
                <li key={key}>
                  <Link
                    href={localePath(locale, href)}
                    className="text-muted transition-colors hover:text-accent"
                  >
                    {dictionary.nav[key as keyof Dictionary['nav']]}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>

          <div>
            <h2 className="font-mono text-[10px] uppercase tracking-[0.2em] text-faint">
              {dictionary.common.getInTouch}
            </h2>
            <ul className="mt-4 space-y-3 text-sm">
              {profile.email && (
                <li>
                  <a
                    href={`mailto:${profile.email}`}
                    className="flex items-center gap-2 text-muted transition-colors hover:text-accent"
                  >
                    <Icon name="mail" size={14} className="text-accent" />
                    {profile.email}
                  </a>
                </li>
              )}
              {profile.location && (
                <li className="flex items-center gap-2 text-muted">
                  <Icon name="map-pin" size={14} className="text-accent" />
                  {profile.location}
                </li>
              )}
            </ul>

            {socials.length > 0 && (
              <div className="mt-5 flex flex-wrap gap-2">
                {socials.map(([key, url]) => (
                  <a
                    key={key}
                    href={url}
                    target="_blank"
                    rel="noreferrer noopener"
                    aria-label={socialLabels[key] ?? key}
                    title={socialLabels[key] ?? key}
                    className="flex h-9 w-9 items-center justify-center rounded-full border border-line text-muted transition-colors hover:border-accent-line hover:bg-accent-soft hover:text-accent"
                  >
                    <Icon name={socialIcons[key] ?? 'globe'} size={15} />
                  </a>
                ))}
              </div>
            )}
          </div>
        </div>

        <div className="mt-12 flex flex-col gap-3 border-t border-line pt-6 text-[11px] text-faint sm:flex-row sm:items-center sm:justify-between">
          <p>
            © {year} {profile.full_name}. {dictionary.footer.rights}
          </p>
          <p className="font-mono tracking-wide">{dictionary.footer.builtWith}</p>
        </div>
      </div>
    </footer>
  );
}

import { Icon, type IconName } from '@/components/ui/Icons';
import type { SocialLinks as SocialLinksType } from '@/lib/types';

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

export function SocialLinks({
  socials,
  className = '',
}: {
  socials: SocialLinksType;
  className?: string;
}) {
  const entries = Object.entries(socials).filter(([, url]) => Boolean(url));

  if (entries.length === 0) {
    return null;
  }

  return (
    <div className={`flex flex-wrap items-center gap-2 ${className}`}>
      {entries.map(([key, url]) => (
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
  );
}

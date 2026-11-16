import type { ReactNode } from 'react';
import { Icon, type IconName } from '@/components/ui/Icons';
import type { Project } from '@/lib/types';

const categoryIcons: Record<string, IconName> = {
  'web-app': 'code',
  'ui-ux': 'palette',
  'design-system': 'layers',
  mobile: 'grid',
  other: 'sparkles',
};

export function ProjectArtwork({
  project,
  index = 0,
  className = '',
}: {
  project: Project;
  index?: number;
  className?: string;
}) {
  const icon = categoryIcons[project.category ?? 'other'] ?? 'sparkles';

  return (
    <div className={`project-art relative h-full w-full overflow-hidden ${className}`}>
      <div className="art-grid absolute inset-0 opacity-70" aria-hidden="true" />
      <div
        className="absolute -right-16 -top-20 h-52 w-52 rounded-full border border-accent-line/40"
        aria-hidden="true"
      />
      <div
        className="absolute -bottom-20 -left-16 h-44 w-44 rounded-full bg-accent-soft blur-2xl"
        aria-hidden="true"
      />

      <div className="absolute left-4 top-4 flex items-center gap-1.5 rounded-full border border-accent-line bg-bg/60 px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.14em] text-accent backdrop-blur-sm">
        <Icon name={icon} size={11} />
        {project.category_label || project.category || 'project'}
      </div>

      <span className="absolute bottom-4 right-5 font-mono text-[11px] tracking-[0.2em] text-faint">
        {String(index + 1).padStart(2, '0')}
      </span>

      <div className="relative flex h-full items-center justify-center">
        <div className="flex h-20 w-20 rotate-3 items-center justify-center rounded-2xl border border-line-strong bg-elevated/70 text-accent shadow-xl shadow-black/10 backdrop-blur-sm transition-transform duration-500 group-hover:rotate-0 group-hover:scale-105">
          <Icon name={icon} size={30} />
        </div>
      </div>
    </div>
  );
}

export function ProjectCover({
  project,
  index = 0,
  className = '',
  sizes,
}: {
  project: Project;
  index?: number;
  className?: string;
  sizes?: string;
}) {
  if (project.image_url) {
    return (
      <img
        src={project.image_url}
        alt={project.title}
        loading="lazy"
        sizes={sizes}
        className={`h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03] ${className}`}
      />
    );
  }

  return <ProjectArtwork project={project} index={index} className={className} />;
}

export function Badge({
  children,
  tone = 'neutral',
  className = '',
}: {
  children: ReactNode;
  tone?: 'neutral' | 'accent';
  className?: string;
}) {
  return <span className={`chip ${tone === 'accent' ? 'chip-accent' : ''} ${className}`}>{children}</span>;
}

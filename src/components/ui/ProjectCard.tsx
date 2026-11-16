import Link from 'next/link';
import { Icon } from '@/components/ui/Icons';
import { ProjectCover } from '@/components/ui/ProjectArtwork';
import type { Dictionary } from '@/lib/dictionaries';
import { localePath } from '@/lib/i18n';
import type { Locale, Project } from '@/lib/types';

export function ProjectCard({
  project,
  locale,
  dictionary,
  index = 0,
}: {
  project: Project;
  locale: Locale;
  dictionary: Dictionary;
  index?: number;
}) {
  return (
    <Link
      href={localePath(locale, `/projects/${project.slug}`)}
      className="project-card-glow group flex flex-col overflow-hidden rounded-2xl border border-line bg-elevated/70 transition-all duration-300 hover:-translate-y-1 hover:border-accent-line"
    >
      <div className="relative aspect-[16/10] overflow-hidden border-b border-line">
        <ProjectCover project={project} index={index} sizes="(max-width: 768px) 100vw, 50vw" />
        <div className="absolute right-3 top-3 flex flex-col items-end gap-1.5">
          {project.is_featured && (
            <span className="rounded-full border border-accent-line bg-accent-soft px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.14em] text-accent backdrop-blur-sm">
              {dictionary.common.featured}
            </span>
          )}
          {project.status === 'coming_soon' && (
            <span className="rounded-full border border-line bg-bg/70 px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.14em] text-muted backdrop-blur-sm">
              {dictionary.common.comingSoon}
            </span>
          )}
        </div>
      </div>

      <div className="relative z-[2] flex flex-1 flex-col p-5">
        <div className="flex flex-wrap items-center gap-2 font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
          <span className="text-accent">{project.category_label || project.category}</span>
          <span className="h-1 w-1 rounded-full bg-line-strong" />
          <span>{project.tags.length} {dictionary.common.tags.toLowerCase()}</span>
        </div>

        <h3 className="mt-2.5 text-lg font-semibold tracking-tight text-ink transition-colors group-hover:text-accent">
          {project.title}
        </h3>

        {project.excerpt && (
          <p className="mt-2 line-clamp-2 text-sm leading-relaxed text-muted">{project.excerpt}</p>
        )}

        <div className="mt-4 flex flex-wrap gap-1.5">
          {project.tags.slice(0, 4).map((tag) => (
            <span key={tag} className="chip">
              {tag}
            </span>
          ))}
        </div>

        <div className="mt-auto flex items-center justify-between pt-5 text-[11px] text-faint">
          <span className="inline-flex items-center gap-1.5 font-medium text-ink transition-colors group-hover:text-accent">
            {dictionary.common.viewProject}
            <Icon
              name="arrow-right"
              size={13}
              className="transition-transform group-hover:translate-x-1"
            />
          </span>
          <span className="font-mono uppercase tracking-[0.16em]">
            {String(index + 1).padStart(2, '0')}
          </span>
        </div>
      </div>
    </Link>
  );
}

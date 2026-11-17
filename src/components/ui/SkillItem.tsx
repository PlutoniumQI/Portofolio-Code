import type { IconName } from '@/components/ui/Icons';
import type { Skill, SkillLevel } from '@/lib/types';

export function skillIcon(name: string): IconName {
  const value = name.toLowerCase();

  if (value.includes('figma') || value.includes('design')) return 'figma';
  if (value.includes('ux') || value.includes('ui')) return 'palette';
  if (value.includes('laravel') || value.includes('php')) return 'layers';
  if (value.includes('react') || value.includes('next') || value.includes('javascript')) return 'code';
  if (value.includes('html') || value.includes('css')) return 'code';
  if (value.includes('tailwind')) return 'sparkles';

  return 'layers';
}

const levelStyles: Record<SkillLevel, string> = {
  beginner: 'border-line text-faint',
  intermediate: 'border-accent-line text-accent',
  advanced: 'border-accent-line bg-accent-soft text-accent',
  expert: 'border-accent bg-accent text-on-accent',
};

export function skillLevelClass(level: SkillLevel): string {
  return levelStyles[level] ?? levelStyles.intermediate;
}

export function SkillItem({
  skill,
  levelLabel,
  compact = false,
}: {
  skill: Skill;
  levelLabel: string;
  compact?: boolean;
}) {
  return (
    <div>
      <div className="flex flex-wrap items-center justify-between gap-2">
        <span className="text-sm font-medium text-ink">{skill.name}</span>
        <span
          className={`rounded-full border px-2 py-0.5 font-mono text-[9px] uppercase tracking-[0.12em] ${skillLevelClass(skill.level)}`}
        >
          {levelLabel}
        </span>
      </div>
      {!compact && skill.description && (
        <p className="mt-2 text-xs leading-relaxed text-faint">{skill.description}</p>
      )}
    </div>
  );
}

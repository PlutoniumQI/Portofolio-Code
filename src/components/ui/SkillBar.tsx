import type { CSSProperties } from 'react';
import type { IconName } from '@/components/ui/Icons';
import type { Skill } from '@/lib/types';

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

export function SkillBar({ skill, compact = false }: { skill: Skill; compact?: boolean }) {
  return (
    <div>
      <div className="flex items-baseline justify-between gap-3">
        <span className="text-sm font-medium text-ink">{skill.name}</span>
        <span className="font-mono text-[10px] text-accent">{skill.percentage}%</span>
      </div>
      <div
        className="skill-bar mt-2"
        role="progressbar"
        aria-valuenow={skill.percentage}
        aria-valuemin={0}
        aria-valuemax={100}
        aria-label={skill.name}
      >
        <span
          style={
            {
              '--value': `${skill.percentage}%`,
              width: `${skill.percentage}%`,
            } as CSSProperties
          }
        />
      </div>
      {!compact && skill.description && (
        <p className="mt-2 text-xs leading-relaxed text-faint">{skill.description}</p>
      )}
    </div>
  );
}

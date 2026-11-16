import type { ReactNode } from 'react';
import { Icon, type IconName } from '@/components/ui/Icons';

export function EmptyState({
  icon = 'sparkles',
  title,
  description,
  action,
}: {
  icon?: IconName;
  title: string;
  description?: string;
  action?: ReactNode;
}) {
  return (
    <div className="flex flex-col items-center justify-center rounded-2xl border border-dashed border-line-strong bg-elevated/40 px-6 py-16 text-center">
      <span className="flex h-12 w-12 items-center justify-center rounded-full border border-line bg-accent-soft text-accent">
        <Icon name={icon} size={20} />
      </span>
      <p className="mt-4 text-sm font-medium text-ink">{title}</p>
      {description && <p className="mt-1 max-w-sm text-xs leading-relaxed text-muted">{description}</p>}
      {action && <div className="mt-5">{action}</div>}
    </div>
  );
}

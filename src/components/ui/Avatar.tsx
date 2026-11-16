import { initials } from '@/lib/i18n';

const sizes = {
  sm: 'h-9 w-9 text-[11px]',
  md: 'h-11 w-11 text-xs',
  lg: 'h-14 w-14 text-sm',
  xl: 'h-28 w-28 text-2xl',
} as const;

export function Avatar({
  src,
  name,
  size = 'md',
  className = '',
}: {
  src?: string | null;
  name: string;
  size?: keyof typeof sizes;
  className?: string;
}) {
  if (src) {
    return (
      <img
        src={src}
        alt={name}
        loading="lazy"
        className={`${sizes[size]} shrink-0 rounded-full border border-line object-cover ${className}`}
      />
    );
  }

  return (
    <span
      aria-hidden="true"
      className={`${sizes[size]} inline-flex shrink-0 items-center justify-center rounded-full border border-accent-line bg-accent-soft font-mono font-semibold text-accent ${className}`}
    >
      {initials(name)}
    </span>
  );
}

'use client';

import { Icon } from '@/components/ui/Icons';

export function ThemeToggle({ label }: { label: string }) {
  function toggle() {
    const root = document.documentElement;
    const next = root.classList.contains('dark') ? 'light' : 'dark';

    root.classList.toggle('dark', next === 'dark');

    try {
      localStorage.setItem('theme', next);
    } catch {
      // Storage can be unavailable in private browsing.
    }
  }

  return (
    <button
      type="button"
      onClick={toggle}
      aria-label={label}
      title={label}
      className="inline-flex h-9 w-9 items-center justify-center rounded-full border border-line text-muted transition-colors hover:border-accent-line hover:text-accent"
    >
      <Icon name="sun" size={16} className="hidden dark:block" />
      <Icon name="moon" size={16} className="dark:hidden" />
    </button>
  );
}

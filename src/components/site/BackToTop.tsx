'use client';

import { useEffect, useState } from 'react';
import { Icon } from '@/components/ui/Icons';

export function BackToTop({ label }: { label: string }) {
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const onScroll = () => setVisible(window.scrollY > 480);

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  return (
    <button
      id="backToTop"
      type="button"
      aria-label={label}
      title={label}
      onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
      className={`fixed bottom-5 right-5 z-40 flex h-11 w-11 items-center justify-center rounded-full border border-accent-line bg-elevated text-accent shadow-lg shadow-black/20 transition-colors hover:bg-accent hover:text-on-accent sm:bottom-7 sm:right-7 ${
        visible ? 'show' : ''
      }`}
    >
      <Icon name="arrow-up" size={18} />
    </button>
  );
}

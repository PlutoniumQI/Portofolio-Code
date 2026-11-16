'use client';

import { useEffect } from 'react';
import { Icon } from '@/components/ui/Icons';

export interface LightboxItem {
  src: string;
  alt: string;
  caption?: string;
}

export function Lightbox({
  items,
  index,
  onClose,
  onIndexChange,
  label,
}: {
  items: LightboxItem[];
  index: number | null;
  onClose: () => void;
  onIndexChange: (index: number) => void;
  label: string;
}) {
  const open = index !== null && items.length > 0;

  useEffect(() => {
    if (!open) {
      return;
    }

    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';

    const onKey = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        onClose();
      }

      if (event.key === 'ArrowRight' && index !== null) {
        onIndexChange((index + 1) % items.length);
      }

      if (event.key === 'ArrowLeft' && index !== null) {
        onIndexChange((index - 1 + items.length) % items.length);
      }
    };

    window.addEventListener('keydown', onKey);

    return () => {
      document.body.style.overflow = previousOverflow;
      window.removeEventListener('keydown', onKey);
    };
  }, [open, index, items.length, onClose, onIndexChange]);

  if (!open || index === null) {
    return null;
  }

  const item = items[index];

  return (
    <div
      className="fixed inset-0 z-[90] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm sm:p-8"
      role="dialog"
      aria-modal="true"
      aria-label={label}
      onMouseDown={(event) => {
        if (event.target === event.currentTarget) {
          onClose();
        }
      }}
    >
      <button
        type="button"
        onClick={onClose}
        aria-label="Close"
        className="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-black/40 text-white transition-colors hover:bg-white/10 sm:right-6 sm:top-6"
      >
        <Icon name="close" size={18} />
      </button>

      {items.length > 1 && (
        <>
          <button
            type="button"
            onClick={() => onIndexChange((index - 1 + items.length) % items.length)}
            aria-label="Previous"
            className="absolute left-3 z-10 flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-black/40 text-white transition-colors hover:bg-white/10 sm:left-6"
          >
            <Icon name="arrow-left" size={18} />
          </button>
          <button
            type="button"
            onClick={() => onIndexChange((index + 1) % items.length)}
            aria-label="Next"
            className="absolute right-3 z-10 flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-black/40 text-white transition-colors hover:bg-white/10 sm:right-6"
          >
            <Icon name="arrow-right" size={18} />
          </button>
        </>
      )}

      <figure className="lightbox-panel flex max-h-full w-full max-w-4xl flex-col items-center gap-3">
        <img
          src={item.src}
          alt={item.alt}
          className="max-h-[78vh] w-auto max-w-full rounded-lg border border-white/10 object-contain"
        />
        <figcaption className="flex w-full items-center justify-between gap-4 px-1 text-xs text-white/60">
          <span>{item.caption ?? item.alt}</span>
          <span className="font-mono">
            {index + 1}/{items.length}
          </span>
        </figcaption>
      </figure>
    </div>
  );
}

'use client';

import { useState } from 'react';
import { Icon } from '@/components/ui/Icons';
import { Lightbox, type LightboxItem } from '@/components/ui/Lightbox';

export function ProjectGallery({
  images,
  title,
  label,
}: {
  images: string[];
  title: string;
  label: string;
}) {
  const [index, setIndex] = useState<number | null>(null);

  if (images.length === 0) {
    return null;
  }

  const items: LightboxItem[] = images.map((src, position) => ({
    src,
    alt: `${title} — ${position + 1}`,
    caption: title,
  }));

  return (
    <>
      <div className="grid gap-3 sm:grid-cols-2">
        {images.map((image, position) => (
          <button
            key={`${image}-${position}`}
            type="button"
            onClick={() => setIndex(position)}
            className="group relative aspect-[4/3] overflow-hidden rounded-xl border border-line bg-elevated"
            aria-label={`${label} ${position + 1}`}
          >
            <img
              src={image}
              alt={`${title} — ${position + 1}`}
              loading="lazy"
              className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]"
            />
            <span className="absolute inset-0 flex items-center justify-center bg-black/0 text-white opacity-0 transition-all group-hover:bg-black/30 group-hover:opacity-100">
              <span className="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-black/30 backdrop-blur">
                <Icon name="search" size={16} />
              </span>
            </span>
          </button>
        ))}
      </div>

      <Lightbox
        items={items}
        index={index}
        onClose={() => setIndex(null)}
        onIndexChange={setIndex}
        label={label}
      />
    </>
  );
}

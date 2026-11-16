'use client';

import { useMemo, useState } from 'react';
import { CertificateCard } from '@/components/ui/CertificateCard';
import { EmptyState } from '@/components/ui/EmptyState';
import { Lightbox, type LightboxItem } from '@/components/ui/Lightbox';
import type { Dictionary } from '@/lib/dictionaries';
import type { Certificate, Locale } from '@/lib/types';

export function CertificateGallery({
  certificates,
  locale,
  dictionary,
}: {
  certificates: Certificate[];
  locale: Locale;
  dictionary: Dictionary;
}) {
  const [index, setIndex] = useState<number | null>(null);

  const images = useMemo<LightboxItem[]>(
    () =>
      certificates
        .filter((certificate) => Boolean(certificate.image_url))
        .map((certificate) => ({
          src: certificate.image_url as string,
          alt: `${dictionary.certificates.imageAlt}: ${certificate.title}`,
          caption: `${certificate.title} — ${certificate.issuer}`,
        })),
    [certificates, dictionary.certificates.imageAlt],
  );

  if (certificates.length === 0) {
    return <EmptyState icon="trophy" title={dictionary.certificates.empty} />;
  }

  return (
    <>
      <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        {certificates.map((certificate) => (
          <div key={certificate.id} className="reveal visible">
            <CertificateCard
              certificate={certificate}
              locale={locale}
              dictionary={dictionary}
              onPreview={
                certificate.image_url
                  ? () => {
                      const target = images.findIndex((image) => image.src === certificate.image_url);

                      setIndex(target === -1 ? 0 : target);
                    }
                  : undefined
              }
            />
          </div>
        ))}
      </div>

      <Lightbox
        items={images}
        index={index}
        onClose={() => setIndex(null)}
        onIndexChange={setIndex}
        label={dictionary.certificates.preview}
      />
    </>
  );
}

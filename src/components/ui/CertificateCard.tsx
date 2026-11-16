import { Icon } from '@/components/ui/Icons';
import type { Dictionary } from '@/lib/dictionaries';
import { formatDate } from '@/lib/i18n';
import type { Certificate, Locale } from '@/lib/types';

function CertificatePlaceholder({ certificate }: { certificate: Certificate }) {
  return (
    <div className="flex h-full flex-col justify-between rounded-xl border border-accent-line/50 bg-bg/45 p-4">
      <div className="flex items-center justify-between">
        <span className="font-mono text-[8px] uppercase tracking-[0.22em] text-faint">
          Certificate
        </span>
        <Icon name="trophy" size={16} className="text-accent" />
      </div>

      <div className="py-2 text-center">
        <p className="line-clamp-2 text-xs font-semibold leading-snug text-ink">
          {certificate.title}
        </p>
        <p className="mt-1 line-clamp-1 font-mono text-[9px] uppercase tracking-[0.12em] text-muted">
          {certificate.issuer}
        </p>
        <span className="mx-auto mt-3 block h-px w-12 bg-accent/40" aria-hidden="true" />
        <p className="mt-2 font-mono text-[8px] uppercase tracking-[0.18em] text-faint">
          {certificate.issued_at ? certificate.issued_at.slice(0, 4) : 'credential'}
        </p>
      </div>

      <p className="text-center font-mono text-[7px] uppercase tracking-[0.28em] text-faint">
        verified credential
      </p>
    </div>
  );
}

export function CertificateCard({
  certificate,
  locale,
  dictionary,
  onPreview,
}: {
  certificate: Certificate;
  locale: Locale;
  dictionary: Dictionary;
  onPreview?: () => void;
}) {
  return (
    <article className="group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-elevated/70 transition-all duration-300 hover:-translate-y-1 hover:border-accent-line">
      <div className="certificate-art relative aspect-[4/3] overflow-hidden border-b border-line p-3">
        {certificate.image_url ? (
          <img
            src={certificate.image_url}
            alt={`${dictionary.certificates.imageAlt}: ${certificate.title}`}
            loading="lazy"
            className="h-full w-full rounded-xl object-cover"
          />
        ) : (
          <CertificatePlaceholder certificate={certificate} />
        )}
      </div>

      <div className="flex flex-1 flex-col p-5">
        <h3 className="text-base font-semibold leading-snug tracking-tight text-ink">
          {certificate.title}
        </h3>

        <p className="mt-1 text-xs text-muted">
          {dictionary.certificates.issuedBy} {certificate.issuer}
        </p>

        <dl className="mt-4 space-y-1.5 text-[11px] text-faint">
          {certificate.issued_at && (
            <div className="flex items-center gap-1.5">
              <Icon name="calendar" size={12} className="text-accent" />
              <dt className="sr-only">{dictionary.certificates.issuedAt}</dt>
              <dd>{formatDate(certificate.issued_at, locale)}</dd>
            </div>
          )}
          {certificate.credential_id && (
            <div className="flex items-center gap-1.5">
              <Icon name="document" size={12} className="text-accent" />
              <dt className="sr-only">{dictionary.certificates.credential}</dt>
              <dd className="font-mono">{certificate.credential_id}</dd>
            </div>
          )}
        </dl>

        <div className="mt-auto flex flex-wrap items-center gap-3 pt-5">
          {certificate.credential_url && (
            <a
              href={certificate.credential_url}
              target="_blank"
              rel="noreferrer noopener"
              className="inline-flex items-center gap-1.5 text-xs font-medium text-accent transition-opacity hover:opacity-75"
            >
              {dictionary.certificates.viewCredential}
              <Icon name="arrow-up-right" size={12} />
            </a>
          )}

          {certificate.image_url && onPreview && (
            <button
              type="button"
              onClick={onPreview}
              className="inline-flex items-center gap-1.5 text-xs font-medium text-muted transition-colors hover:text-accent"
            >
              {dictionary.certificates.preview}
              <Icon name="search" size={12} />
            </button>
          )}
        </div>
      </div>
    </article>
  );
}

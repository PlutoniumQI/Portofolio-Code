import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { GuestbookForm } from '@/components/forms/GuestbookForm';
import { Avatar } from '@/components/ui/Avatar';
import { EmptyState } from '@/components/ui/EmptyState';
import { Icon } from '@/components/ui/Icons';
import { PageHero, Section } from '@/components/ui/Section';
import { getGuestbook } from '@/lib/api';
import { getDictionary } from '@/lib/dictionaries';
import { formatDate, isLocale, localePath } from '@/lib/i18n';

export const revalidate = 60;

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;

  if (!isLocale(locale)) {
    return {};
  }

  const dictionary = getDictionary(locale);

  return {
    title: dictionary.meta.guestbook.title,
    description: dictionary.meta.guestbook.description,
    alternates: {
      canonical: localePath(locale, '/guestbook'),
      languages: { id: '/id/guestbook', en: '/en/guestbook', 'x-default': '/id/guestbook' },
    },
  };
}

export default async function GuestbookPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale: rawLocale } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale = rawLocale;
  const dictionary = getDictionary(locale);
  const entries = await getGuestbook();

  return (
    <>
      <PageHero
        eyebrow={dictionary.guestbook.eyebrow}
        title={dictionary.guestbook.title}
        description={dictionary.guestbook.description}
      >
        <span className="chip chip-accent">
          {entries.length} {dictionary.guestbook.entriesTitle}
        </span>
      </PageHero>

      <Section>
        <div className="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:gap-12">
          <div>
            <p className="section-eyebrow">{dictionary.guestbook.eyebrow}</p>
            <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink">
              {dictionary.guestbook.formTitle}
            </h2>
            <p className="mt-2 text-sm leading-relaxed text-muted">{dictionary.guestbook.formDesc}</p>

            <div className="mt-5">
              <GuestbookForm dictionary={dictionary} />
            </div>
          </div>

          <div>
            <div className="flex items-center justify-between gap-3">
              <h2 className="text-sm font-semibold uppercase tracking-[0.14em] text-ink">
                {dictionary.guestbook.entriesTitle}
              </h2>
              <span className="inline-flex items-center gap-1.5 text-[10px] text-faint">
                <Icon name="check" size={12} className="text-accent" />
                {dictionary.guestbook.approvalNote}
              </span>
            </div>

            {entries.length === 0 ? (
              <div className="mt-5">
                <EmptyState icon="message" title={dictionary.guestbook.empty} />
              </div>
            ) : (
              <div className="mt-5 space-y-3">
                {entries.map((entry) => (
                  <article
                    key={entry.id}
                    className="rounded-2xl border border-line bg-elevated/60 p-4 transition-colors hover:border-accent-line sm:p-5"
                  >
                    <div className="flex items-center justify-between gap-3">
                      <div className="flex min-w-0 items-center gap-3">
                        <Avatar name={entry.name} size="sm" />
                        <div className="min-w-0">
                          <p className="truncate text-sm font-medium text-ink">{entry.name}</p>
                          {entry.created_at && (
                            <time
                              dateTime={entry.created_at}
                              className="font-mono text-[9px] uppercase tracking-[0.1em] text-faint"
                            >
                              {formatDate(entry.created_at, locale)}
                            </time>
                          )}
                        </div>
                      </div>
                      <Icon name="quote" size={14} className="shrink-0 text-accent/40" />
                    </div>
                    <p className="mt-3 text-sm leading-relaxed text-muted">{entry.message}</p>
                  </article>
                ))}
              </div>
            )}
          </div>
        </div>
      </Section>
    </>
  );
}

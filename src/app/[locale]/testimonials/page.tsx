import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { TestimonialExplorer } from '@/components/explorers/TestimonialExplorer';
import { TestimonialForm } from '@/components/forms/TestimonialForm';
import { Icon } from '@/components/ui/Icons';
import { PageHero, Section } from '@/components/ui/Section';
import { getPortfolio } from '@/lib/api';
import { getDictionary } from '@/lib/dictionaries';
import { isLocale, localePath } from '@/lib/i18n';

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
    title: dictionary.meta.testimonials.title,
    description: dictionary.meta.testimonials.description,
    alternates: {
      canonical: localePath(locale, '/testimonials'),
      languages: {
        id: '/id/testimonials',
        en: '/en/testimonials',
        'x-default': '/id/testimonials',
      },
    },
  };
}

export default async function TestimonialsPage({
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
  const portfolio = await getPortfolio(locale);

  return (
    <>
      <PageHero
        eyebrow={dictionary.testimonials.eyebrow}
        title={dictionary.testimonials.title}
        description={dictionary.testimonials.description}
      >
        <div className="flex flex-wrap gap-2">
          {portfolio.testimonial_categories.map((category) => (
            <span key={category.value} className="chip">
              {dictionary.testimonialCategories[
                category.value as keyof typeof dictionary.testimonialCategories
              ] ?? category.label}
            </span>
          ))}
        </div>
      </PageHero>

      <Section>
        <TestimonialExplorer
          testimonials={portfolio.testimonials}
          dictionary={dictionary}
        />
      </Section>

      <Section id="write" className="border-t border-line">
        <div className="grid gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
          <div className="reveal visible lg:sticky lg:top-24">
            <p className="section-eyebrow">{dictionary.testimonials.write.eyebrow}</p>
            <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink sm:text-3xl">
              {dictionary.testimonials.write.title}
            </h2>
            <p className="mt-3 text-sm leading-relaxed text-muted">
              {dictionary.testimonials.write.description}
            </p>

            <ul className="mt-6 space-y-3">
              {[
                dictionary.testimonials.write.approvalNote,
                dictionary.testimonials.write.privacy,
              ].map((item) => (
                <li key={item} className="flex items-start gap-2 text-sm text-ink-soft">
                  <Icon name="check" size={14} className="mt-0.5 shrink-0 text-accent" />
                  {item}
                </li>
              ))}
            </ul>
          </div>

          <div>
            <p className="section-eyebrow">{dictionary.testimonials.write.formTitle}</p>
            <p className="mt-2 mb-4 text-sm leading-relaxed text-muted">
              {dictionary.testimonials.write.formDesc}
            </p>
            <TestimonialForm dictionary={dictionary} locale={locale} />
          </div>
        </div>
      </Section>
    </>
  );
}

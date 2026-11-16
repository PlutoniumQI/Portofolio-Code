import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { TestimonialExplorer } from '@/components/explorers/TestimonialExplorer';
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
    </>
  );
}

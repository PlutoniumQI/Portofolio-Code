import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { ProjectGallery } from '@/components/explorers/ProjectGallery';
import { Icon } from '@/components/ui/Icons';
import { JsonLd } from '@/components/ui/JsonLd';
import { Prose } from '@/components/ui/Prose';
import { ProjectCard } from '@/components/ui/ProjectCard';
import { ProjectCover } from '@/components/ui/ProjectArtwork';
import { Container, Section, SectionHeading } from '@/components/ui/Section';
import { findProject, getPortfolio, getSiteUrl } from '@/lib/api';
import { getDictionary } from '@/lib/dictionaries';
import { formatDate, isLocale, localePath } from '@/lib/i18n';
import type { Locale } from '@/lib/types';

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string; slug: string }>;
}): Promise<Metadata> {
  const { locale, slug } = await params;

  if (!isLocale(locale)) {
    return {};
  }

  const portfolio = await getPortfolio(locale);
  const project = findProject(portfolio, slug);

  if (!project) {
    // Fail before the streaming shell is flushed, so the response is a real 404.
    notFound();
  }

  const canonical = localePath(locale, `/projects/${project.slug}`);
  const description = project.excerpt ?? project.description ?? project.title;

  return {
    title: project.title,
    description,
    alternates: {
      canonical,
      languages: {
        id: `/id/projects/${project.slug}`,
        en: `/en/projects/${project.slug}`,
        'x-default': `/id/projects/${project.slug}`,
      },
    },
    openGraph: {
      type: 'article',
      url: canonical,
      title: project.title,
      description,
      images: project.image_url ? [{ url: project.image_url }] : undefined,
    },
  };
}

export default async function ProjectDetailPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string }>;
}) {
  const { locale: rawLocale, slug } = await params;

  if (!isLocale(rawLocale)) {
    notFound();
  }

  const locale: Locale = rawLocale;
  const dictionary = getDictionary(locale);
  const portfolio = await getPortfolio(locale);
  const project = findProject(portfolio, slug);

  if (!project) {
    notFound();
  }

  const index = portfolio.projects.findIndex((item) => item.id === project.id);
  const related = portfolio.projects.filter((item) => item.id !== project.id).slice(0, 3);
  const gallery = project.gallery ?? [];

  return (
    <>
      <JsonLd
        data={{
          '@context': 'https://schema.org',
          '@type': 'CreativeWork',
          name: project.title,
          description: project.excerpt ?? undefined,
          url: `${getSiteUrl()}${localePath(locale, `/projects/${project.slug}`)}`,
          image: project.image_url ?? undefined,
          dateCreated: project.created_at ?? undefined,
          genre: project.category_label ?? project.category ?? undefined,
          keywords: project.tags.join(', '),
          author: { '@type': 'Person', name: portfolio.profile.full_name },
        }}
      />

      <div className="border-b border-line">
        <Container className="flex items-center justify-between gap-4 py-5">
          <Link
            href={localePath(locale, '/projects')}
            className="inline-flex items-center gap-2 text-xs text-muted transition-colors hover:text-accent"
          >
            <Icon name="arrow-left" size={14} />
            {dictionary.common.backToProjects}
          </Link>
          <span className="font-mono text-[10px] uppercase tracking-[0.16em] text-faint">
            {String(index + 1).padStart(2, '0')} / {String(portfolio.projects.length).padStart(2, '0')}
          </span>
        </Container>
      </div>

      <Container className="py-10 sm:py-14">
        <div className="max-w-3xl">
          <div className="flex flex-wrap items-center gap-2">
            {project.category_label && <span className="chip chip-accent">{project.category_label}</span>}
            {project.is_featured && <span className="chip">{dictionary.common.featured}</span>}
            {project.status === 'coming_soon' && (
              <span className="chip">{dictionary.common.comingSoon}</span>
            )}
          </div>

          <h1 className="mt-5 text-3xl font-semibold leading-[1.08] tracking-tight text-ink sm:text-5xl">
            {project.title}
          </h1>

          {project.excerpt && (
            <p className="mt-5 text-sm leading-relaxed text-muted sm:text-base">{project.excerpt}</p>
          )}

          <div className="mt-6 flex flex-wrap gap-1.5">
            {project.tags.map((tag) => (
              <span key={tag} className="chip">
                {tag}
              </span>
            ))}
          </div>

          {(project.url || project.repo_url) && (
            <div className="mt-7 flex flex-wrap gap-3">
              {project.url && (
                <a href={project.url} target="_blank" rel="noreferrer noopener" className="btn-primary">
                  {dictionary.common.visitSite}
                  <Icon name="external" size={14} />
                </a>
              )}
              {project.repo_url && (
                <a href={project.repo_url} target="_blank" rel="noreferrer noopener" className="btn-ghost">
                  <Icon name="github" size={14} />
                  {dictionary.common.viewRepository}
                </a>
              )}
            </div>
          )}
        </div>

        <div className="relative mt-10 aspect-[16/9] overflow-hidden rounded-2xl border border-line sm:rounded-3xl">
          <ProjectCover project={project} index={Math.max(index, 0)} />
        </div>

        <div className="mt-10 grid gap-10 lg:grid-cols-[1fr_310px] lg:gap-14">
          <article className="min-w-0">
            <h2 className="flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.14em] text-ink">
              <Icon name="sparkles" size={15} className="text-accent" />
              {dictionary.projects.process}
            </h2>

            {project.content ? (
              <Prose html={project.content} className="mt-5" />
            ) : (
              <p className="mt-5 rounded-2xl border border-dashed border-line-strong bg-elevated/40 p-6 text-sm text-muted">
                {dictionary.projects.noContent}
              </p>
            )}

            {gallery.length > 0 && (
              <div className="mt-10">
                <h2 className="flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.14em] text-ink">
                  <Icon name="grid" size={15} className="text-accent" />
                  {dictionary.projects.gallery}
                </h2>
                <div className="mt-5">
                  <ProjectGallery
                    images={gallery}
                    title={project.title}
                    label={dictionary.projects.gallery}
                  />
                </div>
              </div>
            )}
          </article>

          <aside className="lg:sticky lg:top-24 lg:self-start">
            <div className="rounded-2xl border border-line bg-elevated/60 p-5">
              <h2 className="font-mono text-[10px] uppercase tracking-[0.18em] text-faint">
                {dictionary.projects.projectInfo}
              </h2>

              <dl className="mt-4 space-y-3 text-xs">
                {project.category_label && (
                  <div className="flex items-center justify-between gap-3 border-b border-line pb-3">
                    <dt className="text-faint">{dictionary.common.category}</dt>
                    <dd className="text-right text-ink-soft">{project.category_label}</dd>
                  </div>
                )}
                <div className="flex items-center justify-between gap-3 border-b border-line pb-3">
                  <dt className="text-faint">{dictionary.common.status}</dt>
                  <dd className="text-right text-ink-soft">
                    {project.status === 'coming_soon'
                      ? dictionary.common.comingSoon
                      : dictionary.common.published}
                  </dd>
                </div>
                {project.created_at && (
                  <div className="flex items-center justify-between gap-3 border-b border-line pb-3">
                    <dt className="text-faint">{dictionary.common.date}</dt>
                    <dd className="text-right text-ink-soft">
                      {formatDate(project.created_at, locale)}
                    </dd>
                  </div>
                )}
                {project.tags.length > 0 && (
                  <div className="border-b border-line pb-3">
                    <dt className="text-faint">{dictionary.common.tags}</dt>
                    <dd className="mt-2 flex flex-wrap gap-1.5">
                      {project.tags.map((tag) => (
                        <span key={tag} className="chip">
                          {tag}
                        </span>
                      ))}
                    </dd>
                  </div>
                )}
              </dl>

              <div className="mt-4 space-y-2">
                {project.url && (
                  <a
                    href={project.url}
                    target="_blank"
                    rel="noreferrer noopener"
                    className="flex items-center justify-between rounded-lg border border-line px-3 py-2 text-xs text-ink-soft transition-colors hover:border-accent-line hover:text-accent"
                  >
                    {dictionary.common.visitSite}
                    <Icon name="external" size={13} />
                  </a>
                )}
                {project.repo_url && (
                  <a
                    href={project.repo_url}
                    target="_blank"
                    rel="noreferrer noopener"
                    className="flex items-center justify-between rounded-lg border border-line px-3 py-2 text-xs text-ink-soft transition-colors hover:border-accent-line hover:text-accent"
                  >
                    {dictionary.common.viewRepository}
                    <Icon name="github" size={13} />
                  </a>
                )}
                <Link
                  href={localePath(locale, '/contact')}
                  className="flex items-center justify-between rounded-lg border border-accent-line bg-accent-soft px-3 py-2 text-xs text-accent transition-colors hover:bg-accent hover:text-on-accent"
                >
                  {dictionary.common.contactMe}
                  <Icon name="send" size={13} />
                </Link>
              </div>
            </div>
          </aside>
        </div>
      </Container>

      {related.length > 0 && (
        <Section className="border-t border-line bg-elevated/20">
          <SectionHeading
            eyebrow={dictionary.projects.relatedTitle}
            title={dictionary.projects.relatedTitle}
            description={dictionary.projects.relatedDesc}
            action={
              <Link href={localePath(locale, '/projects')} className="btn-ghost">
                {dictionary.common.viewAll}
                <Icon name="arrow-right" size={14} />
              </Link>
            }
          />
          <div className="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {related.map((item, itemIndex) => (
              <ProjectCard
                key={item.id}
                project={item}
                locale={locale}
                dictionary={dictionary}
                index={itemIndex}
              />
            ))}
          </div>
        </Section>
      )}
    </>
  );
}

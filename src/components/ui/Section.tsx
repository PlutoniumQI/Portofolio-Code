import type { ReactNode } from 'react';

export function Container({ children, className = '' }: { children: ReactNode; className?: string }) {
  return <div className={`mx-auto w-full max-w-6xl px-5 sm:px-8 ${className}`}>{children}</div>;
}

export function Section({
  children,
  className = '',
  id,
}: {
  children: ReactNode;
  className?: string;
  id?: string;
}) {
  return (
    <section id={id} className={`py-16 sm:py-20 ${className}`}>
      <Container>{children}</Container>
    </section>
  );
}

export function SectionHeading({
  eyebrow,
  title,
  description,
  action,
  align = 'left',
}: {
  eyebrow: string;
  title: ReactNode;
  description?: string;
  action?: ReactNode;
  align?: 'left' | 'center';
}) {
  return (
    <div
      className={`reveal flex flex-col gap-5 ${
        align === 'center'
          ? 'items-center text-center'
          : 'sm:flex-row sm:items-end sm:justify-between'
      }`}
    >
      <div className={`max-w-2xl ${align === 'center' ? 'mx-auto' : ''}`}>
        <p className="section-eyebrow">{eyebrow}</p>
        <h2 className="mt-3 text-2xl font-semibold tracking-tight text-ink sm:text-3xl lg:text-[2.1rem]">
          {title}
        </h2>
        {description && (
          <p className="mt-3 text-sm leading-relaxed text-muted sm:text-[0.95rem]">{description}</p>
        )}
      </div>
      {action && <div className="shrink-0">{action}</div>}
    </div>
  );
}

export function PageHero({
  eyebrow,
  title,
  description,
  children,
}: {
  eyebrow: string;
  title: ReactNode;
  description?: string;
  children?: ReactNode;
}) {
  return (
    <section className="relative overflow-hidden border-b border-line">
      <div className="hero-orb -right-52 -top-52 opacity-80" aria-hidden="true" />
      <Container className="relative py-14 sm:py-20">
        <p className="section-eyebrow">{eyebrow}</p>
        <h1 className="mt-5 max-w-4xl text-3xl font-semibold leading-[1.1] tracking-tight text-ink sm:text-5xl lg:text-[3.4rem]">
          {title}
        </h1>
        {description && (
          <p className="mt-5 max-w-2xl text-sm leading-relaxed text-muted sm:text-base">
            {description}
          </p>
        )}
        {children && <div className="mt-8">{children}</div>}
      </Container>
    </section>
  );
}

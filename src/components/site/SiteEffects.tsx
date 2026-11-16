'use client';

import { useEffect } from 'react';
import { usePathname } from 'next/navigation';

/**
 * Imperative site effects: reveal-on-scroll, cursor glow, and the radial
 * highlight on project cards. Kept in one client component so server pages
 * stay static.
 */
export function SiteEffects() {
  const pathname = usePathname();

  useEffect(() => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const observer = new IntersectionObserver(
      (entries) => {
        for (const entry of entries) {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        }
      },
      { threshold: 0.08, rootMargin: '0px 0px -48px 0px' },
    );

    const revealElements = document.querySelectorAll('.reveal:not(.visible)');

    if (reduceMotion) {
      revealElements.forEach((element) => element.classList.add('visible'));
    } else {
      revealElements.forEach((element) => observer.observe(element));
    }

    const glow = document.getElementById('glow');
    const finePointer = window.matchMedia('(pointer: fine)').matches;

    const onPointerMove = (event: PointerEvent) => {
      if (glow) {
        glow.style.left = `${event.clientX}px`;
        glow.style.top = `${event.clientY}px`;
      }
    };

    if (glow && finePointer && !reduceMotion) {
      window.addEventListener('pointermove', onPointerMove, { passive: true });
    }

    const cards = Array.from(document.querySelectorAll<HTMLElement>('.project-card-glow'));

    const cardHandlers = cards.map((card) => {
      const onMove = (event: PointerEvent) => {
        const rect = card.getBoundingClientRect();

        card.style.setProperty('--mouse-x', `${event.clientX - rect.left}px`);
        card.style.setProperty('--mouse-y', `${event.clientY - rect.top}px`);
      };

      card.addEventListener('pointermove', onMove);

      return { card, onMove };
    });

    return () => {
      observer.disconnect();
      window.removeEventListener('pointermove', onPointerMove);

      for (const { card, onMove } of cardHandlers) {
        card.removeEventListener('pointermove', onMove);
      }
    };
  }, [pathname]);

  return null;
}

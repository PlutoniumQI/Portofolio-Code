'use client';

import { useEffect } from 'react';

export default function ClientEffects() {
  useEffect(() => {
    // ── Navbar scroll effect ──────────────────────────────
    const navbar = document.getElementById('navbar');
    const handleNavScroll = () => {
      if (!navbar) return;
      if (window.scrollY > 20) {
        navbar.classList.add('bg-zinc-950/80', 'backdrop-blur-md', 'border-b', 'border-zinc-900/50');
      } else {
        navbar.classList.remove('bg-zinc-950/80', 'backdrop-blur-md', 'border-b', 'border-zinc-900/50');
      }
    };
    window.addEventListener('scroll', handleNavScroll, { passive: true });

    // ── Mobile menu toggle ────────────────────────────────
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const handleMenuToggle = () => {
      if (!menuToggle || !mobileMenu) return;
      menuToggle.classList.toggle('active');
      mobileMenu.classList.toggle('open');
      document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
    };
    menuToggle?.addEventListener('click', handleMenuToggle);

    // Close mobile menu on link click
    mobileMenu?.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        menuToggle?.classList.remove('active');
        mobileMenu?.classList.remove('open');
        document.body.style.overflow = '';
      });
    });

    // ── Scroll reveal with Intersection Observer ──────────
    const reveals = document.querySelectorAll<HTMLElement>('.reveal');
    const revealObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1 }
    );
    reveals.forEach((el) => revealObserver.observe(el));

    // Trigger hero immediately
    setTimeout(() => {
      document.querySelectorAll('#hero .reveal').forEach((el) => {
        el.classList.add('visible');
      });
    }, 300);

    // ── Cursor glow ───────────────────────────────────────
    const glow = document.getElementById('glow');
    const handleMouseMove = (e: MouseEvent) => {
      if (!glow) return;
      glow.style.left = e.clientX + 'px';
      glow.style.top = e.clientY + 'px';
    };
    document.addEventListener('mousemove', handleMouseMove);

    // ── Typewriter ────────────────────────────────────────
    const phrases = [
      'UI/UX Designer.',
      'Front-end Developer.',
      'Creating better web experiences.',
    ];
    let pi = 0, ci = 0, deleting = false;
    let twTimer: ReturnType<typeof setTimeout>;
    const tw = document.getElementById('tw');

    function typewrite() {
      if (!tw) return;
      const phrase = phrases[pi];
      if (!deleting) {
        tw.textContent = phrase.slice(0, ++ci);
        if (ci === phrase.length) {
          deleting = true;
          twTimer = setTimeout(typewrite, 1800);
          return;
        }
      } else {
        tw.textContent = phrase.slice(0, --ci);
        if (ci === 0) {
          deleting = false;
          pi = (pi + 1) % phrases.length;
        }
      }
      twTimer = setTimeout(typewrite, deleting ? 45 : 85);
    }
    twTimer = setTimeout(typewrite, 800);

    // ── Active nav highlight on scroll ────────────────────
    const navSections = ['about', 'skills', 'experience', 'projects', 'contact'];
    const handleActiveNav = () => {
      navSections.forEach((id) => {
        const sec = document.getElementById(id);
        const link = document.querySelector<HTMLElement>(`nav a[href="#${id}"]`);
        if (!sec || !link) return;
        const r = sec.getBoundingClientRect();
        if (r.top <= 100 && r.bottom > 100) {
          link.classList.remove('text-zinc-400');
          link.classList.add('text-emerald-400');
        } else {
          link.classList.remove('text-emerald-400');
          link.classList.add('text-zinc-400');
        }
      });
    };
    window.addEventListener('scroll', handleActiveNav, { passive: true });

    // ── Counter animation ─────────────────────────────────
    const counters = document.querySelectorAll<HTMLElement>('.counter');
    const counterObs = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const el = entry.target as HTMLElement;
          const target = +(el.dataset.target || 0);
          let n = 0;
          const tick = () => {
            el.textContent = ++n + '+';
            if (n < target) setTimeout(tick, 150);
          };
          tick();
          counterObs.unobserve(el);
        });
      },
      { threshold: 0.6 }
    );
    counters.forEach((c) => counterObs.observe(c));

    // ── Project card glow tracking ────────────────────────
    const cards = document.querySelectorAll<HTMLElement>('.project-card-glow');
    const cardHandlers: Array<(e: MouseEvent) => void> = [];
    cards.forEach((card) => {
      const handler = (e: MouseEvent) => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--mouse-x', e.clientX - rect.left + 'px');
        card.style.setProperty('--mouse-y', e.clientY - rect.top + 'px');
      };
      cardHandlers.push(handler);
      card.addEventListener('mousemove', handler);
    });

    // ── Back to top button ────────────────────────────────
    const backToTop = document.getElementById('backToTop');
    const handleBackToTop = () => {
      if (!backToTop) return;
      if (window.scrollY > 600) {
        backToTop.classList.add('show');
      } else {
        backToTop.classList.remove('show');
      }
    };
    window.addEventListener('scroll', handleBackToTop, { passive: true });
    backToTop?.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ── Live clock (WITA UTC+8) ───────────────────────────
    function updateClock() {
      const now = new Date();
      const wita = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Makassar' }));
      const h = String(wita.getHours()).padStart(2, '0');
      const m = String(wita.getMinutes()).padStart(2, '0');
      const s = String(wita.getSeconds()).padStart(2, '0');
      const clockEl = document.getElementById('liveClock');
      if (clockEl) clockEl.textContent = `${h}:${m}:${s}`;
    }
    updateClock();
    const clockInterval = setInterval(updateClock, 1000);

    // ── Cleanup ───────────────────────────────────────────
    return () => {
      window.removeEventListener('scroll', handleNavScroll);
      window.removeEventListener('scroll', handleActiveNav);
      window.removeEventListener('scroll', handleBackToTop);
      document.removeEventListener('mousemove', handleMouseMove);
      menuToggle?.removeEventListener('click', handleMenuToggle);
      clearTimeout(twTimer);
      clearInterval(clockInterval);
      revealObserver.disconnect();
      counterObs.disconnect();
      cards.forEach((card, i) => card.removeEventListener('mousemove', cardHandlers[i]));
    };
  }, []);

  return null;
}

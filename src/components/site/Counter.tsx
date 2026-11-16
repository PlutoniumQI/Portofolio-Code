'use client';

import { useEffect, useRef, useState } from 'react';

export function Counter({
  value,
  suffix = '',
  className,
}: {
  value: number;
  suffix?: string;
  className?: string;
}) {
  const ref = useRef<HTMLSpanElement>(null);
  const [display, setDisplay] = useState(value);

  useEffect(() => {
    const element = ref.current;

    if (!element) {
      return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    let frame = 0;
    let started = false;

    const animate = () => {
      const start = performance.now();
      const duration = 1200;

      const step = (now: number) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);

        setDisplay(Math.round(value * eased));

        if (progress < 1) {
          frame = window.requestAnimationFrame(step);
        }
      };

      frame = window.requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0]?.isIntersecting && !started) {
          started = true;
          setDisplay(0);
          animate();
          observer.disconnect();
        }
      },
      { threshold: 0.4 },
    );

    observer.observe(element);

    return () => {
      observer.disconnect();
      window.cancelAnimationFrame(frame);
    };
  }, [value]);

  return (
    <span ref={ref} className={className}>
      <span className="counter tabular-nums">{display}</span>
      {suffix}
    </span>
  );
}

'use client';

import { useEffect, useState } from 'react';

export function Typewriter({ words }: { words: string[] }) {
  const initialWord = words[0] ?? '';
  const [text, setText] = useState(initialWord);

  useEffect(() => {
    if (words.length === 0 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    let wordIndex = 0;
    let charIndex = words[0].length;
    let deleting = false;
    let timeout = 0;

    const tick = () => {
      const word = words[wordIndex % words.length];

      if (!deleting) {
        charIndex += 1;
        setText(word.slice(0, charIndex));

        if (charIndex >= word.length) {
          deleting = true;
          timeout = window.setTimeout(tick, 2100);

          return;
        }

        timeout = window.setTimeout(tick, 68);

        return;
      }

      charIndex -= 1;
      setText(word.slice(0, Math.max(charIndex, 0)));

      if (charIndex <= 0) {
        deleting = false;
        wordIndex = (wordIndex + 1) % words.length;
        timeout = window.setTimeout(tick, 380);

        return;
      }

      timeout = window.setTimeout(tick, 32);
    };

    timeout = window.setTimeout(tick, 2100);

    return () => window.clearTimeout(timeout);
  }, [words]);

  return (
    <span id="tw">
      <span aria-hidden="true">{text}</span>
      <span className="sr-only">{words[0] ?? ''}</span>
    </span>
  );
}

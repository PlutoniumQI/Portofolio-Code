'use client';

import { Icon } from '@/components/ui/Icons';

export function PrintButton({ label }: { label: string }) {
  return (
    <button type="button" onClick={() => window.print()} className="btn-primary no-print">
      <Icon name="printer" size={15} />
      {label}
    </button>
  );
}

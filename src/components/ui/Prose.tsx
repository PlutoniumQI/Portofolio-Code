export function Prose({ html, className = '' }: { html: string; className?: string }) {
  return <div className={`prose-site ${className}`} dangerouslySetInnerHTML={{ __html: html }} />;
}

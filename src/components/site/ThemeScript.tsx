export function ThemeScript() {
  const script = `(function(){try{var stored=localStorage.getItem('theme');var dark=stored?stored==='dark':true;var root=document.documentElement;root.classList.toggle('dark',dark);root.classList.add('js');}catch(e){var root=document.documentElement;root.classList.add('dark','js');}})();`;

  return <script dangerouslySetInnerHTML={{ __html: script }} />;
}

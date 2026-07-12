import { getProjects } from '@/lib/contentful';
import ClientEffects from '@/components/ClientEffects';

// ─── Static data (Skills & Experience) ───────────────────────────────────────
// These were previously DB-driven in Laravel. Expand to Contentful later if needed.

const skills = [
  {
    id: '01',
    title: 'UI/UX Design',
    subtitle: 'Crafting intuitive user interfaces and seamless experiences.',
    icon: (
      <svg className="w-5 h-5 text-emerald-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
      </svg>
    ),
  },
  {
    id: '02',
    title: 'Figma',
    subtitle: 'Prototyping, components, and collaborative design systems.',
    icon: (
      <svg className="w-5 h-5 text-emerald-400/70" fill="currentColor" viewBox="0 0 24 24">
        <path d="M8 24c2.208 0 4-1.792 4-4v-4H8c-2.208 0-4 1.792-4 4s1.792 4 4 4zm0-20H4a4 4 0 000 8h4V4zm0 0h4a4 4 0 000-8H8v8zm0 8h4a4 4 0 010 8H8v-8zm8-4a4 4 0 100-8 4 4 0 000 8z" />
      </svg>
    ),
  },
  {
    id: '03',
    title: 'Front-end Dev',
    subtitle: 'Building responsive interfaces with modern web technologies.',
    icon: (
      <svg className="w-5 h-5 text-emerald-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
      </svg>
    ),
  },
  {
    id: '04',
    title: 'Tailwind CSS',
    subtitle: 'Utility-first styling for fast, consistent UI development.',
    icon: (
      <svg className="w-5 h-5 text-emerald-400/70" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 6C9.33 6 7.67 7.33 7 10c1-1.33 2.17-1.83 3.5-1.5.76.19 1.31.74 1.91 1.35C13.41 10.85 14.73 12 17 12c2.67 0 4.33-1.33 5-4-1 1.33-2.17 1.83-3.5 1.5-.76-.19-1.3-.74-1.91-1.35C15.59 7.15 14.27 6 12 6zM7 12c-2.67 0-4.33 1.33-5 4 1-1.33 2.17-1.83 3.5-1.5.76.19 1.3.74 1.91 1.35C8.41 16.85 9.73 18 12 18c2.67 0 4.33-1.33 5-4-1 1.33-2.17 1.83-3.5 1.5-.76-.19-1.3-.74-1.91-1.35C10.59 13.15 9.27 12 7 12z" />
      </svg>
    ),
  },
  {
    id: '05',
    title: 'Next.js',
    subtitle: 'Full-stack React framework for production-grade web apps.',
    icon: (
      <svg className="w-5 h-5 text-emerald-400/70" fill="currentColor" viewBox="0 0 24 24">
        <path d="M11.572 0c-.176 0-.31.001-.358.007a19.76 19.76 0 01-.364.033C7.443.346 4.25 2.185 2.228 5.012a11.875 11.875 0 00-2.119 5.243c-.096.659-.108.854-.108 1.747s.012 1.089.108 1.748c.652 4.506 3.86 8.292 8.209 9.695.779.25 1.6.422 2.534.525.363.04 1.935.04 2.299 0 1.611-.178 2.977-.577 4.323-1.264.207-.106.247-.134.219-.158-.02-.013-.9-1.193-1.955-2.62l-1.919-2.592-2.404-3.558a338.739 338.739 0 00-2.422-3.556c-.009-.002-.018 1.579-.023 3.51-.007 3.38-.01 3.515-.052 3.595a.426.426 0 01-.206.214c-.075.037-.14.044-.495.044H7.81l-.108-.068a.438.438 0 01-.157-.171l-.05-.106.006-4.703.007-4.705.072-.092a.645.645 0 01.174-.143c.096-.047.134-.051.54-.051.478 0 .558.018.682.154.035.038 1.337 1.999 2.895 4.361a10760.433 10760.433 0 004.735 7.17l1.9 2.879.096-.063a12.317 12.317 0 002.466-2.163 11.944 11.944 0 002.824-6.134c.096-.66.108-.854.108-1.748 0-.893-.012-1.088-.108-1.747-.652-4.506-3.859-8.292-8.208-9.695a12.597 12.597 0 00-2.499-.523A33.119 33.119 0 0011.573 0zm4.069 7.217c.347 0 .408.005.486.047a.473.473 0 01.237.277c.018.06.023 1.365.018 4.304l-.006 4.218-.744-1.14-.746-1.14v-3.066c0-1.982.01-3.097.023-3.15a.478.478 0 01.233-.296c.096-.05.13-.054.5-.054z"/>
      </svg>
    ),
  },
  {
    id: '06',
    title: 'Design Systems',
    subtitle: 'Building consistent, scalable component libraries and patterns.',
    icon: (
      <svg className="w-5 h-5 text-emerald-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
      </svg>
    ),
  },
];

const experiences = [
  {
    id: '1',
    dateRange: '2024',
    title: 'UI/UX Design Student',
    company: 'SMKN 2 Banjarmasin',
    description:
      'Studying software engineering and UI/UX design fundamentals, building projects using Figma, Tailwind CSS, and Laravel.',
    tags: ['Figma', 'Tailwind CSS', 'Laravel', 'UI/UX'],
  },
];


const marqueeItems = [
  'UI/UX Design', 'Front-end Dev', 'Figma', 'Next.js', 'Tailwind CSS', 'Design Systems', 'Prototyping',
];
const allMarqueeItems = [...marqueeItems, ...marqueeItems];

// Enable automatic background revalidation of the static page (ISR) every 60 seconds
export const revalidate = 60;

export default async function PortfolioPage() {
  // Fetch projects on the server before the page renders
  const projects = await getProjects();

  const dynamicStats = [
    { target: 1, label: 'Year Learning' },
    { target: projects.length, label: 'Projects Built' },
    { target: 5, label: 'Tools Mastered' },
  ];

  return (
    <>
      {/* Client-side interactive effects */}
      <ClientEffects />

      {/* Cursor glow */}
      <div id="glow" />

      {/* ─── NAVBAR ───────────────────────────────────────────────────── */}
      <header id="navbar" className="fixed top-0 inset-x-0 z-50 transition-all duration-500">
        <div className="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
          <a href="#hero" className="text-white font-semibold tracking-widest text-sm uppercase relative z-50">
            re:<span className="text-emerald-400">SKY</span>
          </a>

          {/* Desktop Nav */}
          <nav className="hidden md:flex items-center gap-8">
            {['about', 'skills', 'experience', 'projects', 'contact'].map((section) => (
              <a
                key={section}
                href={`#${section}`}
                className="nav-link text-xs uppercase tracking-widest text-zinc-400 hover:text-white transition-colors duration-300"
              >
                {section === 'projects' ? 'Work' : section.charAt(0).toUpperCase() + section.slice(1)}
              </a>
            ))}
          </nav>

          {/* Mobile Hamburger */}
          <button
            id="menuToggle"
            className="md:hidden relative z-50 flex flex-col items-center justify-center w-10 h-10 gap-1.5 group"
            aria-label="Toggle menu"
          >
            <span className="hamburger-line w-6 h-px bg-zinc-300 transition-all duration-300 origin-center group-[.active]:rotate-45 group-[.active]:translate-y-[3.5px]" />
            <span className="hamburger-line w-6 h-px bg-zinc-300 transition-all duration-300 group-[.active]:opacity-0" />
            <span className="hamburger-line w-6 h-px bg-zinc-300 transition-all duration-300 origin-center group-[.active]:-rotate-45 group-[.active]:-translate-y-[3.5px]" />
          </button>
        </div>
      </header>

      {/* ─── MOBILE MENU OVERLAY ──────────────────────────────────────── */}
      <div
        id="mobileMenu"
        className="fixed inset-0 z-40 bg-zinc-950/95 backdrop-blur-xl opacity-0 invisible flex flex-col items-center justify-center gap-8"
      >
        {[
          { href: '#about', label: 'About', delay: '0ms' },
          { href: '#skills', label: 'Skills', delay: '50ms' },
          { href: '#experience', label: 'Experience', delay: '100ms' },
          { href: '#projects', label: 'Work', delay: '150ms' },
          { href: '#contact', label: 'Contact', delay: '200ms' },
        ].map(({ href, label, delay }) => (
          <a
            key={href}
            href={href}
            className="mobile-nav-link text-2xl font-light text-zinc-300 hover:text-emerald-400 tracking-widest uppercase transition-colors duration-300 translate-y-4 opacity-0"
            style={{ transitionDelay: delay }}
          >
            {label}
          </a>
        ))}
      </div>

      {/* ─── HERO ──────────────────────────────────────────────────────── */}
      <section id="hero" className="min-h-screen flex flex-col justify-center px-6 max-w-6xl mx-auto relative overflow-hidden">
        {/* Animated gradient orb */}
        <div className="hero-orb right-[10%] top-[30%]" />

        {/* Floating decorative elements */}
        <div className="fl absolute right-16 top-1/4 w-40 h-40 border border-zinc-800/50 rounded-full opacity-20 pointer-events-none" />
        <div className="fl2 absolute right-32 top-1/2 w-6 h-6 bg-emerald-500/10 rounded-full opacity-40 pointer-events-none" />
        <div className="fl3 absolute right-48 top-[60%] w-3 h-3 bg-emerald-400/20 rounded-full pointer-events-none" />
        <div className="fl absolute left-[10%] top-[20%] w-24 h-24 border border-zinc-800/30 rounded-full opacity-10 pointer-events-none" />

        <div className="reveal relative z-10">
          <div className="flex items-center gap-3 mb-6">
            <div className="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent" />
            <p className="text-xs uppercase tracking-[0.25em] text-zinc-500">Student of SMKN 2 Banjarmasin</p>
          </div>
          <h1 className="text-5xl md:text-7xl lg:text-8xl font-semibold text-white leading-none tracking-tight mb-8">
            Muhammad Resky Ramadhan<span className="text-emerald-400">.</span>
          </h1>
          <p className="text-lg md:text-xl text-zinc-400 max-w-xl leading-relaxed mb-12">
            <span id="tw" />
          </p>
          <div className="flex items-center gap-6">
            <a
              href="#projects"
              className="group inline-flex items-center gap-2 text-sm text-white border border-zinc-700 hover:border-emerald-500/50 px-6 py-3 rounded-full transition-all duration-300 hover:gap-3 hover:shadow-[0_0_20px_rgba(16,185,129,.1)]"
            >
              View Work
              <svg xmlns="http://www.w3.org/2000/svg" className="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </a>
            <a
              href="#contact"
              className="text-sm text-zinc-500 hover:text-emerald-400 transition-colors duration-300 underline underline-offset-4 decoration-zinc-700 hover:decoration-emerald-500/50"
            >
              Get in touch
            </a>
          </div>
        </div>

        {/* Scroll hint */}
        <div className="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-3">
          <span className="text-[10px] uppercase tracking-[.3em] text-zinc-600">Scroll</span>
          <div className="scroll-indicator flex flex-col items-center">
            <div className="w-5 h-8 border border-zinc-700 rounded-full flex justify-center pt-1.5">
              <div className="w-1 h-2 bg-emerald-400/60 rounded-full" />
            </div>
          </div>
        </div>
      </section>

      {/* ─── MARQUEE ─────────────────────────────────────────────────── */}
      <div className="border-y border-zinc-900/80 py-4 overflow-hidden relative">
        <div className="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-zinc-950 to-transparent z-10 pointer-events-none" />
        <div className="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-zinc-950 to-transparent z-10 pointer-events-none" />
        <div className="mq flex gap-10 whitespace-nowrap w-max">
          {allMarqueeItems.map((item, i) => (
            <span key={`${item}-${i}`} className="flex items-center gap-10">
              <span className="text-xs tracking-[.25em] text-zinc-700 uppercase">{item}</span>
              <span className="text-emerald-500/30 px-3">✦</span>
            </span>
          ))}
        </div>
      </div>

      {/* ─── ABOUT ──────────────────────────────────────────────────── */}
      <section id="about" className="py-32 px-6 max-w-6xl mx-auto">
        <div className="grid md:grid-cols-2 gap-20 items-start">
          {/* Left col */}
          <div className="reveal">
            {/* Profile photo with gradient ring */}
            <div className="gradient-border-ring w-28 h-28 mb-6">
              <div className="w-full h-full rounded-full overflow-hidden bg-zinc-900">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src="https://ui-avatars.com/api/?name=Resky+Ramadhan&background=18181b&color=10b981&size=512&bold=true"
                  alt="Muhammad Resky Ramadhan"
                  className="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500"
                />
              </div>
            </div>

            <div className="flex items-center gap-3 mb-4">
              <div className="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent" />
              <p className="text-xs uppercase tracking-[0.25em] text-zinc-500">About</p>
            </div>
            <h2 className="text-4xl md:text-5xl font-semibold text-white leading-tight">
              Designing with purpose,<br />
              <span className="bg-gradient-to-r from-zinc-400 to-zinc-600 bg-clip-text text-transparent">
                building with precision.
              </span>
            </h2>

            <div className="mt-10 flex items-center gap-3">
              <div className="h-px bg-gradient-to-r from-emerald-500/40 to-zinc-800 w-16" />
              <div className="w-1.5 h-1.5 rounded-full bg-emerald-500/50" />
            </div>

            <dl className="mt-8 space-y-4">
              <div className="flex items-start gap-5 group">
                <dt className="text-[10px] uppercase tracking-widest text-zinc-600 w-20 shrink-0 pt-0.5 group-hover:text-emerald-400/60 transition-colors duration-300">
                  School
                </dt>
                <dd className="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors duration-300">
                  SMKN 2 Banjarmasin
                </dd>
              </div>
              <div className="flex items-start gap-5 group">
                <dt className="text-[10px] uppercase tracking-widest text-zinc-600 w-20 shrink-0 pt-1 group-hover:text-emerald-400/60 transition-colors duration-300">
                  Language
                </dt>
                <dd className="flex flex-wrap gap-2">
                  {['Indonesian', 'English'].map((lang) => (
                    <span
                      key={lang}
                      className="text-xs text-zinc-400 border border-zinc-800 px-3 py-1 rounded-full cursor-default hover:border-emerald-500/30 hover:text-emerald-400/80 transition-all duration-300"
                    >
                      {lang}
                    </span>
                  ))}
                </dd>
              </div>
              <div className="flex items-start gap-5 group">
                <dt className="text-[10px] uppercase tracking-widest text-zinc-600 w-20 shrink-0 pt-0.5 group-hover:text-emerald-400/60 transition-colors duration-300">
                  Based in
                </dt>
                <dd className="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors duration-300 flex items-center gap-2">
                  <span className="w-1.5 h-1.5 rounded-full bg-emerald-500/50 inline-block shrink-0" />
                  Banjarmasin, Indonesia
                </dd>
              </div>
            </dl>
          </div>

          {/* Right col */}
          <div className="reveal space-y-7" style={{ transitionDelay: '150ms' }}>
            <div className="relative glass-card rounded-2xl p-6">
              <div className="absolute -top-2.5 left-5 text-5xl leading-none text-emerald-500/30 select-none font-serif">
                &ldquo;
              </div>
              <p className="text-zinc-300 italic text-sm leading-relaxed pt-3">
                You&apos;re living a different night, yet you are in the same season.
              </p>
            </div>

            <p className="text-zinc-300 leading-relaxed">
              I&apos;m a <span className="text-emerald-400 font-medium">UI/UX Designer</span> and{' '}
              <span className="text-emerald-400 font-medium">Front-end Developer</span> based in Indonesia,
              passionate about minimal, purposeful interfaces.
            </p>
            <p className="text-zinc-500 text-sm leading-relaxed">
              I love making things that help people and solve real problems. My goal is to create experiences that
              are not only beautiful but also intuitive and accessible.
            </p>

            <div className="inline-flex items-center gap-2 glass-card rounded-full px-4 py-2">
              <span className="relative flex h-2 w-2">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-40" />
                <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500/70" />
              </span>
              <span className="text-xs text-zinc-400 uppercase tracking-widest">Open to learning</span>
            </div>
          </div>
        </div>

        {/* Stats */}
        <div className="reveal mt-20">
          <div className="grid grid-cols-3 divide-x divide-zinc-800/50 rounded-2xl glass-card overflow-hidden">
            {dynamicStats.map((stat, i) => (
              <div
                key={stat.label}
                className="flex flex-col items-center justify-center py-10 gap-1 hover:bg-emerald-500/[.02] transition-colors duration-500 group"
                style={{ transitionDelay: `${i * 100}ms` }}
              >
                <p
                  className="text-4xl font-semibold bg-gradient-to-b from-white to-zinc-400 bg-clip-text text-transparent counter group-hover:from-emerald-300 group-hover:to-emerald-500 transition-all duration-500"
                  data-target={stat.target}
                >
                  0
                </p>
                <p className="text-[10px] uppercase tracking-widest text-zinc-600 mt-1 group-hover:text-zinc-500 transition-colors">
                  {stat.label}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ─── SKILLS ─────────────────────────────────────────────────── */}
      <section id="skills" className="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60">
        <div className="reveal mb-16">
          <div className="flex items-center gap-3 mb-4">
            <div className="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent" />
            <p className="text-xs uppercase tracking-[0.25em] text-zinc-500">Expertise</p>
          </div>
          <h2 className="text-3xl md:text-4xl font-semibold text-white">Skills</h2>
        </div>

        {skills.length > 0 ? (
          <div className="hover-fade-group grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {skills.map((skill, i) => (
              <div
                key={skill.id}
                className="hover-fade-item reveal"
                style={{ transitionDelay: `${(i + 1) * 100}ms` }}
              >
                <div className="skill-card relative glass-card rounded-2xl p-6 h-full cursor-default group overflow-hidden">
                  <div className="flex items-start justify-between mb-5">
                    <span className="text-xs font-mono text-emerald-500/50 bg-emerald-500/5 px-2.5 py-1 rounded-full">
                      {skill.id}
                    </span>
                    <div className="flex items-center gap-2 shrink-0">{skill.icon}</div>
                  </div>
                  <h3 className="text-white font-semibold text-lg mb-1 group-hover:text-emerald-400 transition-colors duration-300">
                    {skill.title}
                  </h3>
                  <p className="text-zinc-500 text-sm">{skill.subtitle}</p>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center text-zinc-500 py-10">No skills added yet.</div>
        )}
      </section>

      {/* ─── EXPERIENCE ──────────────────────────────────────────────── */}
      <section id="experience" className="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60">
        <div className="reveal mb-16">
          <div className="flex items-center gap-3 mb-4">
            <div className="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent" />
            <p className="text-xs uppercase tracking-[0.25em] text-zinc-500">Where I&apos;ve learned</p>
          </div>
          <h2 className="text-3xl md:text-4xl font-semibold text-white">Experience</h2>
        </div>

        {experiences.length > 0 ? (
          <div className="hover-fade-group space-y-2">
            {experiences.map((exp, i) => (
              <div
                key={exp.id}
                className="hover-fade-item exp-item reveal"
                style={{ transitionDelay: `${i * 100}ms` }}
              >
                <div className="group block rounded-xl p-6 -mx-4 hover:bg-zinc-900/50 transition-all duration-300">
                  <div className="flex gap-6">
                    <div className="shrink-0 w-[72px] pt-1">
                      <span className="text-[10px] text-emerald-500/60 bg-emerald-500/5 px-2 py-1 rounded font-mono tracking-wide">
                        {exp.dateRange}
                      </span>
                    </div>
                    <div className="flex-1 min-w-0">
                      <h3 className="text-white font-medium group-hover:text-emerald-400 transition-colors duration-300 flex items-center gap-2">
                        {exp.title} &middot; {exp.company}
                      </h3>
                      {exp.description && (
                        <p className="text-sm text-zinc-500 mt-2 leading-relaxed">{exp.description}</p>
                      )}
                      {exp.tags.length > 0 && (
                        <div className="flex flex-wrap gap-2 mt-4">
                          {exp.tags.map((tag) => (
                            <span
                              key={tag}
                              className="text-[10px] text-emerald-400/80 bg-emerald-400/10 px-2.5 py-1 rounded-full font-medium"
                            >
                              {tag}
                            </span>
                          ))}
                        </div>
                      )}
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center text-zinc-500 py-10">No experiences yet.</div>
        )}
      </section>

      {/* ─── PROJECTS ────────────────────────────────────────────────── */}
      <section id="projects" className="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60">
        <div className="reveal mb-16">
          <div className="flex items-center gap-3 mb-4">
            <div className="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent" />
            <p className="text-xs uppercase tracking-[0.25em] text-zinc-500">A few things I&apos;ve built</p>
          </div>
          <h2 className="text-3xl md:text-4xl font-semibold text-white">Projects</h2>
        </div>

        {projects.length > 0 ? (
          <div className="hover-fade-group grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {projects.map((project, i) => {
              const isFeatured = i === 0 && projects.length > 1;
              const indexStr = String(i + 1).padStart(2, '0');
              return (
                <article
                  key={project.id}
                  className={`hover-fade-item project-card-glow reveal group cursor-pointer rounded-2xl glass-card p-4 ${isFeatured ? 'md:col-span-2 md:row-span-2' : ''}`}
                  style={{ transitionDelay: `${i * 120}ms` }}
                >
                  <a
                    href={project.url || '#'}
                    target={project.url ? '_blank' : undefined}
                    rel={project.url ? 'noopener noreferrer' : undefined}
                    className="block w-full h-full relative z-10"
                  >
                    <div
                      className={`overflow-hidden rounded-xl bg-zinc-900 mb-5 ${isFeatured ? 'aspect-[16/10]' : 'aspect-[4/3]'} relative`}
                    >
                      {project.imageUrl ? (
                        <>
                          {/* eslint-disable-next-line @next/next/no-img-element */}
                          <img
                            src={project.imageUrl}
                            alt={project.title}
                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                          />
                          <div className="absolute inset-0 bg-gradient-to-t from-zinc-950/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                        </>
                      ) : (
                        <div className="w-full h-full bg-gradient-to-br from-zinc-800/80 to-zinc-900 group-hover:scale-105 transition-transform duration-700 ease-out flex flex-col items-center justify-center gap-3 relative">
                          <div className="absolute inset-0 bg-gradient-to-br from-emerald-500/[.02] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                          <span className="text-5xl font-light text-zinc-800 group-hover:text-zinc-700 transition-colors">
                            {indexStr}
                          </span>
                        </div>
                      )}
                      {/* Project number badge */}
                      <span className="absolute top-3 left-3 text-[10px] font-mono text-zinc-500 bg-zinc-900/80 backdrop-blur-sm px-2 py-1 rounded-md border border-zinc-800/50">
                        {indexStr}
                      </span>
                    </div>
                    <div className="px-1">
                      <div className="flex items-center justify-between mb-2">
                        <h3 className="text-white font-medium group-hover:text-emerald-400 transition-colors duration-300 flex items-center gap-2">
                          {project.title}
                          {project.status === 'coming_soon' && (
                            <span className="text-[9px] uppercase tracking-widest text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20 whitespace-nowrap animate-pulse">
                              Coming Soon
                            </span>
                          )}
                        </h3>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          className="w-4 h-4 text-zinc-600 group-hover:text-emerald-400 group-hover:translate-x-1 group-hover:-translate-y-1 transition-all duration-300"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M7 17L17 7M17 7H7M17 7v10" />
                        </svg>
                      </div>
                      {project.description && (
                        <p className="text-sm text-zinc-500 line-clamp-2">{project.description}</p>
                      )}
                      {project.tags.length > 0 && (
                        <div className="flex flex-wrap gap-2 mt-3">
                          {project.tags.map((tag) => (
                            <span
                              key={tag}
                              className="text-[10px] text-emerald-400/70 bg-emerald-400/5 border border-emerald-500/15 px-2.5 py-0.5 rounded-full"
                            >
                              {tag}
                            </span>
                          ))}
                        </div>
                      )}
                    </div>
                  </a>
                </article>
              );
            })}
          </div>
        ) : (
          <div className="col-span-full text-center text-zinc-500 py-10">
            No projects yet. Add entries in Contentful to get started.
          </div>
        )}
      </section>

      {/* ─── CONTACT ─────────────────────────────────────────────────── */}
      <section id="contact" className="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60 relative overflow-hidden">
        {/* Background gradient orb */}
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full bg-emerald-500/[.03] blur-[100px] pointer-events-none" />

        <div className="reveal text-center max-w-2xl mx-auto relative z-10">
          <div className="flex items-center justify-center gap-3 mb-4">
            <div className="w-8 h-px bg-gradient-to-r from-transparent to-emerald-500/60" />
            <p className="text-xs uppercase tracking-[0.25em] text-zinc-500">Get In Touch</p>
            <div className="w-8 h-px bg-gradient-to-l from-transparent to-emerald-500/60" />
          </div>
          <h2 className="text-4xl md:text-5xl font-semibold text-white leading-tight mb-6">
            Have a project in mind?
          </h2>
          <p className="text-zinc-400 leading-relaxed mb-10">
            I&apos;m always open to discussing new projects, creative ideas, or opportunities to be part of your vision.
          </p>

          {/* Shimmer CTA button */}
          <a
            href="mailto:m.reskyramadhan9@gmail.com"
            className="group relative inline-flex items-center gap-3 text-sm text-white border border-emerald-500/30 hover:border-emerald-400/60 px-8 py-4 rounded-full transition-all duration-300 hover:gap-4 hover:shadow-[0_0_30px_rgba(16,185,129,.15)] overflow-hidden"
          >
            <span className="btn-shimmer absolute inset-0 rounded-full" />
            <span className="relative">Say Hello</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              className="w-4 h-4 relative group-hover:translate-x-0.5 transition-transform"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>

          {/* Social links */}
          <div className="flex items-center justify-center gap-3 mt-12">
            <a
              href="https://github.com/PlutoniumQI"
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 text-xs text-zinc-500 border border-zinc-800 hover:border-emerald-500/30 hover:text-emerald-400 px-5 py-2.5 rounded-full transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_4px_12px_rgba(16,185,129,.08)]"
            >
              <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
              </svg>
              GitHub
            </a>
            <a
              href="https://www.instagram.com/hibiki_ilya"
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 text-xs text-zinc-500 border border-zinc-800 hover:border-emerald-500/30 hover:text-emerald-400 px-5 py-2.5 rounded-full transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_4px_12px_rgba(16,185,129,.08)]"
            >
              <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
              </svg>
              Instagram
            </a>
          </div>

          {/* Timezone with live clock */}
          <p className="mt-8 text-xs text-zinc-700 flex items-center justify-center gap-2">
            <span className="w-1.5 h-1.5 rounded-full bg-emerald-500/50 inline-block animate-pulse" />
            Available · WITA (UTC+8) ·{' '}
            <span id="liveClock" className="font-mono text-zinc-600" />
          </p>
        </div>
      </section>

      {/* ─── FOOTER ──────────────────────────────────────────────────── */}
      <footer className="border-t border-zinc-900/60 py-10 px-6">
        <div className="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
          <p className="text-xs text-zinc-600">
            © {new Date().getFullYear()} Muhammad Resky Ramadhan. All rights reserved.
          </p>
          <div className="flex items-center gap-4">
            <a
              href="#hero"
              className="text-xs text-zinc-600 hover:text-emerald-400 transition-colors duration-300 flex items-center gap-1.5"
            >
              <svg xmlns="http://www.w3.org/2000/svg" className="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 15l7-7 7 7" />
              </svg>
              Back to top
            </a>
          </div>
        </div>
      </footer>

      {/* ─── BACK TO TOP FLOATING ────────────────────────────────────── */}
      <button
        id="backToTop"
        className="fixed bottom-6 right-6 z-40 w-10 h-10 rounded-full glass-card border-emerald-500/20 flex items-center justify-center opacity-0 translate-y-4 hover:border-emerald-400/50 hover:shadow-[0_0_15px_rgba(16,185,129,.1)] transition-all duration-300"
        aria-label="Back to top"
      >
        <svg xmlns="http://www.w3.org/2000/svg" className="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 15l7-7 7 7" />
        </svg>
      </button>
    </>
  );
}

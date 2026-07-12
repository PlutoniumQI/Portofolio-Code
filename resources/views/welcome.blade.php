<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfolio Re:SKY</title>
    <meta name="description"
        content="Muhammad Resky Ramadhan — UI/UX Designer & Front-end Developer based in Banjarmasin, Indonesia." />
    <meta name="author" content="Muhammad Resky Ramadhan" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                        mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
                    },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet" />
    <style>
        /* ─── GLOBAL ─────────────────────────────────────── */
        ::selection {
            background: rgba(16, 185, 129, .25);
            color: #fff;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #09090b;
        }

        ::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #3f3f46;
        }

        /* Dot grid pattern */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255, 255, 255, .03) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }

        /* Noise / grain overlay */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            opacity: .018;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 256px 256px;
            pointer-events: none;
            z-index: 0;
        }

        /* ─── CURSOR GLOW ────────────────────────────────── */
        #glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, .04) 0%, rgba(255, 255, 255, .02) 30%, transparent 70%);
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: left .15s ease, top .15s ease;
            z-index: 0;
        }

        /* ─── TYPEWRITER CURSOR ──────────────────────────── */
        #tw::after {
            content: '|';
            animation: blink 1s step-end infinite;
            color: #10b981;
            opacity: .7;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: .7
            }

            50% {
                opacity: 0
            }
        }

        /* ─── FLOATING ANIMATION ─────────────────────────── */
        @keyframes fl {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-14px)
            }
        }

        .fl {
            animation: fl 5s ease-in-out infinite;
        }

        .fl2 {
            animation: fl 7s 1.5s ease-in-out infinite;
        }

        .fl3 {
            animation: fl 6s 0.7s ease-in-out infinite;
        }

        /* ─── MARQUEE ────────────────────────────────────── */
        @keyframes mq {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        .mq {
            animation: mq 28s linear infinite;
        }

        /* ─── GRADIENT ORB (Hero) ────────────────────────── */
        @keyframes orbFloat {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
            }

            33% {
                transform: translate(-45%, -55%) scale(1.05);
            }

            66% {
                transform: translate(-55%, -45%) scale(.95);
            }
        }

        .hero-orb {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, .08) 0%, rgba(6, 78, 59, .04) 40%, transparent 70%);
            filter: blur(60px);
            animation: orbFloat 12s ease-in-out infinite;
            pointer-events: none;
        }

        /* ─── HOVER FADE (Brittany Chiang style) ─────────── */
        .hover-fade-group:hover .hover-fade-item {
            opacity: 0.35;
        }

        .hover-fade-group .hover-fade-item {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .hover-fade-group .hover-fade-item:hover {
            opacity: 1 !important;
        }

        /* ─── PROJECT CARD GLOW ──────────────────────────── */
        .project-card-glow {
            position: relative;
            overflow: hidden;
        }

        .project-card-glow::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            opacity: 0;
            background: radial-gradient(600px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(16, 185, 129, .06), transparent 40%);
            transition: opacity 0.3s ease;
            z-index: 1;
            pointer-events: none;
        }

        .project-card-glow:hover::before {
            opacity: 1;
        }

        /* ─── GLASS CARD ─────────────────────────────────── */
        .glass-card {
            background: rgba(24, 24, 27, .5);
            border: 1px solid rgba(63, 63, 70, .4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* ─── SHIMMER BUTTON ─────────────────────────────── */
        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        .btn-shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, .06) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        /* ─── GRADIENT BORDER ────────────────────────────── */
        .gradient-border-ring {
            background: conic-gradient(from 0deg, #10b981, #059669, #047857, #065f46, #10b981);
            padding: 2px;
            border-radius: 9999px;
        }

        /* ─── EXPERIENCE TIMELINE ────────────────────────── */
        .exp-item {
            position: relative;
        }

        .exp-item::before {
            content: '';
            position: absolute;
            left: 79px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgb(63 63 70) 20%, rgb(63 63 70) 80%, transparent);
        }

        .exp-item:last-child::before {
            display: none;
        }

        /* ─── SCROLL INDICATOR ───────────────────────────── */
        @keyframes scrollBounce {

            0%,
            100% {
                transform: translateY(0);
                opacity: .4;
            }

            50% {
                transform: translateY(8px);
                opacity: .8;
            }
        }

        .scroll-indicator {
            animation: scrollBounce 2s ease-in-out infinite;
        }

        /* ─── STAT SHIMMER ───────────────────────────────── */
        @keyframes statShimmer {
            0% {
                opacity: 0;
                transform: translateY(4px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ─── BACK TO TOP ────────────────────────────────── */
        #backToTop {
            transition: opacity .3s ease, transform .3s ease;
        }

        #backToTop.show {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        /* ─── MOBILE MENU ────────────────────────────────── */
        #mobileMenu {
            transition: opacity .3s ease, visibility .3s ease;
        }

        #mobileMenu.open {
            opacity: 1 !important;
            visibility: visible !important;
        }

        #mobileMenu a {
            transition: transform .3s ease, opacity .3s ease;
        }

        #mobileMenu.open a {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }

        /* ─── SKILL CARD ─────────────────────────────────── */
        .skill-card {
            transition: all .3s ease;
        }

        .skill-card:hover {
            border-color: rgba(16, 185, 129, .3);
            background: rgba(16, 185, 129, .03);
            transform: translateY(-2px);
        }

        .skill-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 60%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #10b981, transparent);
            transition: transform .3s ease;
            border-radius: 1px;
        }

        .skill-card:hover::after {
            transform: translateX(-50%) scaleX(1);
        }
    </style>
</head>

<body class="bg-zinc-950 text-zinc-300 font-sans antialiased">
    <div id="glow"></div>

    <!-- ─── NAVBAR ─────────────────────────────────────────────────── -->
    <header id="navbar" class="fixed top-0 inset-x-0 z-50 transition-all duration-500">
        <div class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="#hero" class="text-white font-semibold tracking-widest text-sm uppercase relative z-50">re:<span
                    class="text-emerald-400">SKY</span></a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="#about"
                    class="nav-link text-xs uppercase tracking-widest text-zinc-400 hover:text-white transition-colors duration-300">About</a>
                <a href="#skills"
                    class="nav-link text-xs uppercase tracking-widest text-zinc-400 hover:text-white transition-colors duration-300">Skills</a>
                <a href="#experience"
                    class="nav-link text-xs uppercase tracking-widest text-zinc-400 hover:text-white transition-colors duration-300">Experience</a>
                <a href="#projects"
                    class="nav-link text-xs uppercase tracking-widest text-zinc-400 hover:text-white transition-colors duration-300">Work</a>
                <a href="#contact"
                    class="nav-link text-xs uppercase tracking-widest text-zinc-400 hover:text-white transition-colors duration-300">Contact</a>


            </nav>

            <!-- Mobile Hamburger -->
            <button id="menuToggle"
                class="md:hidden relative z-50 flex flex-col items-center justify-center w-10 h-10 gap-1.5 group"
                aria-label="Toggle menu">
                <span
                    class="hamburger-line w-6 h-px bg-zinc-300 transition-all duration-300 origin-center group-[.active]:rotate-45 group-[.active]:translate-y-[3.5px]"></span>
                <span
                    class="hamburger-line w-6 h-px bg-zinc-300 transition-all duration-300 group-[.active]:opacity-0"></span>
                <span
                    class="hamburger-line w-6 h-px bg-zinc-300 transition-all duration-300 origin-center group-[.active]:-rotate-45 group-[.active]:-translate-y-[3.5px]"></span>
            </button>
        </div>
    </header>

    <!-- ─── MOBILE MENU OVERLAY ───────────────────────────────────── -->
    <div id="mobileMenu"
        class="fixed inset-0 z-40 bg-zinc-950/95 backdrop-blur-xl opacity-0 invisible flex flex-col items-center justify-center gap-8">
        <a href="#about"
            class="mobile-nav-link text-2xl font-light text-zinc-300 hover:text-emerald-400 tracking-widest uppercase transition-colors duration-300 translate-y-4 opacity-0">About</a>
        <a href="#skills"
            class="mobile-nav-link text-2xl font-light text-zinc-300 hover:text-emerald-400 tracking-widest uppercase transition-colors duration-300 translate-y-4 opacity-0"
            style="transition-delay:50ms">Skills</a>
        <a href="#experience"
            class="mobile-nav-link text-2xl font-light text-zinc-300 hover:text-emerald-400 tracking-widest uppercase transition-colors duration-300 translate-y-4 opacity-0"
            style="transition-delay:100ms">Experience</a>
        <a href="#projects"
            class="mobile-nav-link text-2xl font-light text-zinc-300 hover:text-emerald-400 tracking-widest uppercase transition-colors duration-300 translate-y-4 opacity-0"
            style="transition-delay:150ms">Work</a>
        <a href="#contact"
            class="mobile-nav-link text-2xl font-light text-zinc-300 hover:text-emerald-400 tracking-widest uppercase transition-colors duration-300 translate-y-4 opacity-0"
            style="transition-delay:200ms">Contact</a>

    </div>

    <!-- ─── HERO ──────────────────────────────────────────────────── -->
    <section id="hero"
        class="min-h-screen flex flex-col justify-center px-6 max-w-6xl mx-auto relative overflow-hidden">

        <!-- Animated gradient orb -->
        <div class="hero-orb right-[10%] top-[30%]"></div>

        <!-- Floating decorative elements -->
        <div
            class="fl absolute right-16 top-1/4 w-40 h-40 border border-zinc-800/50 rounded-full opacity-20 pointer-events-none">
        </div>
        <div
            class="fl2 absolute right-32 top-1/2 w-6 h-6 bg-emerald-500/10 rounded-full opacity-40 pointer-events-none">
        </div>
        <div class="fl3 absolute right-48 top-[60%] w-3 h-3 bg-emerald-400/20 rounded-full pointer-events-none"></div>
        <div
            class="fl absolute left-[10%] top-[20%] w-24 h-24 border border-zinc-800/30 rounded-full opacity-10 pointer-events-none">
        </div>

        <div class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out relative z-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent"></div>
                <p class="text-xs uppercase tracking-[0.25em] text-zinc-500">Student of SMKN 2 Banjarmasin</p>
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-semibold text-white leading-none tracking-tight mb-8">
                Muhammad Resky Ramadhan<span class="text-emerald-400">.</span>
            </h1>
            <p class="text-lg md:text-xl text-zinc-400 max-w-xl leading-relaxed mb-12">
                <span id="tw"></span>
            </p>
            <div class="flex items-center gap-6">
                <a href="#projects"
                    class="group inline-flex items-center gap-2 text-sm text-white border border-zinc-700 hover:border-emerald-500/50 px-6 py-3 rounded-full transition-all duration-300 hover:gap-3 hover:shadow-[0_0_20px_rgba(16,185,129,.1)]">
                    View Work
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="#contact"
                    class="text-sm text-zinc-500 hover:text-emerald-400 transition-colors duration-300 underline underline-offset-4 decoration-zinc-700 hover:decoration-emerald-500/50">Get
                    in touch</a>
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-3">
            <span class="text-[10px] uppercase tracking-[.3em] text-zinc-600">Scroll</span>
            <div class="scroll-indicator flex flex-col items-center">
                <div class="w-5 h-8 border border-zinc-700 rounded-full flex justify-center pt-1.5">
                    <div class="w-1 h-2 bg-emerald-400/60 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── MARQUEE ─────────────────────────────────────────────────── -->
    <div class="border-y border-zinc-900/80 py-4 overflow-hidden relative">
        <div
            class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-zinc-950 to-transparent z-10 pointer-events-none">
        </div>
        <div
            class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-zinc-950 to-transparent z-10 pointer-events-none">
        </div>
        <div class="mq flex gap-10 whitespace-nowrap w-max">
            @php
                $marquee_items = ['UI/UX Design', 'Front-end Dev', 'Figma', 'Laravel', 'Tailwind CSS', 'Design Systems', 'Prototyping'];
                $all_items = array_merge($marquee_items, $marquee_items);
            @endphp
            @foreach($all_items as $item)
                <span class="text-xs tracking-[.25em] text-zinc-700 uppercase">{{ $item }}</span>
                <span class="text-emerald-500/30 px-3">✦</span>
            @endforeach
        </div>
    </div>

    <!-- ─── ABOUT ──────────────────────────────────────────────────── -->
    <section id="about" class="py-32 px-6 max-w-6xl mx-auto">
        <div class="grid md:grid-cols-2 gap-20 items-start">

            <!-- Left col -->
            <div class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out">
                <!-- Profile photo with gradient ring -->
                <div class="gradient-border-ring w-28 h-28 mb-6">
                    <div class="w-full h-full rounded-full overflow-hidden bg-zinc-900">
                        @if(isset($user) && $user->about_photo)
                            <img src="{{ \Illuminate\Support\Str::startsWith($user->about_photo, ['http://', 'https://', '//']) ? $user->about_photo : \Illuminate\Support\Facades\Storage::disk('public')->url($user->about_photo) }}"
                                alt="{{ $user->name }}"
                                class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500">
                        @else
                            <img src="https://ui-avatars.com/api/?name=Resky+Ramadhan&background=18181b&color=10b981&size=512&bold=true"
                                alt="Photo_Profile"
                                class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500">
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent"></div>
                    <p class="text-xs uppercase tracking-[0.25em] text-zinc-500">About</p>
                </div>
                <h2 class="text-4xl md:text-5xl font-semibold text-white leading-tight">
                    Designing with purpose,<br />
                    <span class="bg-gradient-to-r from-zinc-400 to-zinc-600 bg-clip-text text-transparent">building with
                        precision.</span>
                </h2>

                <div class="mt-10 flex items-center gap-3">
                    <div class="h-px bg-gradient-to-r from-emerald-500/40 to-zinc-800 w-16"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500/50"></div>
                </div>

                <dl class="mt-8 space-y-4">
                    <div class="flex items-start gap-5 group">
                        <dt
                            class="text-[10px] uppercase tracking-widest text-zinc-600 w-20 shrink-0 pt-0.5 group-hover:text-emerald-400/60 transition-colors duration-300">
                            School</dt>
                        <dd class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors duration-300">
                            SMKN 2 Banjarmasin</dd>
                    </div>
                    <div class="flex items-start gap-5 group">
                        <dt
                            class="text-[10px] uppercase tracking-widest text-zinc-600 w-20 shrink-0 pt-1 group-hover:text-emerald-400/60 transition-colors duration-300">
                            Language</dt>
                        <dd class="flex flex-wrap gap-2">
                            @foreach (['Indonesian', 'English'] as $lang)
                                <span
                                    class="text-xs text-zinc-400 border border-zinc-800 px-3 py-1 rounded-full cursor-default hover:border-emerald-500/30 hover:text-emerald-400/80 transition-all duration-300">
                                    {{ $lang }}
                                </span>
                            @endforeach
                        </dd>
                    </div>
                    <div class="flex items-start gap-5 group">
                        <dt
                            class="text-[10px] uppercase tracking-widest text-zinc-600 w-20 shrink-0 pt-0.5 group-hover:text-emerald-400/60 transition-colors duration-300">
                            Based in</dt>
                        <dd
                            class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors duration-300 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/50 inline-block shrink-0"></span>
                            Banjarmasin, Indonesia
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Right col -->
            <div class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out delay-150 space-y-7">
                <div class="relative glass-card rounded-2xl p-6">
                    <div
                        class="absolute -top-2.5 left-5 text-5xl leadding-none text-emerald-500/30 select-none font-serif">
                        "</div>
                    <p class="text-zinc-300 italic text-sm leading-relaxed pt-3">You're living a different night, yet
                        you are in the same season.</p>
                </div>

                <p class="text-zinc-300 leading-relaxed">
                    I'm a <span class="text-emerald-400 font-medium">UI/UX Designer</span> and
                    <span class="text-emerald-400 font-medium">Front-end Developer</span> based in Indonesia,
                    passionate about minimal, purposeful interfaces.
                </p>
                <p class="text-zinc-500 text-sm leading-relaxed">
                    I love making things that help people and solve real problems. My goal is to create experiences that
                    are not only beautiful but also intuitive and accessible.
                </p>

                <div class="inline-flex items-center gap-2 glass-card rounded-full px-4 py-2">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-40"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500/70"></span>
                    </span>
                    <span class="text-xs text-zinc-400 uppercase tracking-widest">Open to learning</span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out mt-20">
            <div class="grid grid-cols-3 divide-x divide-zinc-800/50 rounded-2xl glass-card overflow-hidden">
                @php
                    $stats = [
                        ['target' => 1, 'label' => 'Year Learning'],
                        ['target' => 3, 'label' => 'Projects Built'],
                        ['target' => 5, 'label' => 'Tools Mastered'],
                    ];
                @endphp
                @foreach ($stats as $i => $stat)
                    <div class="flex flex-col items-center justify-center py-10 gap-1 hover:bg-emerald-500/[.02] transition-colors duration-500 group"
                        style="transition-delay: {{ $i * 100 }}ms">
                        <p class="text-4xl font-semibold bg-gradient-to-b from-white to-zinc-400 bg-clip-text text-transparent counter group-hover:from-emerald-300 group-hover:to-emerald-500 transition-all duration-500"
                            data-target="{{ $stat['target'] }}">0</p>
                        <p
                            class="text-[10px] uppercase tracking-widest text-zinc-600 mt-1 group-hover:text-zinc-500 transition-colors">
                            {{ $stat['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ─── SKILLS ─────────────────────────────────────────────────── -->
    <section id="skills" class="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60">
        <div class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out mb-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent"></div>
                <p class="text-xs uppercase tracking-[0.25em] text-zinc-500">Expertise</p>
            </div>
            <h2 class="text-3xl md:text-4xl font-semibold text-white">Skills</h2>
        </div>

        <div class="hover-fade-group grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($skills as $i => $skill)
                <div class="hover-fade-item reveal opacity-0 translate-y-8 transition-all duration-700 ease-out"
                    style="transition-delay: {{ ($i + 1) * 100 }}ms">
                    <div class="skill-card relative glass-card rounded-2xl p-6 h-full cursor-default group overflow-hidden">
                        <div class="flex items-start justify-between mb-5">
                            <span class="text-xs font-mono text-emerald-500/50 bg-emerald-500/5 px-2.5 py-1 rounded-full">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="flex items-center gap-2 shrink-0">
                                {!! $skill->icon_data !!}
                            </div>
                        </div>
                        <h3
                            class="text-white font-semibold text-lg mb-1 group-hover:text-emerald-400 transition-colors duration-300">
                            {{ $skill->title }}
                        </h3>
                        <p class="text-zinc-500 text-sm">
                            {{ $skill->subtitle }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-zinc-500 py-10">
                    No skills added yet.
                </div>
            @endforelse
        </div>
    </section>

    <!-- ─── EXPERIENCE ─────────────────────────────────────────────── -->
    <section id="experience" class="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60">
        <div class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out mb-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent"></div>
                <p class="text-xs uppercase tracking-[0.25em] text-zinc-500">Where I've learned</p>
            </div>
            <h2 class="text-3xl md:text-4xl font-semibold text-white">Experience</h2>
        </div>

        <div class="hover-fade-group space-y-2">
            @forelse($experiences as $exp)
                <div class="hover-fade-item exp-item reveal opacity-0 translate-y-8 transition-all duration-700 ease-out"
                    style="transition-delay: {{ $loop->index * 100 }}ms">
                    <div class="group block rounded-xl p-6 -mx-4 hover:bg-zinc-900/50 transition-all duration-300">
                        <div class="flex gap-6">
                            <div class="shrink-0 w-[72px] pt-1">
                                <span
                                    class="text-[10px] text-emerald-500/60 bg-emerald-500/5 px-2 py-1 rounded font-mono tracking-wide">{{ $exp->date_range }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-white font-medium group-hover:text-emerald-400 transition-colors duration-300 flex items-center gap-2">
                                    {{ $exp->title }} &middot; {{ $exp->company }}
                                </h3>
                                @if($exp->description)
                                    <p class="text-sm text-zinc-500 mt-2 leading-relaxed">{{ $exp->description }}</p>
                                @endif
                                @if(is_array($exp->tags) && count($exp->tags) > 0)
                                    <div class="flex flex-wrap gap-2 mt-4">
                                        @foreach($exp->tags as $tag)
                                            <span
                                                class="text-[10px] text-emerald-400/80 bg-emerald-400/10 px-2.5 py-1 rounded-full font-medium">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-zinc-500 py-10">
                    No experiences yet.
                </div>
            @endforelse
        </div>
    </section>

    <!-- ─── PROJECTS ──────────────────────────────────────────────── -->
    <section id="projects" class="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60">
        <div class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out mb-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-px bg-gradient-to-r from-emerald-500/60 to-transparent"></div>
                <p class="text-xs uppercase tracking-[0.25em] text-zinc-500">A few things I've built</p>
            </div>
            <h2 class="text-3xl md:text-4xl font-semibold text-white">Projects</h2>
        </div>

        <div class="hover-fade-group grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                @php
                    $isFeatured = $loop->first && count($projects) > 1;
                @endphp
                <article
                    class="hover-fade-item project-card-glow reveal opacity-0 translate-y-8 transition-all duration-700 ease-out group cursor-pointer rounded-2xl glass-card p-4 {{ $isFeatured ? 'md:col-span-2 md:row-span-2' : '' }}"
                    style="transition-delay: {{ $loop->index * 120 }}ms">
                    <a href="{{ $project->url ?: '#' }}" {{ $project->url ? 'target="_blank" rel="noopener noreferrer"' : '' }} class="block w-full h-full relative z-10">
                        <div
                            class="overflow-hidden rounded-xl bg-zinc-900 mb-5 {{ $isFeatured ? 'aspect-[16/10]' : 'aspect-[4/3]' }} relative">
                            @if($project->image_url)
                                <img src="{{ \Illuminate\Support\Str::startsWith($project->image_url, ['http://', 'https://', '//']) ? $project->image_url : \Illuminate\Support\Facades\Storage::disk('public')->url($project->image_url) }}"
                                    alt="{{ $project->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out" />
                                <!-- Image overlay gradient -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-zinc-950/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-zinc-800/80 to-zinc-900 group-hover:scale-105 transition-transform duration-700 ease-out flex flex-col items-center justify-center gap-3 relative">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-emerald-500/[.02] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>
                                    <span
                                        class="text-5xl font-light text-zinc-800 group-hover:text-zinc-700 transition-colors">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            @endif
                            <!-- Project number badge -->
                            <span
                                class="absolute top-3 left-3 text-[10px] font-mono text-zinc-500 bg-zinc-900/80 backdrop-blur-sm px-2 py-1 rounded-md border border-zinc-800/50">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="px-1">
                            <div class="flex items-center justify-between mb-2">
                                <h3
                                    class="text-white font-medium group-hover:text-emerald-400 transition-colors duration-300 flex items-center gap-2">
                                    {{ $project->title }}
                                    @if($project->status === 'coming_soon')
                                        <span
                                            class="text-[9px] uppercase tracking-widest text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20 whitespace-nowrap animate-pulse">Coming
                                            Soon</span>
                                    @endif
                                </h3>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-zinc-600 group-hover:text-emerald-400 group-hover:translate-x-1 group-hover:-translate-y-1 transition-all duration-300"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 17L17 7M17 7H7M17 7v10" />
                                </svg>
                            </div>
                            @if($project->description)
                                <p class="text-sm text-zinc-500 line-clamp-2">
                                    {!! strip_tags($project->description) !!}
                                </p>
                            @endif
                            @if(is_array($project->tags) && count($project->tags) > 0)
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($project->tags as $tag)
                                        <span
                                            class="text-[10px] text-emerald-400/70 bg-emerald-400/5 border border-emerald-500/15 px-2.5 py-0.5 rounded-full">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                </article>
            @empty
                <div class="col-span-full text-center text-zinc-500 py-10">
                    No projects yet.
                </div>
            @endforelse
        </div>
    </section>

    <!-- ─── CONTACT ───────────────────────────────────────────────── -->
    <section id="contact" class="py-32 px-6 max-w-6xl mx-auto border-t border-zinc-900/60 relative overflow-hidden">
        <!-- Background gradient orb -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full bg-emerald-500/[.03] blur-[100px] pointer-events-none">
        </div>

        <div
            class="reveal opacity-0 translate-y-8 transition-all duration-700 ease-out text-center max-w-2xl mx-auto relative z-10">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-8 h-px bg-gradient-to-r from-transparent to-emerald-500/60"></div>
                <p class="text-xs uppercase tracking-[0.25em] text-zinc-500">Get In Touch</p>
                <div class="w-8 h-px bg-gradient-to-l from-transparent to-emerald-500/60"></div>
            </div>
            <h2 class="text-4xl md:text-5xl font-semibold text-white leading-tight mb-6">Have a project in mind?</h2>
            <p class="text-zinc-400 leading-relaxed mb-10">I'm always open to discussing new projects, creative ideas,
                or opportunities to be part of your vision.</p>

            <!-- Shimmer CTA button -->
            <a href="mailto:m.reskyramadhan9@gmail.com"
                class="group relative inline-flex items-center gap-3 text-sm text-white border border-emerald-500/30 hover:border-emerald-400/60 px-8 py-4 rounded-full transition-all duration-300 hover:gap-4 hover:shadow-[0_0_30px_rgba(16,185,129,.15)] overflow-hidden">
                <span class="btn-shimmer absolute inset-0 rounded-full"></span>
                <span class="relative">Say Hello</span>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 relative group-hover:translate-x-0.5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>

            <!-- Social links -->
            <div class="flex items-center justify-center gap-3 mt-12">
                <a href="https://github.com/PlutoniumQI" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 text-xs text-zinc-500 border border-zinc-800 hover:border-emerald-500/30 hover:text-emerald-400 px-5 py-2.5 rounded-full transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_4px_12px_rgba(16,185,129,.08)]">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                    </svg>
                    GitHub
                </a>
                <a href="https://www.instagram.com/hibiki_ilya" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 text-xs text-zinc-500 border border-zinc-800 hover:border-emerald-500/30 hover:text-emerald-400 px-5 py-2.5 rounded-full transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_4px_12px_rgba(16,185,129,.08)]">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>
                    Instagram
                </a>
            </div>

            <!-- Timezone with live clock -->
            <p class="mt-8 text-xs text-zinc-700 flex items-center justify-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/50 inline-block animate-pulse"></span>
                Available · WITA (UTC+8) ·
                <span id="liveClock" class="font-mono text-zinc-600"></span>
            </p>
        </div>
    </section>

    <!-- ─── FOOTER ────────────────────────────────────────────────── -->
    <footer class="border-t border-zinc-900/60 py-10 px-6">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs text-zinc-600">© {{ date('Y') }} Muhammad Resky Ramadhan. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#hero"
                    class="text-xs text-zinc-600 hover:text-emerald-400 transition-colors duration-300 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    Back to top
                </a>
            </div>
        </div>
    </footer>

    <!-- ─── BACK TO TOP FLOATING ──────────────────────────────────── -->
    <button id="backToTop"
        class="fixed bottom-6 right-6 z-40 w-10 h-10 rounded-full glass-card border-emerald-500/20 flex items-center justify-center opacity-0 translate-y-4 hover:border-emerald-400/50 hover:shadow-[0_0_15px_rgba(16,185,129,.1)] transition-all duration-300"
        aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <!-- ─── JAVASCRIPT ─────────────────────────────────────────────── -->
    <script>
        // ── Navbar scroll effect ──────────────────────────────
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('bg-zinc-950/80', 'backdrop-blur-md', 'border-b', 'border-zinc-900/50');
            } else {
                navbar.classList.remove('bg-zinc-950/80', 'backdrop-blur-md', 'border-b', 'border-zinc-900/50');
            }
        });

        // ── Mobile menu toggle ────────────────────────────────
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('open');
            document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
        });
        // Close mobile menu on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menuToggle.classList.remove('active');
                mobileMenu.classList.remove('open');
                document.body.style.overflow = '';
            });
        });

        // ── Scroll reveal with Intersection Observer ──────────
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('opacity-0', 'translate-y-8');
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => observer.observe(el));

        // Trigger hero immediately
        document.querySelectorAll('#hero .reveal').forEach(el => {
            setTimeout(() => {
                el.classList.remove('opacity-0', 'translate-y-8');
                el.classList.add('opacity-100', 'translate-y-0');
            }, 300);
        });

        // ── Cursor glow ───────────────────────────────────────
        const glow = document.getElementById('glow');
        document.addEventListener('mousemove', e => {
            glow.style.left = e.clientX + 'px';
            glow.style.top = e.clientY + 'px';
        });

        // ── Typewriter ────────────────────────────────────────
        const phrases = ["UI/UX Designer.", "Front-end Developer.", "Creating better web experiences."];
        let pi = 0, ci = 0, deleting = false;
        const tw = document.getElementById('tw');
        function typewrite() {
            const phrase = phrases[pi];
            if (!deleting) {
                tw.textContent = phrase.slice(0, ++ci);
                if (ci === phrase.length) { deleting = true; setTimeout(typewrite, 1800); return; }
            } else {
                tw.textContent = phrase.slice(0, --ci);
                if (ci === 0) { deleting = false; pi = (pi + 1) % phrases.length; }
            }
            setTimeout(typewrite, deleting ? 45 : 85);
        }
        setTimeout(typewrite, 800);

        // ── Active nav highlight on scroll ────────────────────
        const navSections = ['about', 'skills', 'experience', 'projects', 'contact'];
        window.addEventListener('scroll', () => {
            navSections.forEach(id => {
                const sec = document.getElementById(id);
                const link = document.querySelector(`nav a[href="#${id}"]`);
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
        }, { passive: true });

        // ── Counter animation ─────────────────────────────────
        const counters = document.querySelectorAll('.counter');
        const cObs = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el = entry.target, target = +el.dataset.target;
                let n = 0;
                const tick = () => { el.textContent = ++n + '+'; if (n < target) setTimeout(tick, 150); };
                tick();
                cObs.unobserve(el);
            });
        }, { threshold: 0.6 });
        counters.forEach(c => cObs.observe(c));

        // ── Project card glow tracking ────────────────────────
        document.querySelectorAll('.project-card-glow').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                card.style.setProperty('--mouse-x', (e.clientX - rect.left) + 'px');
                card.style.setProperty('--mouse-y', (e.clientY - rect.top) + 'px');
            });
        });

        // ── Back to top button ────────────────────────────────
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 600) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        }, { passive: true });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // ── Live clock (WITA UTC+8) ──────────────────────────
        function updateClock() {
            const now = new Date();
            const wita = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Makassar' }));
            const h = String(wita.getHours()).padStart(2, '0');
            const m = String(wita.getMinutes()).padStart(2, '0');
            const s = String(wita.getSeconds()).padStart(2, '0');
            document.getElementById('liveClock').textContent = `${h}:${m}:${s}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

</body>

</html>
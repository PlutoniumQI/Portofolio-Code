<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Placeholder content that mirrors the current one-page portfolio. Every
     * record is keyed by a natural identifier so the seeder can run again
     * without creating duplicates.
     */
    public function run(): void
    {
        $this->seedProfile();
        $this->seedProjects();
        $this->seedSkills();
        $this->seedEducations();
        $this->seedCertificates();
        $this->seedExperiences();
        $this->seedTestimonials();
        $this->seedPosts();
    }

    private function seedProfile(): void
    {
        Profile::query()->firstOrCreate([], [
            'full_name' => 'Muhammad Resky Ramadhan',
            'headline_id' => 'Siswa PPLG & Desainer UI/UX',
            'headline_en' => 'Software Engineering Student & UI/UX Designer',
            'intro_id' => 'Saya membangun antarmuka yang bersih, aksesibel, dan menyenangkan untuk digunakan.',
            'intro_en' => 'I build clean, accessible, and enjoyable interfaces for the web.',
            'about_id' => '<p>Saya Muhammad Resky Ramadhan, siswa SMKN 2 Banjarmasin jurusan Pengembangan Perangkat Lunak dan Gim. Saya tertarik pada desain UI/UX, front-end development, dan cara teknologi dapat menyelesaikan masalah nyata.</p><p>Saat ini saya mengerjakan Modulia, sebuah platform ujian online, dan terus belajar membangun produk dari riset hingga implementasi.</p>',
            'about_en' => '<p>I am Muhammad Resky Ramadhan, a software engineering student at SMKN 2 Banjarmasin. I am interested in UI/UX design, front-end development, and how technology can solve real problems.</p><p>I am currently working on Modulia, an online exam platform, and continue learning to build products from research to implementation.</p>',
            'location' => 'Banjarmasin, Indonesia',
            'email' => 'hello@example.com',
            'phone' => null,
            'availability_id' => 'Terbuka untuk kolaborasi',
            'availability_en' => 'Open to collaboration',
            'linkedin_url' => 'https://www.linkedin.com/',
            'github_url' => 'https://github.com/PlutoniumQI',
            'instagram_url' => null,
            'website_url' => null,
            'years_learning' => 2,
            'tools_mastered' => 8,
            'is_available' => true,
        ]);
    }

    private function seedProjects(): void
    {
        $projects = [
            [
                'title' => 'Exam Platform',
                'slug' => 'exam-platform',
                'title_en' => 'Exam Platform',
                'category' => 'web-app',
                'description' => 'Platform ujian online untuk sekolah dan bimbingan belajar.',
                'description_en' => 'An online exam platform for schools and tutoring centres.',
                'excerpt_id' => 'Modulia membantu guru membuat, menjadwalkan, dan menilai ujian dalam satu alur kerja yang sederhana.',
                'excerpt_en' => 'Modulia helps teachers create, schedule, and grade exams in one simple workflow.',
                'content_id' => '<p>Modulia dimulai dari masalah sederhana: guru menghabiskan terlalu banyak waktu untuk menyusun dan mengoreksi ujian kertas. Saya memetakan alur kerja guru, membuat wireframe, lalu membangun prototipe di Figma bersama tim.</p><h3>Proses</h3><ul><li>Riset pengguna dengan tiga guru dan dua siswa.</li><li>Information architecture dan user flow untuk pembuatan ujian.</li><li>Design system kecil berisi komponen soal, timer, dan hasil.</li><li>Implementasi front-end dengan Tailwind CSS dan integrasi API.</li></ul><h3>Hasil</h3><p>Prototipe digunakan untuk demo internal dan menjadi dasar pengembangan platform Modulia.</p>',
                'content_en' => '<p>Modulia started from a simple problem: teachers spend too much time preparing and grading paper exams. I mapped the teacher workflow, created wireframes, and built a Figma prototype with the team.</p><h3>Process</h3><ul><li>User research with three teachers and two students.</li><li>Information architecture and user flows for exam creation.</li><li>A small design system for question cards, timers, and results.</li><li>Front-end implementation with Tailwind CSS and API integration.</li></ul><h3>Outcome</h3><p>The prototype was used for an internal demo and became the foundation of the Modulia platform.</p>',
                'tags' => ['Figma', 'Tailwind CSS', 'Laravel', 'UI/UX'],
                'url' => 'https://suj.modulia.web.id/',
                'repo_url' => null,
                'status' => Project::STATUS_PUBLISHED,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Portfolio Website',
                'slug' => 'portfolio-website',
                'title_en' => 'Portfolio Website',
                'category' => 'web-app',
                'description' => 'Situs portofolio bilingual dengan admin Filament dan API.',
                'description_en' => 'A bilingual portfolio with a Filament admin and a public API.',
                'excerpt_id' => 'Portofolio ini menghubungkan Next.js di Vercel dengan Laravel dan Filament di Render.',
                'excerpt_en' => 'This portfolio connects Next.js on Vercel with Laravel and Filament on Render.',
                'content_id' => '<p>Saya ingin portofolio yang bisa diperbarui tanpa mengubah kode. Admin Filament menyimpan konten di Postgres, API menyajikannya dalam dua bahasa, dan Next.js merender halaman dengan ISR.</p><h3>Yang saya pelajari</h3><ul><li>Merancang skema konten bilingual.</li><li>Mengunggah aset ke GitHub agar bertahan di hosting gratis.</li><li>Menjaga performa dan aksesibilitas di perangkat mobile.</li></ul>',
                'content_en' => '<p>I wanted a portfolio I could update without touching code. A Filament admin stores content in Postgres, the API serves it in two languages, and Next.js renders the pages with ISR.</p><h3>What I learned</h3><ul><li>Designing a bilingual content schema.</li><li>Uploading assets to GitHub so they survive free hosting.</li><li>Keeping performance and accessibility solid on mobile.</li></ul>',
                'tags' => ['Next.js', 'Laravel', 'Filament', 'Tailwind CSS'],
                'url' => null,
                'repo_url' => 'https://github.com/PlutoniumQI/Portofolio-Code',
                'status' => Project::STATUS_PUBLISHED,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Modulia Design System',
                'slug' => 'modulia-design-system',
                'title_en' => 'Modulia Design System',
                'category' => 'design-system',
                'description' => 'Komponen dan token desain untuk produk Modulia.',
                'description_en' => 'Components and design tokens for the Modulia product suite.',
                'excerpt_id' => 'Kumpulan komponen Figma yang konsisten untuk tim produk dan engineering.',
                'excerpt_en' => 'A consistent set of Figma components for the product and engineering team.',
                'content_id' => '<p>Design system ini menyatukan warna, tipografi, spacing, dan komponen form yang dipakai di platform ujian. Tujuannya mengurangi keputusan desain berulang dan mempercepat handoff.</p><h3>Isi</h3><ul><li>Token warna dan tipografi.</li><li>Komponen tombol, input, modal, dan tabel.</li><li>Dokumentasi penggunaan dan contoh state.</li></ul>',
                'content_en' => '<p>This design system unifies the colours, typography, spacing, and form components used across the exam platform. The goal is to reduce repeated design decisions and speed up handoff.</p><h3>Contents</h3><ul><li>Colour and typography tokens.</li><li>Button, input, modal, and table components.</li><li>Usage documentation and state examples.</li></ul>',
                'tags' => ['Figma', 'Design System', 'UI/UX'],
                'url' => null,
                'repo_url' => null,
                'status' => Project::STATUS_COMING_SOON,
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['slug' => $project['slug']], $project);
        }
    }

    private function seedSkills(): void
    {
        $skills = [
            ['name' => 'UI/UX Design', 'category' => 'hard', 'percentage' => 85, 'icon' => 'palette', 'description_id' => 'Riset, wireframe, prototyping, dan pengujian kegunaan.', 'description_en' => 'Research, wireframes, prototyping, and usability testing.'],
            ['name' => 'Figma', 'category' => 'hard', 'percentage' => 90, 'icon' => 'figma', 'description_id' => 'Komponen, auto layout, design system, dan kolaborasi.', 'description_en' => 'Components, auto layout, design systems, and collaboration.'],
            ['name' => 'HTML & CSS', 'category' => 'hard', 'percentage' => 85, 'icon' => 'code', 'description_id' => 'Markup semantik, layout responsif, dan aksesibilitas.', 'description_en' => 'Semantic markup, responsive layout, and accessibility.'],
            ['name' => 'JavaScript', 'category' => 'hard', 'percentage' => 78, 'icon' => 'javascript', 'description_id' => 'DOM, fetch API, dan logika antarmuka modern.', 'description_en' => 'DOM, fetch API, and modern interface logic.'],
            ['name' => 'React & Next.js', 'category' => 'hard', 'percentage' => 75, 'icon' => 'react', 'description_id' => 'Komponen, routing, data fetching, dan optimasi halaman.', 'description_en' => 'Components, routing, data fetching, and page optimisation.'],
            ['name' => 'Tailwind CSS', 'category' => 'hard', 'percentage' => 85, 'icon' => 'tailwind', 'description_id' => 'Utility-first styling dan design token di CSS.', 'description_en' => 'Utility-first styling and design tokens in CSS.'],
            ['name' => 'Laravel', 'category' => 'hard', 'percentage' => 72, 'icon' => 'laravel', 'description_id' => 'Routing, Eloquent, validasi, dan API.', 'description_en' => 'Routing, Eloquent, validation, and APIs.'],
            ['name' => 'PHP', 'category' => 'hard', 'percentage' => 70, 'icon' => 'php', 'description_id' => 'Dasar pemrograman server-side dan struktur aplikasi.', 'description_en' => 'Server-side programming fundamentals and application structure.'],
            ['name' => 'Communication', 'category' => 'soft', 'percentage' => 85, 'icon' => 'chat', 'description_id' => 'Menyampaikan ide dengan jelas kepada tim dan pengguna.', 'description_en' => 'Explaining ideas clearly to teams and users.'],
            ['name' => 'Teamwork', 'category' => 'soft', 'percentage' => 90, 'icon' => 'users', 'description_id' => 'Berkolaborasi lintas peran dan memberi umpan balik.', 'description_en' => 'Collaborating across roles and giving useful feedback.'],
            ['name' => 'Problem Solving', 'category' => 'soft', 'percentage' => 82, 'icon' => 'sparkles', 'description_id' => 'Memecah masalah kompleks menjadi langkah kecil.', 'description_en' => 'Breaking complex problems into small, testable steps.'],
            ['name' => 'Time Management', 'category' => 'soft', 'percentage' => 78, 'icon' => 'clock', 'description_id' => 'Mengatur prioritas antara sekolah dan proyek.', 'description_en' => 'Balancing school work, projects, and deadlines.'],
            ['name' => 'Adaptability', 'category' => 'soft', 'percentage' => 82, 'icon' => 'refresh', 'description_id' => 'Cepat belajar alat dan kebutuhan baru.', 'description_en' => 'Learning new tools and requirements quickly.'],
        ];

        foreach ($skills as $index => $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                $skill + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedEducations(): void
    {
        Education::updateOrCreate(
            ['institution' => 'SMKN 2 Banjarmasin', 'start_date' => '2024'],
            [
                'degree_id' => 'Sekolah Menengah Kejuruan',
                'degree_en' => 'Vocational High School',
                'field_id' => 'Pengembangan Perangkat Lunak dan Gim',
                'field_en' => 'Software Engineering and Game Development',
                'end_date' => 'Now',
                'description_id' => 'Belajar pemrograman web, basis data, dan desain antarmuka melalui proyek nyata.',
                'description_en' => 'Studying web programming, databases, and interface design through real projects.',
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        Education::updateOrCreate(
            ['institution' => 'SMPN 1 Banjarmasin', 'start_date' => '2021'],
            [
                'degree_id' => 'Sekolah Menengah Pertama',
                'degree_en' => 'Junior High School',
                'field_id' => null,
                'field_en' => null,
                'end_date' => '2024',
                'description_id' => 'Mulai tertarik pada desain digital dan pengembangan web.',
                'description_en' => 'First became interested in digital design and web development.',
                'sort_order' => 2,
                'is_active' => true,
            ],
        );
    }

    private function seedCertificates(): void
    {
        $certificates = [
            [
                'title_id' => 'Belajar Dasar UX Design',
                'title_en' => 'UX Design Fundamentals',
                'issuer' => 'Dicoding Indonesia',
                'issued_at' => '2025-06-15',
                'credential_url' => 'https://www.dicoding.com/',
                'description_id' => 'Dasar riset pengguna, wireframe, dan pengujian kegunaan.',
                'description_en' => 'User research, wireframing, and usability testing fundamentals.',
            ],
            [
                'title_id' => 'Front-End Web Development',
                'title_en' => 'Front-End Web Development',
                'issuer' => 'Dicoding Indonesia',
                'issued_at' => '2025-09-20',
                'credential_url' => 'https://www.dicoding.com/',
                'description_id' => 'HTML, CSS, JavaScript, dan pengembangan antarmuka responsif.',
                'description_en' => 'HTML, CSS, JavaScript, and responsive interface development.',
            ],
            [
                'title_id' => 'Figma untuk Desain UI',
                'title_en' => 'Figma for UI Design',
                'issuer' => 'Coursera',
                'issued_at' => '2024-11-10',
                'credential_url' => 'https://www.coursera.org/',
                'description_id' => 'Komponen, prototyping, dan kolaborasi di Figma.',
                'description_en' => 'Components, prototyping, and collaboration in Figma.',
            ],
        ];

        foreach ($certificates as $index => $certificate) {
            Certificate::updateOrCreate(
                ['title_id' => $certificate['title_id']],
                $certificate + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedExperiences(): void
    {
        $experiences = [
            [
                'type' => Experience::TYPE_EDUCATION,
                'title' => 'PPLG Student',
                'title_en' => 'Software Engineering Student',
                'company' => 'SMKN 2 Banjarmasin',
                'location' => 'Banjarmasin, Indonesia',
                'date_range' => '2024 — Now',
                'description' => 'Mempelajari dasar rekayasa perangkat lunak dan desain UI/UX, lalu menerapkannya pada proyek sekolah dan organisasi.',
                'description_en' => 'Learning software engineering and UI/UX fundamentals, then applying them to school and organisation projects.',
                'tags' => ['Figma', 'Tailwind CSS', 'Laravel', 'UI/UX'],
                'sort_order' => 1,
            ],
            [
                'type' => Experience::TYPE_WORK,
                'title' => 'UI/UX Designer',
                'title_en' => 'UI/UX Designer',
                'company' => 'Modulia',
                'location' => 'Remote',
                'date_range' => '2025 — 2026',
                'description' => 'Merancang alur ujian, komponen antarmuka, dan prototipe untuk platform ujian online Modulia.',
                'description_en' => 'Designing exam flows, interface components, and prototypes for the Modulia online exam platform.',
                'tags' => ['Figma', 'UI/UX', 'Design System'],
                'sort_order' => 2,
            ],
            [
                'type' => Experience::TYPE_INTERNSHIP,
                'title' => 'PLK Intern',
                'title_en' => 'Internship (PLK)',
                'company' => 'PT Digital Nusantara',
                'location' => 'Banjarmasin, Indonesia',
                'date_range' => '2025',
                'description' => 'Membantu tim produk memperbaiki tampilan dasbor dan mendokumentasikan komponen UI.',
                'description_en' => 'Helped the product team improve a dashboard interface and document UI components.',
                'tags' => ['UI/UX', 'Figma', 'Documentation'],
                'sort_order' => 3,
            ],
            [
                'type' => Experience::TYPE_ORGANIZATION,
                'title' => 'OSIS Member',
                'title_en' => 'Student Council Member',
                'company' => 'SMKN 2 Banjarmasin',
                'location' => 'Banjarmasin, Indonesia',
                'date_range' => '2024 — 2025',
                'description' => 'Mengelola publikasi acara sekolah dan berkolaborasi dengan divisi lain.',
                'description_en' => 'Managed school event publications and collaborated with other divisions.',
                'tags' => ['Communication', 'Teamwork'],
                'sort_order' => 4,
            ],
            [
                'type' => Experience::TYPE_COMPETITION,
                'title' => 'Web Design Competition',
                'title_en' => 'Web Design Competition',
                'company' => 'Universitas Lambung Mangkurat',
                'location' => 'Banjarmasin, Indonesia',
                'date_range' => '2025',
                'description' => 'Membuat konsep dan prototipe situs pendidikan bersama tim, lalu mempresentasikannya kepada juri.',
                'description_en' => 'Created an education website concept and prototype with a team, then presented it to the judges.',
                'tags' => ['Figma', 'Web Design', 'Presentation'],
                'sort_order' => 5,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::updateOrCreate(
                [
                    'title' => $experience['title'],
                    'company' => $experience['company'],
                ],
                $experience + ['is_active' => true],
            );
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Rizky Pratama',
                'role_id' => 'Founder',
                'role_en' => 'Founder',
                'company' => 'Modulia',
                'quote_id' => 'Resky mampu menerjemahkan kebutuhan produk yang rumit menjadi alur yang sederhana dan mudah dipahami.',
                'quote_en' => 'Resky turns complicated product requirements into simple, understandable flows.',
                'category' => Testimonial::CATEGORY_CLIENT,
            ],
            [
                'name' => 'Aulia Rahma',
                'role_id' => 'UI/UX Mentor',
                'role_en' => 'UI/UX Mentor',
                'company' => 'Modulia',
                'quote_id' => 'Ia cepat belajar, terbuka pada masukan, dan selalu membawa solusi ke diskusi.',
                'quote_en' => 'He learns quickly, stays open to feedback, and always brings solutions to the discussion.',
                'category' => Testimonial::CATEGORY_MENTOR,
            ],
            [
                'name' => 'Hendra Saputra',
                'role_id' => 'Guru PPLG',
                'role_en' => 'Software Engineering Teacher',
                'company' => 'SMKN 2 Banjarmasin',
                'quote_id' => 'Proyeknya rapi dan menunjukkan pemahaman yang kuat tentang proses desain.',
                'quote_en' => 'His projects are tidy and show a strong understanding of the design process.',
                'category' => Testimonial::CATEGORY_TEACHER,
            ],
            [
                'name' => 'Nabila Putri',
                'role_id' => 'Teman sekelas',
                'role_en' => 'Classmate',
                'company' => 'SMKN 2 Banjarmasin',
                'quote_id' => 'Selalu siap membantu dan bisa diandalkan saat kerja kelompok.',
                'quote_en' => 'Always ready to help and reliable in group work.',
                'category' => Testimonial::CATEGORY_PEER,
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name'], 'category' => $testimonial['category']],
                $testimonial + ['rating' => 5, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedPosts(): void
    {
        $posts = [
            [
                'slug' => 'designing-for-clarity',
                'title_id' => 'Merancang untuk Kejelasan',
                'title_en' => 'Designing for Clarity',
                'excerpt_id' => 'Catatan tentang menyederhanakan alur ujian tanpa menghilangkan fitur penting.',
                'excerpt_en' => 'Notes on simplifying an exam flow without removing the features people need.',
                'body_id' => '<p>Desain yang baik sering terasa sederhana karena banyak keputusan rumit disembunyikan dengan rapi. Saat mengerjakan Modulia, saya belajar bahwa mengurangi pilihan tidak selalu berarti mengurangi kemampuan.</p><h3>Mulai dari pertanyaan</h3><p>Saya mewawancarai guru dan siswa untuk memahami langkah mana yang paling sering membuat mereka ragu. Dari sana, saya membuat satu alur utama dan memindahkan opsi lanjutan ke tempat yang tidak mengganggu.</p><h3>Uji, lalu perbaiki</h3><p>Prototipe diuji dengan tugas nyata. Perubahan kecil pada label dan urutan field ternyata lebih berdampak daripada menambah fitur baru.</p>',
                'body_en' => '<p>Good design often feels simple because many hard decisions are hidden neatly. While working on Modulia, I learned that reducing choices does not always mean reducing capability.</p><h3>Start with a question</h3><p>I interviewed teachers and students to understand which steps made them hesitate most. From there, I created one primary flow and moved advanced options somewhere they would not interrupt it.</p><h3>Test, then refine</h3><p>I tested the prototype with real tasks. Small changes to labels and field order had more impact than adding new features.</p>',
                'category' => 'Design notes',
                'tags' => ['UI/UX', 'Figma', 'Case study'],
                'published_at' => now()->subDays(12),
                'is_featured' => true,
            ],
            [
                'slug' => 'figma-to-nextjs',
                'title_id' => 'Dari Figma ke Next.js',
                'title_en' => 'From Figma to Next.js',
                'excerpt_id' => 'Hal-hal yang saya perhatikan saat mengubah desain menjadi komponen yang responsif.',
                'excerpt_en' => 'What I pay attention to when turning a design into responsive components.',
                'body_id' => '<p>Handoff bukan sekadar menyalin warna dan ukuran. Ada keputusan tentang spacing, state, dan perilaku di layar kecil yang perlu diselesaikan bersama.</p><h3>Tiga kebiasaan yang membantu</h3><ul><li>Menamai komponen dengan bahasa yang sama di Figma dan kode.</li><li>Menentukan breakpoint sebelum menulis CSS.</li><li>Menguji dengan keyboard dan pembaca layar sejak awal.</li></ul><p>Semakin cepat perbedaan antara desain dan implementasi ditemukan, semakin murah perbaikannya.</p>',
                'body_en' => '<p>Handoff is not just copying colours and measurements. There are decisions about spacing, states, and small-screen behaviour that need to be resolved together.</p><h3>Three habits that help</h3><ul><li>Naming components with the same language in Figma and code.</li><li>Choosing breakpoints before writing CSS.</li><li>Testing with a keyboard and screen reader from the start.</li></ul><p>The sooner gaps between design and implementation are found, the cheaper they are to fix.</p>',
                'category' => 'Development',
                'tags' => ['Next.js', 'Tailwind CSS', 'Accessibility'],
                'published_at' => now()->subDays(4),
                'is_featured' => false,
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => $post['slug']],
                $post + ['status' => Post::STATUS_PUBLISHED],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Project;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Seed the content migrated from the old Contentful space.
     */
    public function run(): void
    {
        Project::updateOrCreate(
            ['title' => 'Exam Platform'],
            [
                'description' => '',
                'tags' => ['Modulia'],
                'url' => 'https://suj.modulia.web.id/',
                'image_path' => null,
                'image_url' => null,
                'status' => Project::STATUS_PUBLISHED,
                'sort_order' => 1,
            ],
        );

        $experiences = [
            [
                'title' => 'PPLG Student',
                'company' => 'SMKN 2 Banjarmasin',
                'date_range' => '2024 - Now',
                'description' => 'Studying software engineering and UI/UX design fundamentals, building projects using Figma, Tailwind CSS, and Laravel.',
                'tags' => ['Figma', 'Tailwind CSS', 'Laravel', 'UI/UX'],
                'sort_order' => 1,
            ],
            [
                'title' => 'UI/UX Design',
                'company' => 'Modulia',
                'date_range' => '2025 - 2026',
                'description' => 'Focusing on User Experiences and Interfaces for Exam Platform.',
                'tags' => ['Figma', 'UI/UX'],
                'sort_order' => 2,
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
}

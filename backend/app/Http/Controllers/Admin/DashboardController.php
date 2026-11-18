<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\TestimonialSubmission;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = [
            'Projects' => Project::query()->count(),
            'Experience' => Experience::query()->count(),
            'Blog posts' => Post::query()->count(),
            'Skills' => Skill::query()->count(),
            'Education' => Education::query()->count(),
            'Certificates' => Certificate::query()->count(),
            'Testimonials' => Testimonial::query()->count(),
        ];

        return view('admin.dashboard', [
            'counts' => $counts,
            'pendingSubmissions' => TestimonialSubmission::query()
                ->where('status', TestimonialSubmission::STATUS_PENDING)
                ->count(),
            'unreadMessages' => Message::query()->where('is_read', false)->count(),
            'recentSubmissions' => TestimonialSubmission::query()
                ->latest()
                ->limit(5)
                ->get(),
            'recentMessages' => Message::query()
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}

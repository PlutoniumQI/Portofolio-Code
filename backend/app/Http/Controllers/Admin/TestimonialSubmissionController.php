<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TestimonialSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $query = TestimonialSubmission::query();

        if (array_key_exists($status, TestimonialSubmission::statuses())) {
            $query->where('status', $status);
        } elseif ($status === '') {
            $query->where('status', TestimonialSubmission::STATUS_PENDING);
        }

        $counts = TestimonialSubmission::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.submissions.index', [
            'submissions' => $query->latest()->paginate(20)->withQueryString(),
            'status' => $status,
            'counts' => $counts,
        ]);
    }

    public function show(TestimonialSubmission $submission): View
    {
        return view('admin.submissions.show', ['submission' => $submission]);
    }

    public function publish(TestimonialSubmission $submission): RedirectResponse
    {
        if ($submission->status === TestimonialSubmission::STATUS_APPROVED) {
            return back()->with('error', 'This testimonial has already been published.');
        }

        DB::transaction(function () use ($submission): void {
            $english = $submission->locale === 'en';

            Testimonial::create([
                'name' => $submission->name,
                'role_id' => $english ? null : $submission->role,
                'role_en' => $english ? $submission->role : null,
                'company' => $submission->company,
                // quote_id is not nullable; the English value is filled too so
                // both locales show the submitter's own words until translated.
                'quote_id' => $submission->quote,
                'quote_en' => $english ? $submission->quote : null,
                'category' => $submission->category,
                'rating' => $submission->rating,
                'sort_order' => ((int) Testimonial::query()->max('sort_order')) + 1,
                'is_active' => true,
            ]);

            $submission->forceFill([
                'status' => TestimonialSubmission::STATUS_APPROVED,
                'reviewed_at' => now(),
            ])->save();
        });

        return redirect()
            ->route('admin.submissions.show', $submission)
            ->with('status', 'Testimonial published to the live site.');
    }

    public function reject(TestimonialSubmission $submission): RedirectResponse
    {
        if ($submission->status === TestimonialSubmission::STATUS_APPROVED) {
            return back()->with('error', 'Published testimonials must be edited or deleted from the Testimonials section.');
        }

        $submission->forceFill([
            'status' => TestimonialSubmission::STATUS_REJECTED,
            'reviewed_at' => now(),
        ])->save();

        return back()->with('status', 'Submission rejected.');
    }

    public function destroy(TestimonialSubmission $submission): RedirectResponse
    {
        $submission->delete();

        return redirect()
            ->route('admin.submissions.index')
            ->with('status', 'Submission deleted.');
    }
}

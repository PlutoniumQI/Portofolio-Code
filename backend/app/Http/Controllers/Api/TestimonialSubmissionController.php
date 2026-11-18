<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimonialSubmissionRequest;
use App\Models\TestimonialSubmission;
use Illuminate\Http\JsonResponse;

class TestimonialSubmissionController extends Controller
{
    public function store(StoreTestimonialSubmissionRequest $request): JsonResponse
    {
        // Honeypot: accept silently so bots do not learn that they were caught.
        if (filled($request->input('website'))) {
            return $this->success();
        }

        TestimonialSubmission::create([
            'name' => $request->string('name')->trim()->toString(),
            'email' => $request->string('email')->trim()->toString(),
            'role' => $request->filled('role') ? $request->string('role')->trim()->toString() : null,
            'company' => $request->filled('company') ? $request->string('company')->trim()->toString() : null,
            'category' => $request->string('category')->toString(),
            'rating' => $request->integer('rating'),
            'quote' => $request->string('quote')->trim()->toString(),
            'locale' => $request->string('locale')->toString(),
            'status' => TestimonialSubmission::STATUS_PENDING,
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
        ]);

        return $this->success();
    }

    private function success(): JsonResponse
    {
        return response()->json([
            'message' => 'Thank you! Your testimonial is waiting for review.',
            'type' => 'testimonial',
        ], 201);
    }
}

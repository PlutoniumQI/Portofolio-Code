<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\TestimonialSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_submit_a_testimonial_for_review(): void
    {
        $this->postJson('/api/testimonials', [
            'name' => 'Rizky Pratama',
            'email' => 'rizky@example.com',
            'role' => 'Klien',
            'company' => 'Studio Kreatif',
            'category' => 'client',
            'rating' => 5,
            'quote' => 'Resky delivered a clean design and kept every deadline.',
            'locale' => 'id',
        ])
            ->assertCreated()
            ->assertJsonPath('type', 'testimonial');

        $submission = TestimonialSubmission::query()->sole();

        $this->assertSame('Rizky Pratama', $submission->name);
        $this->assertSame('rizky@example.com', $submission->email);
        $this->assertSame(TestimonialSubmission::STATUS_PENDING, $submission->status);
        $this->assertSame('id', $submission->locale);
        $this->assertNotNull($submission->ip_address);

        // Nothing is published until an administrator approves it.
        $this->assertSame(0, Testimonial::query()->count());
    }

    public function test_submissions_are_validated(): void
    {
        $this->postJson('/api/testimonials', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'category', 'rating', 'quote', 'locale']);

        $this->postJson('/api/testimonials', [
            'name' => 'A',
            'email' => 'not-an-email',
            'category' => 'stranger',
            'rating' => 9,
            'quote' => 'short',
            'locale' => 'fr',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'category', 'rating', 'quote', 'locale']);
    }

    public function test_the_honeypot_field_is_silently_ignored(): void
    {
        $this->postJson('/api/testimonials', [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'category' => 'client',
            'rating' => 5,
            'quote' => 'Buy cheap backlinks now and rank higher.',
            'locale' => 'en',
            'website' => 'https://spam.example.com',
        ])->assertCreated();

        $this->assertDatabaseCount('testimonial_submissions', 0);
    }

    public function test_submissions_are_rate_limited_per_client(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->postJson('/api/testimonials', [
                'name' => "Visitor {$attempt}",
                'email' => "visitor{$attempt}@example.com",
                'category' => 'peer',
                'rating' => 4,
                'quote' => "A valid testimonial number {$attempt} for the rate limit test.",
                'locale' => 'en',
            ])->assertCreated();
        }

        $this->postJson('/api/testimonials', [
            'name' => 'Visitor 6',
            'email' => 'visitor6@example.com',
            'category' => 'peer',
            'rating' => 4,
            'quote' => 'This request should be throttled by the API.',
            'locale' => 'en',
        ])->assertTooManyRequests();
    }
}

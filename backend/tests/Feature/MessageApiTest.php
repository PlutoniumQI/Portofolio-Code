<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_messages_are_stored_and_marked_readable(): void
    {
        $this->postJson('/api/messages', [
            'type' => Message::TYPE_CONTACT,
            'name' => 'Aulia Rahma',
            'email' => 'aulia@example.com',
            'subject' => 'Collaboration',
            'message' => 'I would like to discuss a small design project.',
        ])
            ->assertCreated()
            ->assertJsonPath('type', Message::TYPE_CONTACT);

        $message = Message::query()->sole();

        $this->assertSame('Aulia Rahma', $message->name);
        $this->assertSame(Message::TYPE_CONTACT, $message->type);
        $this->assertFalse($message->is_read);
        $this->assertTrue($message->is_approved);
    }

    public function test_guestbook_entries_need_approval_before_they_are_public(): void
    {
        $this->postJson('/api/messages', [
            'type' => Message::TYPE_GUESTBOOK,
            'name' => 'Nabila Putri',
            'email' => 'nabila@example.com',
            'message' => 'Good luck with the portfolio and the UKK!',
        ])->assertCreated();

        $message = Message::query()->sole();

        $this->assertFalse($message->is_approved);

        $this->getJson('/api/guestbook')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $message->update(['is_approved' => true]);

        $this->getJson('/api/guestbook')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Nabila Putri')
            ->assertJsonPath('meta.total', 1);
    }

    public function test_contact_details_are_validated(): void
    {
        $this->postJson('/api/messages', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'message']);

        $this->postJson('/api/messages', [
            'name' => 'Test',
            'email' => 'not-an-email',
            'message' => 'Hi',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'message']);
    }

    public function test_the_honeypot_field_is_silently_ignored(): void
    {
        $this->postJson('/api/messages', [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'message' => 'Buy cheap backlinks now.',
            'website' => 'https://spam.example.com',
        ])->assertCreated();

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_submissions_are_rate_limited_per_client(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->postJson('/api/messages', [
                'name' => "Visitor {$attempt}",
                'email' => "visitor{$attempt}@example.com",
                'message' => 'A valid message for the rate limit test.',
            ])->assertCreated();
        }

        $this->postJson('/api/messages', [
            'name' => 'Visitor 6',
            'email' => 'visitor6@example.com',
            'message' => 'This request should be throttled.',
        ])->assertTooManyRequests();
    }
}

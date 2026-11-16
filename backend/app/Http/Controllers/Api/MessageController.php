<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request): JsonResponse
    {
        $type = $request->input('type', Message::TYPE_CONTACT);

        // Honeypot: accept silently so bots do not learn that they were caught.
        if (filled($request->input('website'))) {
            return $this->success($type);
        }

        Message::create([
            'type' => $type,
            'name' => $request->string('name')->trim()->toString(),
            'email' => $request->string('email')->trim()->toString(),
            'subject' => $request->filled('subject')
                ? $request->string('subject')->trim()->toString()
                : null,
            'message' => $request->string('message')->trim()->toString(),
            'is_read' => false,
            'is_approved' => $type === Message::TYPE_CONTACT,
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
        ]);

        return $this->success($type);
    }

    private function success(string $type): JsonResponse
    {
        $guestbook = $type === Message::TYPE_GUESTBOOK;

        return response()->json([
            'message' => $guestbook
                ? 'Thanks! Your guestbook entry will appear after approval.'
                : 'Thanks! Your message has been sent.',
            'type' => $type,
        ], 201);
    }
}

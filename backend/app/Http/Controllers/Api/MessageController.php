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
        // Honeypot: accept silently so bots do not learn that they were caught.
        if (filled($request->input('website'))) {
            return $this->success();
        }

        Message::create([
            'type' => Message::TYPE_CONTACT,
            'name' => $request->string('name')->trim()->toString(),
            'email' => $request->string('email')->trim()->toString(),
            'subject' => $request->filled('subject')
                ? $request->string('subject')->trim()->toString()
                : null,
            'message' => $request->string('message')->trim()->toString(),
            'is_read' => false,
            'is_approved' => true,
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
        ]);

        return $this->success();
    }

    private function success(): JsonResponse
    {
        return response()->json([
            'message' => 'Thanks! Your message has been sent.',
            'type' => Message::TYPE_CONTACT,
        ], 201);
    }
}

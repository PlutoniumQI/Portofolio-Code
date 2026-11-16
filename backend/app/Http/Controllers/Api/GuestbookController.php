<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class GuestbookController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $entries = Message::query()
            ->approvedGuestbook()
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn (Message $message): array => [
                'id' => (string) $message->id,
                'name' => $message->name,
                'message' => $message->message,
                'created_at' => $message->created_at?->toIso8601String(),
            ])
            ->all();

        return response()->json([
            'data' => $entries,
            'meta' => [
                'total' => count($entries),
            ],
        ]);
    }
}

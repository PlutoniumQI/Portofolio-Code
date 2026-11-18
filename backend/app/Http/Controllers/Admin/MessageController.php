<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->string('filter')->toString();
        $term = trim((string) $request->query('q', ''));

        $query = Message::query();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        }

        if ($term !== '') {
            $query->where(function ($query) use ($term): void {
                $query
                    ->whereLike('name', "%{$term}%")
                    ->orWhereLike('email', "%{$term}%")
                    ->orWhereLike('subject', "%{$term}%");
            });
        }

        return view('admin.messages.index', [
            'messages' => $query->latest()->paginate(20)->withQueryString(),
            'filter' => $filter,
            'term' => $term,
        ]);
    }

    public function show(Message $message): View
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', ['message' => $message]);
    }

    public function update(Request $request, Message $message): RedirectResponse
    {
        $request->merge(['is_read' => $request->boolean('is_read')]);
        $request->validate(['is_read' => ['boolean']]);

        $message->update(['is_read' => $request->boolean('is_read')]);

        return back()->with('status', 'Message updated.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('status', 'Message deleted.');
    }
}

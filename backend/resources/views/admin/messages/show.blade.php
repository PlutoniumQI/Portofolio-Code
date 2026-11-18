@extends('admin.layout')

@section('title', 'Message from ' . $message->name)

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $message->subject ?: 'Message from ' . $message->name }}</h1>
            <p><a href="{{ route('admin.messages.index') }}">← Back to messages</a></p>
        </div>
        <div class="page-actions">
            <form method="POST" action="{{ route('admin.messages.update', $message) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_read" value="{{ $message->is_read ? 0 : 1 }}">
                <button type="submit" class="btn">
                    {{ $message->is_read ? 'Mark as unread' : 'Mark as read' }}
                </button>
            </form>
            <form
                method="POST"
                action="{{ route('admin.messages.destroy', $message) }}"
                data-confirm="Delete this message permanently?"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="meta" style="margin-bottom: 18px;">
        <div>
            <span>From</span>
            <strong>{{ $message->name }}</strong>
        </div>
        <div>
            <span>Email</span>
            <strong><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></strong>
        </div>
        <div>
            <span>Received</span>
            <strong>{{ $message->created_at?->format('M j, Y H:i') }}</strong>
        </div>
        <div>
            <span>Status</span>
            <strong>{{ $message->is_read ? 'Read' : 'New' }}</strong>
        </div>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h2>Message</h2>
        </div>
        <div class="panel-body">
            <div class="quote-box">{{ $message->message }}</div>

            @if ($message->ip_address || $message->user_agent)
                <p class="help mt-2">
                    @if ($message->ip_address)
                        IP: <span class="mono">{{ $message->ip_address }}</span>
                    @endif
                    @if ($message->user_agent)
                        · {{ \Illuminate\Support\Str::limit($message->user_agent, 140) }}
                    @endif
                </p>
            @endif
        </div>
    </section>

    <div class="row">
        <a class="btn primary" href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->subject ?: 'Your message')) }}">
            Reply by email
        </a>
    </div>
@endsection

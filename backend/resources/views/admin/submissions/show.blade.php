@extends('admin.layout')

@section('title', 'Review testimonial')

@section('content')
    <div class="page-head">
        <div>
            <h1>Review testimonial</h1>
            <p><a href="{{ route('admin.submissions.index') }}">← Back to the review queue</a></p>
        </div>
        <div class="page-actions">
            @if ($submission->status !== \App\Models\TestimonialSubmission::STATUS_APPROVED)
                <form method="POST" action="{{ route('admin.submissions.publish', $submission) }}">
                    @csrf
                    <button type="submit" class="btn primary">
                        {{ $submission->status === \App\Models\TestimonialSubmission::STATUS_REJECTED ? 'Publish anyway' : 'Publish to site' }}
                    </button>
                </form>

                @if ($submission->status !== \App\Models\TestimonialSubmission::STATUS_REJECTED)
                    <form
                        method="POST"
                        action="{{ route('admin.submissions.reject', $submission) }}"
                        data-confirm="Reject this submission? You can still publish it later."
                    >
                        @csrf
                        <button type="submit" class="btn danger">Reject</button>
                    </form>
                @endif
            @endif

            <form
                method="POST"
                action="{{ route('admin.submissions.destroy', $submission) }}"
                data-confirm="Delete this submission permanently?"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn ghost">Delete</button>
            </form>
        </div>
    </div>

    @php
        $statusPill = match ($submission->status) {
            'approved' => 'pill-on',
            'rejected' => 'pill-danger',
            default => 'pill-warn',
        };
    @endphp

    <div class="meta" style="margin-bottom: 18px;">
        <div>
            <span>Name</span>
            <strong>{{ $submission->name }}</strong>
        </div>
        <div>
            <span>Role</span>
            <strong>{{ $submission->role ?: '—' }}</strong>
        </div>
        <div>
            <span>Company / school</span>
            <strong>{{ $submission->company ?: '—' }}</strong>
        </div>
        <div>
            <span>Category</span>
            <strong>{{ \App\Models\Testimonial::categories()[$submission->category] ?? $submission->category }}</strong>
        </div>
        <div>
            <span>Rating</span>
            <strong>{{ str_repeat('★', $submission->rating) }}{{ str_repeat('☆', 5 - $submission->rating) }}</strong>
        </div>
        <div>
            <span>Language</span>
            <strong>{{ strtoupper($submission->locale) }}</strong>
        </div>
        <div>
            <span>Submitted</span>
            <strong>{{ $submission->created_at?->format('M j, Y H:i') }}</strong>
        </div>
        <div>
            <span>Status</span>
            <strong><span class="pill {{ $statusPill }}">{{ \App\Models\TestimonialSubmission::statuses()[$submission->status] ?? $submission->status }}</span></strong>
        </div>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h2>Quote</h2>
            <p>This text is shown verbatim on the testimonial cards.</p>
        </div>
        <div class="panel-body">
            <div class="quote-box">{{ $submission->quote }}</div>

            @if ($submission->reviewed_at)
                <p class="help mt-2">Reviewed {{ $submission->reviewed_at->format('M j, Y H:i') }}.</p>
            @endif
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <h2>Private contact</h2>
            <p>Only visible to you. The email address is never published.</p>
        </div>
        <div class="panel-body">
            <div class="meta">
                <div>
                    <span>Email</span>
                    <strong><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></strong>
                </div>
                <div>
                    <span>IP address</span>
                    <strong class="mono">{{ $submission->ip_address ?: '—' }}</strong>
                </div>
                <div>
                    <span>User agent</span>
                    <strong class="small">{{ \Illuminate\Support\Str::limit($submission->user_agent ?: '—', 100) }}</strong>
                </div>
            </div>

            @if ($submission->status === \App\Models\TestimonialSubmission::STATUS_APPROVED)
                <p class="help mt-2">
                    This testimonial is live. Edit its wording, avatar, or order from
                    <a href="{{ route('admin.testimonials.index') }}">Testimonials</a>.
                </p>
            @endif
        </div>
    </section>
@endsection

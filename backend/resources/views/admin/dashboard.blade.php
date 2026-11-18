@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard</h1>
            <p>Everything published on the site starts here. Saves refresh the live pages automatically.</p>
        </div>
        <div class="page-actions">
            <a class="btn" href="{{ route('admin.publish.index') }}">Publish changes</a>
            <a class="btn primary" href="{{ route('admin.projects.create') }}">New project</a>
        </div>
    </div>

    <div class="stat-grid">
        @foreach ($counts as $label => $count)
            <div class="stat-card">
                <span>{{ $label }}</span>
                <strong>{{ $count }}</strong>
            </div>
        @endforeach
        <div class="stat-card {{ $pendingSubmissions > 0 ? 'highlight' : '' }}">
            <span>Pending testimonials</span>
            <strong>{{ $pendingSubmissions }}</strong>
        </div>
        <div class="stat-card {{ $unreadMessages > 0 ? 'highlight' : '' }}">
            <span>Unread messages</span>
            <strong>{{ $unreadMessages }}</strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head" style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <div>
                <h2>Testimonial submissions</h2>
                <p>Approve the ones you want to show on the site.</p>
            </div>
            <a class="btn sm" href="{{ route('admin.submissions.index') }}">Open queue</a>
        </div>

        @if ($recentSubmissions->isEmpty())
            <div class="empty">No testimonials have been submitted yet.</div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Rating</th>
                            <th>Received</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentSubmissions as $submission)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.submissions.show', $submission) }}">{{ $submission->name }}</a>
                                    @if ($submission->company)
                                        <div class="faint small">{{ $submission->company }}</div>
                                    @endif
                                </td>
                                <td>{{ \App\Models\Testimonial::categories()[$submission->category] ?? $submission->category }}</td>
                                <td>{{ $submission->rating }}/5</td>
                                <td>{{ $submission->created_at?->diffForHumans() }}</td>
                                <td>
                                    @php
                                        $pill = match ($submission->status) {
                                            'approved' => 'pill-on',
                                            'rejected' => 'pill-danger',
                                            default => 'pill-warn',
                                        };
                                    @endphp
                                    <span class="pill {{ $pill }}">
                                        {{ \App\Models\TestimonialSubmission::statuses()[$submission->status] ?? $submission->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head" style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <div>
                <h2>Recent messages</h2>
                <p>Contact form submissions from the public site.</p>
            </div>
            <a class="btn sm" href="{{ route('admin.messages.index') }}">Open inbox</a>
        </div>

        @if ($recentMessages->isEmpty())
            <div class="empty">No messages yet.</div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Received</th>
                            <th>Read</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentMessages as $message)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message) }}">{{ $message->name }}</a>
                                    <div class="faint small">{{ $message->email }}</div>
                                </td>
                                <td>{{ $message->subject ?: '—' }}</td>
                                <td>{{ $message->created_at?->diffForHumans() }}</td>
                                <td>
                                    <span class="pill {{ $message->is_read ? 'pill-on' : 'pill-warn' }}">
                                        {{ $message->is_read ? 'Read' : 'New' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

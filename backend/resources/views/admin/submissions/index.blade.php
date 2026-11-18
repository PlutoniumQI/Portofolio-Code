@extends('admin.layout')

@section('title', 'Testimonial submissions')

@section('content')
    <div class="page-head">
        <div>
            <h1>Testimonial submissions</h1>
            <p>Review testimonials written by visitors. Publishing one adds it to the live site immediately.</p>
        </div>
        <div class="page-actions">
            <a class="btn" href="{{ route('admin.testimonials.index') }}">Manage published testimonials</a>
        </div>
    </div>

    @php
        $tabs = [
            '' => ['label' => 'Pending', 'count' => $counts['pending'] ?? 0],
            'approved' => ['label' => 'Published', 'count' => $counts['approved'] ?? 0],
            'rejected' => ['label' => 'Rejected', 'count' => $counts['rejected'] ?? 0],
            'all' => ['label' => 'All', 'count' => $counts->sum()],
        ];
    @endphp

    <div class="filter-tabs">
        @foreach ($tabs as $key => $tab)
            <a
                href="{{ route('admin.submissions.index', $key === '' ? [] : ['status' => $key]) }}"
                class="{{ $status === $key ? 'active' : '' }}"
            >
                {{ $tab['label'] }} ({{ $tab['count'] }})
            </a>
        @endforeach
    </div>

    <div class="panel">
        @if ($submissions->isEmpty())
            <div class="empty">No submissions in this view.</div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Rating</th>
                            <th>Language</th>
                            <th>Received</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $submission)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.submissions.show', $submission) }}">{{ $submission->name }}</a>
                                    @if ($submission->company)
                                        <div class="faint small">{{ $submission->company }}</div>
                                    @endif
                                </td>
                                <td>{{ \App\Models\Testimonial::categories()[$submission->category] ?? $submission->category }}</td>
                                <td>{{ $submission->rating }}/5</td>
                                <td>{{ strtoupper($submission->locale) }}</td>
                                <td>{{ $submission->created_at?->format('M j, Y H:i') }}</td>
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
                                <td class="right">
                                    <div class="actions">
                                        <a class="btn sm" href="{{ route('admin.submissions.show', $submission) }}">Review</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $submissions->onEachSide(1)->links('admin.partials.pagination') }}
        @endif
    </div>
@endsection

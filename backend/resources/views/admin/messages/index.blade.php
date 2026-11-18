@extends('admin.layout')

@section('title', 'Messages')

@section('content')
    <div class="page-head">
        <div>
            <h1>Messages</h1>
            <p>Contact form submissions from the public site.</p>
        </div>
    </div>

    <div class="filter-tabs">
        <a href="{{ route('admin.messages.index') }}" class="{{ $filter === '' ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'active' : '' }}">
            Unread
        </a>
    </div>

    <form class="search-form" method="GET" action="{{ route('admin.messages.index') }}">
        @if ($filter !== '')
            <input type="hidden" name="filter" value="{{ $filter }}">
        @endif
        <input class="input" type="search" name="q" value="{{ $term }}" placeholder="Search name, email, or subject…">
        <button class="btn" type="submit">Search</button>
        @if ($term !== '')
            <a class="btn ghost" href="{{ route('admin.messages.index', $filter !== '' ? ['filter' => $filter] : []) }}">Clear</a>
        @endif
    </form>

    <div class="panel">
        @if ($messages->isEmpty())
            <div class="empty">No messages match this view.</div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Received</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message) }}">{{ $message->name }}</a>
                                    <div class="faint small">{{ $message->email }}</div>
                                </td>
                                <td>{{ $message->subject ?: '—' }}</td>
                                <td>{{ $message->created_at?->format('M j, Y H:i') }}</td>
                                <td>
                                    <span class="pill {{ $message->is_read ? 'pill-off' : 'pill-warn' }}">
                                        {{ $message->is_read ? 'Read' : 'New' }}
                                    </span>
                                </td>
                                <td class="right">
                                    <div class="actions">
                                        <a class="btn sm" href="{{ route('admin.messages.show', $message) }}">Open</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $messages->onEachSide(1)->links('admin.partials.pagination') }}
        @endif
    </div>
@endsection

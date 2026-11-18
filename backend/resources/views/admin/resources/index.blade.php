@extends('admin.layout')

@section('title', $definition['label'])

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $definition['label'] }}</h1>
            <p>
                {{ $definition['reorder'] ?? false ? 'Drag the handle to reorder. Changes save automatically.' : 'Manage the entries shown on the public site.' }}
            </p>
        </div>
        <div class="page-actions">
            <a class="btn primary" href="{{ route("admin.{$resource}.create") }}">New {{ $definition['singular'] }}</a>
        </div>
    </div>

    @if (! empty($definition['search']))
        <form class="search-form" method="GET" action="{{ route("admin.{$resource}.index") }}">
            <input
                class="input"
                type="search"
                name="q"
                value="{{ $term }}"
                placeholder="Search {{ strtolower($definition['label']) }}…"
            >
            <button class="btn" type="submit">Search</button>
            @if ($term !== '')
                <a class="btn ghost" href="{{ route("admin.{$resource}.index") }}">Clear</a>
            @endif
        </form>
    @endif

    <div class="panel">
        @if ($records->isEmpty())
            <div class="empty">
                No {{ strtolower($definition['label']) }} yet.
                <div class="mt-1">
                    <a class="btn sm primary" href="{{ route("admin.{$resource}.create") }}">Create the first {{ strtolower($definition['singular']) }}</a>
                </div>
            </div>
        @else
            <div class="table-wrap">
                <table
                    class="data"
                    @if ($definition['reorder'] ?? false)
                        data-reorder-url="{{ route("admin.{$resource}.reorder") }}"
                    @endif
                >
                    <thead>
                        <tr>
                            @if ($definition['reorder'] ?? false)
                                <th class="drag-col"><span class="sr-only">Reorder</span></th>
                            @endif
                            @foreach ($definition['columns'] as $column)
                                <th>{{ $column['label'] }}</th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody @if ($definition['reorder'] ?? false) data-sortable @endif>
                        @foreach ($records as $record)
                            <tr data-id="{{ $record->getKey() }}">
                                @if ($definition['reorder'] ?? false)
                                    <td>
                                        <button type="button" class="drag-handle" aria-label="Drag to reorder">⠿</button>
                                    </td>
                                @endif

                                @foreach ($definition['columns'] as $column)
                                    @php
                                        $name = $column['name'];
                                        $type = $column['type'] ?? 'text';
                                        $value = $record->{$name};
                                    @endphp
                                    <td>
                                        @if ($type === 'bool')
                                            <span class="pill {{ $value ? 'pill-on' : 'pill-off' }}">
                                                {{ $value ? 'On' : 'Off' }}
                                            </span>
                                        @elseif ($type === 'map')
                                            {{ ($column['map'] ?? [])[$value] ?? ($value ?: '—') }}
                                        @elseif ($type === 'date')
                                            {{ $value ? \Illuminate\Support\Carbon::parse($value)->format('M j, Y') : '—' }}
                                        @elseif ($type === 'datetime')
                                            {{ $value ? \Illuminate\Support\Carbon::parse($value)->format('M j, Y H:i') : '—' }}
                                        @else
                                            {{ \Illuminate\Support\Str::limit((string) ($value ?? ''), 70) ?: '—' }}
                                        @endif
                                    </td>
                                @endforeach

                                <td class="right">
                                    <div class="actions">
                                        <a class="btn sm" href="{{ route("admin.{$resource}.edit", $record) }}">Edit</a>
                                        <form
                                            method="POST"
                                            action="{{ route("admin.{$resource}.destroy", $record) }}"
                                            data-confirm="Delete this {{ strtolower($definition['singular']) }}? This cannot be undone."
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn sm danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($records instanceof \Illuminate\Contracts\Pagination\Paginator)
                {{ $records->onEachSide(1)->links('admin.partials.pagination') }}
            @endif
        @endif
    </div>
@endsection

@extends('admin.layout')

@section('title', ($record->exists ? 'Edit' : 'New') . ' ' . $definition['singular'])

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $record->exists ? 'Edit' : 'New' }} {{ $definition['singular'] }}</h1>
            <p><a href="{{ route("admin.{$resource}.index") }}">← Back to {{ strtolower($definition['label']) }}</a></p>
        </div>
        @if ($record->exists)
            <div class="page-actions">
                <form
                    method="POST"
                    action="{{ route("admin.{$resource}.destroy", $record) }}"
                    data-confirm="Delete this {{ strtolower($definition['singular']) }}? This cannot be undone."
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn danger">Delete</button>
                </form>
            </div>
        @endif
    </div>

    <form
        method="POST"
        action="{{ $record->exists ? route("admin.{$resource}.update", $record) : route("admin.{$resource}.store") }}"
    >
        @csrf
        @if ($record->exists)
            @method('PUT')
        @endif

        @foreach ($definition['groups'] as $group)
            <section class="panel">
                <div class="panel-head">
                    <h2>{{ $group['title'] }}</h2>
                    @if (! empty($group['description']))
                        <p>{{ $group['description'] }}</p>
                    @endif
                </div>
                <div class="panel-body">
                    <div class="form-grid">
                        @foreach ($group['fields'] as $field)
                            @include('admin.partials.field', ['field' => $field, 'record' => $record])
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach

        <div class="form-actions">
            <button type="submit" class="btn primary">
                {{ $record->exists ? 'Save changes' : 'Create ' . strtolower($definition['singular']) }}
            </button>
            <a class="btn" href="{{ route("admin.{$resource}.index") }}">Cancel</a>
        </div>
    </form>
@endsection

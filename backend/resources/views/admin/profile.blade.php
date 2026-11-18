@extends('admin.layout')

@section('title', 'Site profile & CV')

@section('content')
    <div class="page-head">
        <div>
            <h1>Site profile &amp; CV</h1>
            <p>The identity, bio, links, and files used across every public page.</p>
        </div>
        <div class="page-actions">
            <a class="btn" href="{{ route('admin.publish.index') }}">Publish changes</a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PUT')

        @foreach ($groups as $group)
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
            <button type="submit" class="btn primary">Save profile</button>
        </div>
    </form>
@endsection

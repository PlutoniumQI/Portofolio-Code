@extends('admin.layout')

@section('title', 'Publish changes')

@section('content')
    <div class="page-head">
        <div>
            <h1>Publish changes</h1>
            <p>Saving any content already tells the live site to refresh. Use this page to force it manually.</p>
        </div>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h2>Live site</h2>
            <p>The frontend rebuilds cached pages in the background. Visitors see fresh content within seconds.</p>
        </div>
        <div class="panel-body">
            <div class="meta">
                <div>
                    <span>Frontend</span>
                    <strong>{{ config('portfolio.frontend.url') }}</strong>
                </div>
                <div>
                    <span>Revalidation endpoint</span>
                    <strong class="mono small">{{ $url ?: 'Not configured' }}</strong>
                </div>
                <div>
                    <span>Status</span>
                    <strong>
                        @if ($configured)
                            <span class="pill pill-on">Connected</span>
                        @else
                            <span class="pill pill-danger">Missing configuration</span>
                        @endif
                    </strong>
                </div>
            </div>

            @unless ($configured)
                <div class="flash error mt-2">
                    Set <span class="mono">FRONTEND_REVALIDATE_URL</span> and
                    <span class="mono">FRONTEND_REVALIDATE_SECRET</span> to enable publishing.
                </div>
            @endunless

            <form method="POST" action="{{ route('admin.publish') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn primary" @disabled(! $configured)>
                    Refresh the live site now
                </button>
            </form>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <h2>What gets refreshed</h2>
        </div>
        <div class="panel-body">
            <p class="muted">
                Every public route in both languages, including project and blog detail pages:
            </p>
            <div class="row mt-1">
                @foreach (\App\Services\FrontendRevalidator::PATHS as $path)
                    <span class="pill pill-off mono">{{ $path }}</span>
                @endforeach
            </div>
            <p class="help mt-2">
                If publishing fails, confirm the frontend deployment is reachable and the secret matches
                <span class="mono">FRONTEND_REVALIDATE_SECRET</span> on Vercel.
            </p>
        </div>
    </section>
@endsection

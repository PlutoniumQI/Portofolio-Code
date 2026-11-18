<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in · Portfolio Admin</title>
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-brand">
            <strong>Portfolio</strong>
            <span>Admin</span>
        </div>
        <h1>Sign in</h1>
        <p>Manage the site content, messages, and testimonial submissions.</p>

        @if ($errors->any())
            <div class="flash error" style="margin-top: 16px;">
                <ul style="margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input
                    class="input"
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    class="input"
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <label class="check" style="padding-top: 0;">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                <span>Remember this device</span>
            </label>

            <button type="submit" class="btn primary">Sign in</button>
        </form>

        <div class="login-foot">Authorised access only.</div>
    </div>
</div>
</body>
</html>

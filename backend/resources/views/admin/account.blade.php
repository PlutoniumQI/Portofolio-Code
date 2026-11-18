@extends('admin.layout')

@section('title', 'Profile & password')

@section('content')
    <div class="page-head">
        <div>
            <h1>Profile &amp; password</h1>
            <p>The account used to sign in to this admin panel.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.account.update') }}">
        @csrf
        @method('PUT')

        <section class="panel">
            <div class="panel-head">
                <h2>Account details</h2>
            </div>
            <div class="panel-body">
                <div class="form-grid">
                    <div class="field">
                        <label for="name">Name</label>
                        <input class="input" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input class="input" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Change password</h2>
                <p>Leave these fields empty to keep the current password.</p>
            </div>
            <div class="panel-body">
                <div class="form-grid">
                    <div class="field">
                        <label for="current_password">Current password</label>
                        <input class="input" type="password" id="current_password" name="current_password" autocomplete="current-password">
                        @error('current_password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field"></div>

                    <div class="field">
                        <label for="password">New password</label>
                        <input class="input" type="password" id="password" name="password" autocomplete="new-password">
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm new password</label>
                        <input class="input" type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>
            </div>
        </section>

        <div class="form-actions">
            <button type="submit" class="btn primary">Save account</button>
        </div>
    </form>
@endsection

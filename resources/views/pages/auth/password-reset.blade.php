@extends('layouts.app')
@section('title', 'Reset password — effortlessNote')
@section('body-class', 'workspace-page')
@section('content')
<main class="auth-page"><div class="auth-card">
    <span class="section-label">LESS EFFORT. MORE YOU.</span>
    <h1>Reset password.</h1>
    <p>Choose a new password for your account.</p>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        @if ($errors->any()) <div class="auth-errors" role="alert">{{ $errors->first() }}</div> @endif
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" required maxlength="254">
        <label for="password">New password</label>
        <div class="auth-password-field"><input id="password" name="password" type="password" autocomplete="new-password" required minlength="4"><button class="auth-password-toggle" type="button" data-password-toggle="password" aria-label="Show Password" title="Show Password" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></button></div>
        <label for="password_confirmation">Confirm password</label>
        <div class="auth-password-field"><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="4"><button class="auth-password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show Password" title="Show Password" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></button></div>
        <button class="button dark" type="submit">Reset password</button>
    </form>
</div></main>
@endsection

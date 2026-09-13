@extends('layouts.app')
@section('title', 'Forgot password — effortlessNote')
@section('body-class', 'workspace-page')
@section('content')
<main class="auth-page"><div class="auth-card">
    <span class="section-label">LESS EFFORT. MORE YOU.</span>
    <h1>Forgot password?</h1>
    <p>Enter your email and we’ll send you a link to reset it.</p>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        @if (session('status')) <div class="auth-status" role="status">{{ session('status') }}</div> @endif
        @if ($errors->any()) <div class="auth-errors" role="alert">{{ $errors->first() }}</div> @endif
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="254">
        <button class="button dark" type="submit">Send reset link</button>
    </form>
    <p class="auth-back"><a href="{{ route('login') }}">Back to log in</a></p>
</div></main>
@endsection

@extends('layouts.app')
@section('title', ($register ? 'Sign up' : 'Log in').' — effortlessNote')
@section('body-class', 'workspace-page')
@section('content')
<main class="auth-page">
    <div class="auth-card">
        <span class="section-label">LESS EFFORT. MORE YOU.</span>
        <h1>{{ $register ? 'A fresh start.' : 'Welcome back.' }}</h1>
        <p>{{ $register ? 'Create your account and make room for your thoughts.' : 'Log in to your little space for big ideas.' }}</p>
        <form method="POST" action="{{ route($register ? 'register.store' : 'login.store') }}">
            @csrf
            @if ($errors->any())
                <div class="auth-errors" role="alert">{{ $errors->first() }}</div>
            @endif
            @if (session('status'))
                <div class="auth-status" role="status">{{ session('status') }}</div>
            @endif
            @if ($register)
                <label for="name">Your name</label>
                <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="255">
            @endif
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="254">
            <label for="password">Password</label>
            <div class="auth-password-field">
                <input id="password" name="password" type="password" autocomplete="{{ $register ? 'new-password' : 'current-password' }}" required @if($register) minlength="4" @endif>
                <button class="auth-password-toggle" type="button" data-password-toggle="password" aria-label="Show Password" title="Show Password" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            @if ($register)
                <label for="password_confirmation">Confirm password</label>
                <div class="auth-password-field">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="4">
                    <button class="auth-password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show Password" title="Show Password" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
            @else
                <div class="auth-options"><label class="auth-remember"><input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> Remember me</label><a href="{{ route('password.request') }}">Forgot password?</a></div>
            @endif
            <button class="button dark" type="submit">{{ $register ? 'Create account' : 'Log in' }}</button>
        </form>
        <p class="auth-switch">{{ $register ? 'Already have an account?' : 'New here?' }} <a href="{{ route($register ? 'login' : 'register') }}">{{ $register ? 'Log in' : 'Sign up' }}</a></p>
    </div>
</main>
@endsection

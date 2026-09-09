@extends('layouts.app')
@section('title', ($register ? 'Sign up' : 'Log in').' — effortNote')
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
            @if ($register)
                <label for="name">Your name</label>
                <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="255">
            @endif
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="254">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="{{ $register ? 'new-password' : 'current-password' }}" required @if($register) minlength="4" @endif>
            @if ($register)
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="4">
            @else
                <label class="auth-remember"><input type="checkbox" name="remember" value="1"> Keep me logged in</label>
            @endif
            <button class="button dark" type="submit">{{ $register ? 'Create account' : 'Log in' }}</button>
        </form>
        <p class="auth-switch">{{ $register ? 'Already have an account?' : 'New here?' }} <a href="{{ route($register ? 'login' : 'register') }}">{{ $register ? 'Log in' : 'Sign up' }}</a></p>
    </div>
</main>
@endsection

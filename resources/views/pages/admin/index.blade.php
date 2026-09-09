@extends('layouts.app')
@section('title', 'Admin dashboard — effortNote')
@section('body-class', 'workspace-page')
@section('content')
<main class="auth-page">
    <div class="auth-card">
        <span class="section-label">ADMIN DASHBOARD</span>
        <h1>Hello, {{ auth()->user()->name }}.</h1>
        <p>You are signed in as an administrator. This page is restricted to admin accounts.</p>
        <a class="button dark" href="{{ route('notes') }}">Open notes</a>
    </div>
</main>
@endsection

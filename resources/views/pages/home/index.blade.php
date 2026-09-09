@extends('layouts.app')
@section('html-class', 'home-page')

@section('content')
<main>
    @include('pages.home.sections.hero')
    @include('pages.home.sections.trust-strip')
    @include('pages.home.sections.features')
    @include('pages.home.sections.platforms')
    @include('pages.home.sections.pricing')
    @include('pages.home.sections.how-it-works')
    @include('pages.home.sections.faq')
    @include('pages.home.sections.call-to-action')
</main>
@include('pages.home.sections.waitlist-dialog')
@endsection

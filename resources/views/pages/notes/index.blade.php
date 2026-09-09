@extends('layouts.app')
@section('title', 'Your workspace — easyNote')
@section('body-class', 'workspace-page')
@section('content')
<main class="workspace">
    @include('pages.notes.sections.heading')
    <div class="workspace-panel">
        @include('pages.notes.sections.sidebar')
        @include('pages.notes.sections.editor')
    </div>
</main>
@endsection

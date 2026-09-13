@extends('layouts.app')
@section('title', 'Your workspace — e̶f̶f̶o̶r̶t̶Note')
@section('body-class', 'workspace-page')
@section('content')
<main class="workspace">
    @include('pages.notes.sections.heading')
    <div class="workspace-panel">
        @include('pages.notes.sections.sidebar')
        @include('pages.notes.sections.editor')
    </div>
    <dialog class="note-limit-dialog" id="note-limit-dialog" aria-labelledby="note-limit-title">
        <div class="note-limit-content">
            <span class="section-label">YOUR SPACE IS FULL</span>
            <h2 id="note-limit-title">25 notes, 25 ideas worth keeping.</h2>
            <p>You’ve reached the Free plan’s 25-note limit. Pro purchasing is coming soon; you can view the plans or delete a note to make room.</p>
            <div class="note-limit-actions">
                <button class="button light" type="button" id="close-note-limit">Keep writing</button>
                <a class="button dark" href="{{ route('home') }}#pricing">Buy Pro</a>
            </div>
        </div>
    </dialog>
</main>
@endsection

<!DOCTYPE html>
<html lang="en" class="@yield('html-class')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="description" content="Take notes. Leave the effort. Capture and organize your thoughts with effortlessNote.">
    <title>@yield('title', 'effortlessNote — Take notes. Leave the effort.')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class')">
<div class="scroll-progress" aria-hidden="true"></div>
<div class="page-shell">
@include('partials.header')
@yield('content')
@include('partials.footer')
</div>
<div class="toast" role="status" aria-live="polite"></div>
</body>
</html>

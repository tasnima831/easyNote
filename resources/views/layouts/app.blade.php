<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A little space for your biggest ideas. Capture, organize, and find clarity with easyNote.">
    <title>@yield('title', 'easyNote — A little space for big ideas')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
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

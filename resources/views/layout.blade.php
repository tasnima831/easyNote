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
<header class="site-header">
    <a class="brand" href="/" aria-label="easyNote home"><span class="brand-mark">n<span>?</span></span>easyNote<span class="brand-dot">®</span></a>
    <nav aria-label="Main navigation"><a href="/#features">Features</a><a href="/#how-it-works">How it works</a><a href="/#pricing">Pricing</a></nav>
    <a class="button small dark" href="/notes">Start writing <span>?</span></a>
</header>
@yield('content')
<footer class="footer">
    <div class="footer-top"><div><a class="brand" href="/"><span class="brand-mark">n<span>?</span></span>easyNote</a><p>A little space for your biggest ideas.<br>Less noise. More you.</p></div><div><h4>Explore</h4><a href="/#features">Features</a><a href="/#how-it-works">How it works</a><a href="/#pricing">Pricing</a></div><div><h4>Your space</h4><a href="/notes">My notes</a><a href="/#platforms">Write anywhere</a><a href="/#faq">Questions & answers</a></div><div class="footer-message">Make room<br>for a little <em>clarity.</em> ?</div></div>
    <div class="footer-bottom"><span>© {{ date('Y') }} easyNote. Made for a clearer mind.</span><span>Thoughtfully simple. By design. <span class="tiny-star">?</span></span></div>
</footer>
</div>
<div class="toast" role="status" aria-live="polite"></div>
</body>
</html>

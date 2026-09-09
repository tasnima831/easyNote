<header class="site-header">
    <a class="brand" href="/" aria-label="effortNote home"><span class="brand-mark">n<span aria-hidden="true">&#8599;</span></span><span class="brand-wordmark"><span class="brand-effort">effort</span>Note</span></a>
    <nav aria-label="Main navigation"><a href="/#features">Features</a><a href="/#how-it-works">How it works</a><a href="/#pricing">Pricing</a></nav>
    <div class="header-auth">
        @guest
            <a href="{{ route('login') }}">Log in</a>
            <a class="button small dark" href="{{ route('register') }}">Sign up</a>
        @else
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('notes') }}">{{ auth()->user()->role === 'admin' ? 'Dashboard' : 'My notes' }}</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="button small dark" type="submit">Log out</button></form>
        @endguest
    </div>
</header>

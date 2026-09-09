const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
if ('IntersectionObserver' in window) {
    document.documentElement.classList.add('motion-ready');
    const observer = new IntersectionObserver(entries => entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            observer.unobserve(entry.target);
        }
    }), { threshold: 0.08 });
    document.querySelectorAll('[data-reveal]').forEach(element => observer.observe(element));
}
let scrollQueued = false;
const navigationLinks = [...document.querySelectorAll('.site-header nav a')];
const navigationSections = navigationLinks.map(link => ({
    link,
    section: document.getElementById(new URL(link.href).hash.slice(1)),
})).filter(item => item.section);
let clickedNavigationUntil = 0;
function highlightNavigation(selectedLink) {
    navigationLinks.forEach(link => {
        link.classList.toggle('is-active', link === selectedLink);
        if (link === selectedLink) link.setAttribute('aria-current', 'location');
        else link.removeAttribute('aria-current');
    });
}
navigationLinks.forEach(link => link.addEventListener('click', event => {
    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    clickedNavigationUntil = performance.now() + 1400;
    highlightNavigation(link);
    setTimeout(updateScroll, 1450);
}));
function updateScroll() {
    const hero = document.querySelector('.hero');
    const heroIsBehindHeader = hero ? hero.getBoundingClientRect().bottom <= 92 : scrollY > 24;
    document.querySelector('.site-header')?.classList.toggle('is-scrolled', heroIsBehindHeader);
    if (performance.now() >= clickedNavigationUntil) {
        const readingLine = Math.min(innerHeight * 0.3, 220);
        const currentSection = navigationSections.find(({ section }) => {
            const bounds = section.getBoundingClientRect();
            return bounds.top <= readingLine && bounds.bottom > readingLine;
        });
        highlightNavigation(currentSection?.link);
    }
    const maximum = document.documentElement.scrollHeight - innerHeight;
    document.querySelector('.scroll-progress').style.width = `${maximum > 0 ? scrollY / maximum * 100 : 0}%`;
    const mountain = document.querySelector('.mountain-scene');
    if (mountain && !reducedMotion.matches && scrollY < innerHeight * 1.5) mountain.style.transform = `translateY(${scrollY * 0.15}px)`;
    scrollQueued = false;
}
window.addEventListener('scroll', () => {
    if (!scrollQueued) { scrollQueued = true; requestAnimationFrame(updateScroll); }
}, { passive: true });
updateScroll();
window.addEventListener('resize', updateScroll);
document.addEventListener('click', event => {
    const link = event.target.closest('a');
    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target || link.hasAttribute('download') || reducedMotion.matches) return;
    const target = new URL(link.href, location.href);
    if (target.origin !== location.origin || target.pathname === location.pathname || 'onpageswap' in window) return;
    event.preventDefault();
    document.body.classList.add('page-leaving');
    setTimeout(() => location.assign(target.href), 180);
});
window.addEventListener('pageshow', () => {
    document.body.classList.remove('page-leaving');
    updateScroll();
});

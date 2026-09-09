import './bootstrap';

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const toast = (message) => {
    const element = document.querySelector('.toast');
    element.textContent = message;
    element.classList.add('visible');
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => element.classList.remove('visible'), 4200);
};
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
function updateScroll() {
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
document.addEventListener('click', event => {
    const link = event.target.closest('a');
    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target || link.hasAttribute('download') || reducedMotion.matches) return;
    const target = new URL(link.href, location.href);
    if (target.origin !== location.origin || target.pathname === location.pathname || 'onpageswap' in window) return;
    event.preventDefault();
    document.body.classList.add('page-leaving');
    setTimeout(() => location.assign(target.href), 180);
});
window.addEventListener('pageshow', () => document.body.classList.remove('page-leaving'));
document.querySelectorAll('[data-style]').forEach(button => button.addEventListener('click', () => {
    const heading = document.querySelector('.hero h1');
    const style = button.dataset.style;
    if (style === 'reset') { heading.removeAttribute('style'); return; }
    if (style === 'bold') heading.style.fontWeight = heading.style.fontWeight === '700' ? '500' : '700';
    if (style === 'italic') heading.style.fontStyle = heading.style.fontStyle === 'italic' ? 'normal' : 'italic';
    if (style === 'serif' || style === 'sans') heading.style.fontFamily = heading.style.fontFamily.includes('Georgia') ? '' : 'Georgia, serif';
    if (style === 'color') heading.style.color = heading.style.color ? '' : '#747b5d';
}));
document.querySelectorAll('[data-billing]').forEach(button => button.addEventListener('click', () => {
    const period = button.dataset.billing;
    document.querySelectorAll('[data-billing]').forEach(item => { item.classList.toggle('active', item === button); item.setAttribute('aria-pressed', String(item === button)); });
    document.querySelectorAll('.price-number').forEach(number => number.textContent = number.dataset[period]);
    document.querySelectorAll('.billing-note').forEach((note, index) => note.textContent = period === 'yearly' ? `${index === 0 ? '$48 billed yearly' : '$108 per person billed yearly'} · Coming soon` : 'Billed monthly · Coming soon');
}));
const dialog = document.querySelector('#waitlist-dialog');
let selectedPlan = 'Plus';
document.querySelectorAll('[data-plan]').forEach(button => button.addEventListener('click', () => {
    selectedPlan = button.dataset.plan;
    document.querySelector('#waitlist-copy').textContent = `Join the ${selectedPlan} waitlist and be first to hear when your new space is ready.`;
    dialog.showModal();
}));
document.querySelector('.dialog-close')?.addEventListener('click', () => dialog.close());
document.querySelector('#waitlist-form')?.addEventListener('submit', async event => {
    event.preventDefault();
    const submit = event.target.querySelector('[type="submit"]');
    submit.disabled = true;
    try {
        await window.axios.post('/waitlist', { email: document.querySelector('#waitlist-email').value, plan: selectedPlan });
        dialog.close(); event.target.reset(); toast('You’re on the list. Here’s to what’s next.');
    } catch (error) { toast(error.response?.data?.message || 'Could not join right now. Please try again.'); }
    finally { submit.disabled = false; }
});

if (document.querySelector('.workspace')) {
    const key = 'easynote.notes.v1';
    let notes = [];
    let storageAvailable = true;
    try {
        const stored = JSON.parse(localStorage.getItem(key) || '[]');
        notes = Array.isArray(stored) ? stored.filter(note => note && typeof note.id === 'string' && typeof note.title === 'string' && typeof note.body === 'string') : [];
    } catch { storageAvailable = false; toast('Browser storage is unavailable. Export your notes before leaving.'); }
    let activeId = notes[0]?.id;
    let filter = 'all';
    const title = document.querySelector('#note-title');
    const body = document.querySelector('#note-body');
    const search = document.querySelector('#note-search');
    const status = document.querySelector('#save-status');
    const current = () => notes.find(note => note.id === activeId);
    function persist() {
        try { localStorage.setItem(key, JSON.stringify(notes)); storageAvailable = true; status.textContent = 'Saved in this browser'; }
        catch { storageAvailable = false; status.textContent = 'Not saved — export a backup'; toast('Storage is full or unavailable. Export your notes to keep them.'); }
    }
    function renderList() {
        const list = document.querySelector('#note-list');
        list.replaceChildren();
        const query = search.value.trim().toLowerCase();
        const visible = notes.filter(note => (filter !== 'favorites' || note.favorite) && `${note.title} ${note.body}`.toLowerCase().includes(query));
        visible.forEach(note => {
            const button = document.createElement('button');
            button.className = `note-list-item${note.id === activeId ? ' active' : ''}`;
            button.setAttribute('aria-pressed', String(note.id === activeId));
            const name = document.createElement('strong'); name.textContent = `${note.favorite ? '? ' : ''}${note.title || 'An untitled thought'}`;
            const preview = document.createElement('small'); preview.textContent = note.body.slice(0, 65) || 'A fresh page. All yours.';
            button.append(name, preview); button.addEventListener('click', () => { activeId = note.id; renderEditor(); renderList(); }); list.append(button);
        });
        if (!visible.length) { const empty = document.createElement('p'); empty.className = 'empty-list'; empty.textContent = 'No notes here yet. Make a little space.'; list.append(empty); }
    }
    function updateCount() { const count = body.value.trim() ? body.value.trim().split(/\s+/u).length : 0; document.querySelector('#word-count').textContent = `${count} ${count === 1 ? 'word' : 'words'}`; }
    function renderEditor() {
        const note = current(); title.value = note?.title || ''; body.value = note?.body || '';
        title.disabled = body.disabled = !note;
        document.querySelector('#favorite-note').disabled = document.querySelector('#delete-note').disabled = !note;
        document.querySelector('#favorite-note').textContent = note?.favorite ? '?' : '?';
        document.querySelector('#favorite-note').setAttribute('aria-pressed', String(Boolean(note?.favorite)));
        status.textContent = storageAvailable ? 'Saved in this browser' : 'Not saved — export a backup'; updateCount();
    }
    function addNote() {
        const note = { id: crypto.randomUUID(), title: '', body: '', favorite: false, updatedAt: new Date().toISOString() };
        notes.unshift(note); activeId = note.id; filter = 'all'; search.value = '';
        document.querySelectorAll('[data-filter]').forEach(button => button.classList.toggle('active', button.dataset.filter === filter));
        persist(); renderList(); renderEditor(); title.focus();
    }
    document.querySelector('#new-note').addEventListener('click', addNote);
    [title, body].forEach(input => input.addEventListener('input', () => {
        const note = current(); if (!note) return;
        note.title = title.value; note.body = body.value; note.updatedAt = new Date().toISOString(); persist(); renderList(); updateCount();
    }));
    search.addEventListener('input', renderList);
    document.querySelectorAll('[data-filter]').forEach(button => button.addEventListener('click', () => {
        filter = button.dataset.filter; document.querySelectorAll('[data-filter]').forEach(item => item.classList.toggle('active', item === button)); renderList();
    }));
    document.querySelector('#favorite-note').addEventListener('click', () => { const note = current(); if (!note) return; note.favorite = !note.favorite; persist(); renderEditor(); renderList(); });
    document.querySelector('#delete-note').addEventListener('click', () => {
        const note = current(); if (!note || !confirm(`Delete “${note.title || 'An untitled thought'}”? This cannot be undone.`)) return;
        notes = notes.filter(item => item.id !== activeId); activeId = notes[0]?.id; persist(); renderList(); renderEditor();
    });
    document.querySelector('#export-notes').addEventListener('click', () => {
        if (!notes.length) { toast('Write a little something first.'); return; }
        const content = notes.map(note => `${note.title || 'An untitled thought'}\n${'-'.repeat(30)}\n${note.body}`).join('\n\n\n');
        const url = URL.createObjectURL(new Blob([content], { type: 'text/plain;charset=utf-8' }));
        const link = document.createElement('a'); link.href = url; link.download = `easynote-${new Date().toISOString().slice(0, 10)}.txt`; link.click(); setTimeout(() => URL.revokeObjectURL(url), 1000); toast('Your thoughts, ready to take with you.');
    });
    if (!notes.length) addNote(); else { renderList(); renderEditor(); }
}

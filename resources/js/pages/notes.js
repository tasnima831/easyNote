import { toast } from '../shared/toast';

if (document.querySelector('.workspace')) {
    const key = 'easynote.notes.v1';
    const freeNoteLimit = 25;
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
    const noteLimitDialog = document.querySelector('#note-limit-dialog');
    noteLimitDialog.querySelector('#close-note-limit').addEventListener('click', () => noteLimitDialog.close());
    noteLimitDialog.addEventListener('click', (event) => { if (event.target === noteLimitDialog) noteLimitDialog.close(); });
    const current = () => notes.find(note => note.id === activeId);
    function persist() {
        try { localStorage.setItem(key, JSON.stringify(notes)); storageAvailable = true; status.textContent = 'Saved in this browser'; }
        catch { storageAvailable = false; status.textContent = 'Not saved — export a backup'; toast('Storage is full or unavailable. Export your notes to keep them.'); }
    }
    function renderList() {
        const newNoteButton = document.querySelector('#new-note');
        newNoteButton.textContent = 'New note';
        newNoteButton.title = notes.length >= freeNoteLimit ? '25-note limit reached' : '';
        const list = document.querySelector('#note-list');
        list.replaceChildren();
        const query = search.value.trim().toLowerCase();
        const visible = notes.filter(note => (filter !== 'favorites' || note.favorite) && `${note.title} ${note.body}`.toLowerCase().includes(query));
        visible.forEach(note => {
            const button = document.createElement('button');
            button.className = `note-list-item${note.id === activeId ? ' active' : ''}`;
            button.setAttribute('aria-pressed', String(note.id === activeId));
            const name = document.createElement('strong'); name.textContent = `${note.favorite ? '\u2605 ' : ''}${note.title || 'An untitled thought'}`;
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
        document.querySelector('#favorite-note').textContent = note?.favorite ? '\u2605' : '\u2606';
        document.querySelector('#favorite-note').setAttribute('aria-pressed', String(Boolean(note?.favorite)));
        status.textContent = storageAvailable ? 'Saved in this browser' : 'Not saved — export a backup'; updateCount();
    }
    function addNote() {
        if (notes.length >= freeNoteLimit) { noteLimitDialog.showModal(); return; }
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
        const link = document.createElement('a'); link.href = url; link.download = `effortlessnote-${new Date().toISOString().slice(0, 10)}.txt`; link.click(); setTimeout(() => URL.revokeObjectURL(url), 1000); toast('Your thoughts, ready to take with you.');
    });
    if (!notes.length) addNote(); else { renderList(); renderEditor(); }
}

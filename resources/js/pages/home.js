import { toast } from '../shared/toast';

const heroChecklist = document.querySelector('.little-list');
if (heroChecklist) {
    const items = [...heroChecklist.querySelectorAll('input[type="checkbox"]')];
    const updateProgress = () => {
        const completed = items.filter(item => item.checked).length;
        heroChecklist.querySelector('.hero-list-progress span').style.width = `${completed / items.length * 100}%`;
        heroChecklist.querySelector('.hero-list-status').textContent = `${completed} of ${items.length} little wins`;
    };
    items.forEach(item => item.addEventListener('change', updateProgress));
    updateProgress();
}
document.querySelector('#hero-thought')?.addEventListener('input', () => {
    document.querySelector('#hero-draft-status').textContent = 'Just a preview. Your workspace saves notes.';
});

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

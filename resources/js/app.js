import './bootstrap';
import './shared/navigation';
import './pages/home';
import './pages/notes';

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!input) return;
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        const label = visible ? 'Hide Password' : 'Show Password';
        button.setAttribute('aria-label', label);
        button.setAttribute('title', label);
        button.setAttribute('aria-pressed', String(visible));
        button.classList.toggle('is-visible', visible);
    });
});

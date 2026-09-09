export const toast = (message) => {
    const element = document.querySelector('.toast');
    element.textContent = message;
    element.classList.add('visible');
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => element.classList.remove('visible'), 4200);
};

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.nav');
    const session = document.querySelector('.session');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = nav.classList.toggle('is-open');
            session?.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    document.querySelectorAll('form.form').forEach((form) => {
        form.addEventListener('submit', () => {
            form.classList.add('is-busy');
        });
    });

    const flash = document.querySelector('.flash');
    if (flash) {
        window.setTimeout(() => {
            flash.style.transition = 'opacity 240ms ease';
            flash.style.opacity = '0';
        }, 4200);
    }
});

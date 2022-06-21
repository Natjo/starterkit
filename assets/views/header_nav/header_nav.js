const clicktouch = ('ontouchstart' in document.activeElement) ? 'touchstart' : 'click';
const header = document.getElementById('header');
const panel = document.getElementById('nav-panel');
const btn_nav = header.querySelector('#btn-nav');

let oldstatus, status = 0;
let triger = 150;

const change = () => header.classList[status === 1 ? 'add' : 'remove']('trig');

const scroll = () => {
    scrollY = window.pageYOffset;
    if (scrollY > triger) status = 1;
    if (scrollY === 0) status = 0;
    oldstatus !== status && change();
    oldstatus = status;
};
const open = () => {
    document.body.classList.add('hasPopin');
    header.classList.add('open');
    btn_nav.setAttribute('aria-expanded', true);
    document.addEventListener('keydown', onKeyDown);
    document.addEventListener('keyup', onKeyUp);
    window.addEventListener(clicktouch, clickOut, true);
}
const close = () => {
    document.body.classList.remove('hasPopin');
    header.classList.remove('open');
    btn_nav.setAttribute('aria-expanded', false);
    document.removeEventListener('keydown', onKeyDown);
    document.removeEventListener('keyup', onKeyUp);
    window.removeEventListener(clicktouch, clickOut);
}
const clickOut = e => {
    if (!panel.contains(e.target) && !btn_nav.contains(e.target)) close();
}
const onKeyUp = () => {
    if (!panel.contains(document.activeElement) && !btn_nav.contains(document.activeElement)) close();
}
const onKeyDown = () => {
    if (event.key == 'Escape') {
        close();
        btn_nav.focus();
    }
}
btn_nav.onclick = () => btn_nav.getAttribute('aria-expanded') === 'false' ? open() : close();

window.addEventListener('scroll', scroll, { passive: true });
window.pageYOffset > triger && header.classList.add('show');
;

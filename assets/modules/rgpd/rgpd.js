/* eslint-disable */
/**
 *  @module Rgpd
 *  @description
 *  Managing cookies via categories.
 *
 *  @element .rgpd-manage-link - open the manage popin
 *
 *  @callback onexec(cat) - fired if at least 1 category is enabled.
 *  @param cat - in checkbox val, is the id of the cookie ex 'stats'
 *  @var strictmode if false, execute onexec event if no consent action in modal alert
 *  @var cookieName name of the rgpd cookie
 *  @var cookieDuration duration of the rgpd cookie
 *
 */

const Rgpd = (onexec) => {
  
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.media = 'screen';
    link.href = `${paramsData.theme_url}/assets/modules/rgpd/rgpd.css`;
    document.head.appendChild(link);

    const display = () => {
        const cookieName = 'rgpd';
        const cookieDuration = 30;
        const strictmode = true;
        const root = document.documentElement || window;
        const clicktouch = ('ontouchstart' in root) ? 'touchstart' : 'click';
        const modal = document.querySelector('#rgpd-modal');
        const btn_accept = modal.querySelector('.btn-accept');
        const btn_refuse = modal.querySelector('.btn-refuse');
        const manage = document.querySelector('#rgpd-manage');
        const manage_box = manage.querySelector('.box');
        const btn_save = manage.querySelector('.btn-save');
        const checkboxes = manage.querySelectorAll('input[type="checkbox"]');
        const btn_close = manage.querySelector('.btn-close');
        const manage_button = document.querySelectorAll('.rgpd-manage-link');
        const manage_links = document.querySelectorAll('a[href="#rgpd-manage"]');
        let scrollTop;
        let cats = {};

        const cookie = {
            create(name, value, days) {
                const date = new Date();
                date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
                document.cookie = `${name}=${value}${days ? `; expires=${date.toGMTString()}` : ''}; path=/`;
            },
            read(name) {
                const nameEQ = `${name}=`;
                for (let item of document.cookie.split(';')) {
                    while (item.charAt(0) === ' ') item = item.substring(1, item.length);
                    if (item.indexOf(nameEQ) === 0) return item.substring(nameEQ.length, item.length);
                }
                return null;
            },
            erase(name) {
                let hostname = document.location.hostname;
                if (hostname.indexOf('www.') === 0) hostname = hostname.substring(4);
                // if subdomains
                // hostname = hostname.substr(hostname.indexOf('.') + 1);
                document.cookie = `${name}=; domain=.${hostname}; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=`;
                document.cookie = `${name}=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/`;
            },
            eraseUnused() {
                for (const checkbox of checkboxes) {
                    !checkbox.checked && checkbox.dataset.cookies.split(',').forEach(val => cookie.erase(val));
                }
            },
        };
    
        let consent = cookie.read(cookieName) ? true : false;
    
        // fire event onexec if cat == true
        const execute = () => {
            for (const key in cats) (cats[key] && typeof onexec === 'function') && onexec(key);
        };
    
        // set cookie obj status
        const status = () => checkboxes.forEach(item => {
            cats[item.value] = item.checked ? true : false;
        });
    
        const set_consent = () => {
            // console.log("set_consent");
            consent = true;
            modal.classList.remove('active');
            cookie.create(cookieName, JSON.stringify(cats), cookieDuration);
    
            const nonce = manage.getAttribute('data-nonce');
            const action = manage.getAttribute('data-action');
            const formData = new window.FormData();
            formData.append('nonce', nonce);
            formData.append('action', action);
    
            // var xhr = new XMLHttpRequest();
            // xhr.open("POST", ParamsData.wp_ajax_url);
            // xhr.send(formData);
        };
    
        const accept = () => {
            status();
            execute();
            set_consent();
        };
    
        const denie = () => {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            status();
            set_consent();
        };
    
        const save = () => {
            status();
            !consent && execute();
            set_consent();
            cookie.eraseUnused();
        };
    
        const disableScroll = () => window.scrollTo(0, scrollTop);
    
        // init or load cookie obj
        if (consent === true) cats = JSON.parse(cookie.read(cookieName));
        else if (!strictmode) {
            status();
            checkboxes.forEach(checkbox => {
                cats[checkbox.value] = true;
            });
            execute();
        }
        else {
            checkboxes.forEach(checkbox => {
                cats[checkbox.value] = true;
            });
        }
    
        // init checkboxes
        for (let key in cats) {
            for (let checkbox of checkboxes) {
                if (checkbox.value === key) checkbox.checked = cats[key];
            }
        }
    
        btn_accept.onclick = () => accept();
    
        btn_refuse.onclick = () => denie();
    
        consent === true ? execute() : modal.classList.add('active');
    
        const clickoutside = e => !manage_box.contains(e.target) && close();
    
        const trap = {
            index: 0,
            els: [],
            isShifted: false,
            init() {
                manage.querySelectorAll('button,a,input').forEach(el => trap.els.push(el));
            },
            keyup(e) {
                e.key === 'Escape' && close();
                if (e.key === 'Shift') {
                    trap.isShifted = false;
                }
            },
            keydown(e) {
                if (e.key === 'Shift') {
                    trap.isShifted = true;
                }
                if (e.key === 'Tab') {
                    if (e.preventDefault) e.preventDefault();
                    else e.returnValue = false;
                    trap.isShifted ? trap.index-- : trap.index++;
                    if (trap.index < 0) trap.index = trap.els.length - 1;
                    if (trap.index >= trap.els.length) trap.index = 0;
                    trap.els[trap.index].focus();
                }
            },
            add() {
                btn_close.focus();
                document.addEventListener('keydown', trap.keydown, false);
                document.addEventListener('keyup', trap.keyup, false);
            },
            remove() {
                document.removeEventListener('keydown', trap.keydown);
                document.removeEventListener('keyup', trap.keyup);
            },
        };
    
        trap.init();
    
        const open = () => {
            status();
            modal.classList.remove('active');
            manage.classList.add('open');
            manage.addEventListener('animationend', () => {
                window.addEventListener(clicktouch, clickoutside, { once: true });
            }, { once: true });
            scrollTop = window.pageYOffset || window.scrollY;
            window.addEventListener('scroll', disableScroll);
            trap.add();
    
            document.querySelector('body').classList.add('no-scroll');
        };
    
        const close = () => {
            if (!consent) modal.classList.add('active');
            manage.classList.add('close');
            window.removeEventListener(clicktouch, clickoutside);
            window.removeEventListener('scroll', disableScroll);
    
            manage.addEventListener('animationend', () => {
                manage.classList.remove('open');
                manage.classList.remove('close');
            }, { once: true });
            trap.remove();
    
            document.querySelector('body').classList.remove('no-scroll');
        };
    
        manage_links.forEach(link => {
            link.onclick = e => {
                e.stopPropagation();
                e.preventDefault();
                open();
            };
        });
        manage_button.forEach(link => {
            link.onclick = e => {
                e.stopPropagation();
                e.preventDefault();
                open();
            };
        });
    
        btn_save.onclick = () => {
            save();
            close();
        };
    
        btn_close.onclick = e => close(e);
    }

    const formData = new window.FormData();
    formData.append("nonce", paramsData.rgpdNonce);
    formData.append("action", "rgpd");
    var xhr = new XMLHttpRequest();
    xhr.open("POST", paramsData.wp_ajax_url);
    xhr.onload = () => {
        const response = JSON.parse(xhr.response);
        document.body.insertAdjacentHTML('afterend',  response.markup);
        display();
    };
    xhr.send(formData);
};

export default Rgpd;

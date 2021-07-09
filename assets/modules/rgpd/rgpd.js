/**
 * @module Rgpd
 * @description 
 * Managing cookies via categories.

 * @element .rgpd-link - open the manage popin
 * 
 * @callback onexec(cat) - fired if at least 1 category is enabled.
 * @param cat - in checkbox val, is the id of the cookie ex 'stats'
 * @var strictmode if false, execute onexec event if no consent action in modal alert
 * @var 'rgpd' name of the rgpd cookie 
 * @var cookieDuration duration of the rgpd cookie
 * 

 */

const Rgpd = onexec => {
/*
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            document.body.insertAdjacentHTML('beforeend', xhr.responseText)
        }
    };
    xhr.open("GET", '/common/rgpd.php');
    xhr.send();*/
   /* const style = document.createElement('link');
    style.rel = 'stylesheet';
    style.href = import.meta.url.replace('.js', '.css');
    document.body.appendChild(style);
    style.onload = () => {*/
        const cookie = {
            create(name, value, days) {
                var date = new Date();
                date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
                document.cookie = `${name}=${value}${days ? `; expires=${date.toGMTString()}` : ''}; path=/`;
            },
            read(name) {
                const nameEQ = `${name}=`;
                for (let item of document.cookie.split(';')) {
                    while (item.charAt(0) === ' ')
                        item = item.substring(1, item.length);
                    if (item.indexOf(nameEQ) === 0)
                        return item.substring(nameEQ.length, item.length);
                }
                return null;
            },
            erase(name) {
                var hostname = document.location.hostname;
                if (hostname.indexOf('www.') === 0)
                    hostname = hostname.substring(4);
                //hostname = hostname.substr(hostname.indexOf('.') + 1); //if subdomains
                document.cookie = `${name}=; domain=.${hostname}; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=`;
                document.cookie = `${name}=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/`;
            },
            eraseUnused() {
                for (let checkbox of checkboxes) {
                    !checkbox.checked && checkbox.dataset.cookies.split(',').forEach((val) => cookie.erase(val));
                }
            },
        };
        let consent = cookie.read('rgpd') ? true : false;
        const links = document.querySelectorAll('.rgpd-link');
        const cookieDuration = 30;
        const strictmode = true;
        const root = document.documentElement || window;
        const clicktouch = 'ontouchstart' in root ? 'touchstart' : 'click';
        const modal = document.getElementById('rgpd-modal');
        const btn_accept = modal.querySelector('.btn-accept');
        const btn_refuse = modal.querySelector('.btn-refuse');
        const btn_manage = modal.querySelectorAll('.btn-manage');
        const manage = document.getElementById('rgpd-manage');
        const manage_box = manage.querySelector('.box');
        const btn_details = manage.querySelectorAll('.btn-detail');
        const btn_save = manage.querySelector('.btn-save');
        const checkboxes = manage.querySelectorAll('input[type="checkbox"]');
        const btn_close = manage.querySelector('.btn-close');
        let scrollTop;
        let cats = {};

        // fire event onexec if cat == true
        const execute = () => {
            for (let key in cats)
                cats[key] && typeof onexec === 'function' && onexec(key);
        };

        // set cookie obj status
        const status = () => checkboxes.forEach(item => (cats[item.value] = item.checked ? true : false));

        const set_consent = () => {
            consent = true;
            modal.classList.remove('active');
            cookie.create('rgpd', JSON.stringify(cats), cookieDuration);
        };

        const accept = () => {
            status();
            execute();
            set_consent();
        };

        const denie = () => {
            checkboxes.forEach((checkbox) => (checkbox.checked = false));
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
        if (consent === true) cats = JSON.parse(cookie.read('rgpd'));
        else if (!strictmode) {
            status();
            checkboxes.forEach((checkbox) => (cats[checkbox.value] = true));
            execute();
        } else checkboxes.forEach((checkbox) => (cats[checkbox.value] = true));

        // init checkboxes
        for (let key in cats) {
            for (let checkbox of checkboxes) {
                if (checkbox.value == key) checkbox.checked = cats[key];
            }
        }

        btn_details.forEach(btn => (btn.onclick = () => btn.classList.toggle('active')));

        btn_accept.onclick = () => accept();

        btn_refuse.onclick = () => denie();

        consent == true ? execute() : modal.classList.add('active');

        const clickoutside = (e) => !manage_box.contains(e.target) && close();

        const open = () => {
            status();
            modal.classList.remove('active');
            manage.classList.add('open');
            manage.addEventListener('animationend',e => window.addEventListener(clicktouch, clickoutside), {once: true});
            scrollTop = window.pageYOffset || window.scrollY;
            window.addEventListener('scroll', disableScroll);
        };

        btn_manage.forEach((btn) => {
            btn.onclick = e => {
                e.preventDefault();
                open();
            };
        });

        const close = e => {
            if (!consent) modal.classList.add('active');
            manage.classList.add('close');
            window.removeEventListener(clicktouch, clickoutside, false);
            window.removeEventListener('scroll', disableScroll);
            btn_details.forEach(btn => (btn.onclick = () => btn.classList.toggle('active')));
            manage.addEventListener('animationend',e => {
                manage.classList.remove('open');
                manage.classList.remove('close');
            },{once: true});
        };

        links.forEach(link =>
            link.onclick = e => {
                e.stopPropagation();
                e.preventDefault();
                open();
            }
        );

        btn_save.onclick = () => {
            save();
            close();
        };

        btn_close.onclick = e => close(e);
   /* };*/
};

export default Rgpd;
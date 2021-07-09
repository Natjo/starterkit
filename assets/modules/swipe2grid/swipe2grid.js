//import Swiper from'../swiper/swiper.js';
import Swiper from'../swiper/swiper-bundle.esm.browser.min.js';

import Breakpoint from'../breakpoint/breakpoint.js';

function Swipe2grid(value){
    const selector = '.swipe2grid';
    document.querySelectorAll(selector).forEach(el => {
        let swiper;
        const breakpoint = new Breakpoint(value);
        const ul = el.querySelector('ul');
        const lis = ul.querySelectorAll('.item');
        breakpoint.above = () => {
            if(el.swiper !== undefined) el.swiper.destroy(true, true);
            el.classList.remove('swiper-container');
            ul.classList.remove('swiper-wrapper');
            lis.forEach(li => li.classList.remove('swiper-slide'))
        }
        breakpoint.under = () => {
            console.log("under");
            el.classList.add('swiper-container');
            ul.classList.add('swiper-wrapper');
            lis.forEach(li => li.classList.add('swiper-slide'))
            swiper = new Swiper (selector, {
                spaceBetween: window.innerWidth < 480 ? 20: 50,
                slidesPerView: 'auto',
                centeredSlides: true,
            });
        }
    });
}

export default Swipe2grid;

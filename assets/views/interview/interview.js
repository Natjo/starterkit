import animation from '../../modules/animation/animation.js';
import ytPlayer from '../../modules/ytPlayer/ytPlayer.js';

const el = document.querySelector('[data-view=interview]');
const cta = el.querySelector(".videoPlayer-cta");
const btn_close = el.querySelector(".btn-close");
let popinVideo;

const yt = new ytPlayer(cta, {
    onPlayerStateChange() {
        popinVideo.setAttribute("aria-hidden", true);
    },
    onPlayerReady() {
        popinVideo = document.getElementById(cta.getAttribute("aria-controls"));
        cta.onclick = () => {
            yt.play();
            popinVideo.setAttribute("aria-hidden", false);
        };
        btn_close.onclick = () => {
            popinVideo.setAttribute("aria-hidden", true);
        };
    }
});

animation('fromBottom', el, 'q,.author,.function,h2,.videoPlayer-cta');
animation('fadein', el, 'picture');
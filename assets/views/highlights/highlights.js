import GridMasonry from "../../modules/gridMasonry/gridMasonry.js";
import Breakpoint from "../../modules/breakpoint/breakpoint.js";
import Slider from "../../modules/slider/slider.js";
import animation from "../../modules/animation/animation.js";

const el = document.querySelector('[data-view=highlights]');
const masonry = new GridMasonry('.highlights', '[data-view=card-highlight]');
const breakpoint = new Breakpoint(992);
const slider = new Slider(el.querySelector(".slider"));

el.querySelector(".btn-prev").onclick = () => slider.prev();
el.querySelector(".btn-next").onclick = () => slider.next();

breakpoint.under = () => {
   masonry.destroy();
   slider.create();
   animation('fromBottom', el, '.card-highlight');
};
breakpoint.above = () => {
   slider.destroy();
   masonry.column(3);
   animation('fromBottom', el, '.card-highlight');
};

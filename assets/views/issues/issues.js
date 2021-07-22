import Slider from "../../modules/slider/slider.js";
import animation from "../../modules/animation/animation.js";

const el = document.querySelector('[data-view=issues]');
const slider = new Slider(el.querySelector(".slider"));
slider.create();
el.querySelector(".btn-prev").onclick = () => slider.prev();
el.querySelector(".btn-next").onclick = () => slider.next();

animation("fromBottom", el, ".card-article");

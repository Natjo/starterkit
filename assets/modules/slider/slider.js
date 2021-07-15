/**
 * @module Slider
 */

 function Slider(slider){
	const isTouchable = 'ontouchstart' in document.documentElement;
	const items = slider.querySelectorAll('li');
	const total = items.length;	
	var itemW, gap, nb;
	var dir;
	var startX = 0;
	var moveX = 0;
	var posNum = 0;
	var posX = 0, oldposX;
	var isMove = false;
	
	slider.style.setProperty('--total', total);

	const goto = (value, transition = true) => {
		posX = -value * (itemW + gap);
		if(transition) slider.style.transition = 'transform .4s ease';
		slider.style.transform = `translate3d(${posX}px,0,0)`;
	}
	const clickout = e => {
		!slider.contains(e.target) && _mouseUp();
	}
	const _mouseDown = value => {
		startX = value - posX;
		slider.style.transition = 'none';
		window.addEventListener('mouseup', clickout);
	}
	const resize = () => {
		gap = parseInt(getComputedStyle(slider).gridColumnGap);
		nb = parseInt(getComputedStyle(slider).getPropertyValue('--nb')) || 1;
		itemW = items[0].clientWidth;
		goto(posNum, false);
	}
	const _mouseMove = value => {
		posX = value - startX - moveX;
		if(posX != oldposX && !isMove){
			slider.classList.add('onswipe');
			isMove = true;
			document.body.classList.add('disableScroll');
		} 
		//if(posX>=0) posX = 0;
		//if(posX<-ul.scrollWidth + 800) posX = -ul.scrollWidth + 800;
		slider.style.transform = `translate3d(${posX}px,0,0)`;
		if(dir > value){
			posNum = -(posX - (itemW - 20)) / (itemW + gap);
		}
		else if(dir < value){
			posNum = -(posX - 20 - gap) / (itemW + gap);
		}
		dir = value;
		oldposX = posX;
	}
	const _mouseUp = () => {
		slider.onmousemove = null;
		slider.onmouseup = null;
		posNum = Math.floor(posNum);
		if(posNum < 0) posNum = 0;
		if(posNum > total - nb) posNum = total - nb;
		goto(posNum);
		window.removeEventListener('mouseup', clickout);
		slider.classList.remove('onswipe');
		isMove = false;
		document.body.classList.remove('disableScroll');
	}
	const leave = (e) => {
		if(e.clientY <= 0 || e.clientX <= 0 || (e.clientX >= window.innerWidth || e.clientY >= window.innerHeight)){
			_mouseUp();
		}
	}

	this.prev = () => {
		posNum > 0 && posNum--;
		goto(posNum);
	}
	this.next = () => {
		posNum < total - nb && posNum++;
		goto(posNum);
	}
	this.create = () => {
		if(total <= nb) return;
		if(isTouchable){
			slider.ontouchstart = e => {
				_mouseDown(e.touches[0].clientX);
				slider.ontouchmove = e => _mouseMove(e.touches[0].clientX);
				slider.ontouchend = e => _mouseUp(e.changedTouches[0].clientX);
			}
		}
		else{
			slider.onmousedown = e => {
				_mouseDown(e.clientX);
				slider.onmousemove = e => _mouseMove(e.clientX);
				slider.onmouseup = e => _mouseUp(e.clientX);
				return false;
			}
			window.addEventListener('resize', resize, {passive: true});
			document.querySelector('html').addEventListener("mouseleave", leave);
		}
	}
	this.destroy = () => {
		slider.onmousemove = null;
		slider.onmouseup = null;
		slider.ontouchstart = null;
		slider.onmousedown = null;
		slider.style.transition = 'none';
		slider.style.transform = 'none';
		window.removeEventListener('resize', resize);
		document.querySelector('html').removeEventListener("mouseleave", leave);
	}
		
	resize();

	
}

export default Slider;
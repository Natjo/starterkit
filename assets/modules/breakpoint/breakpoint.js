/* eslint-disable */

/**
 * Module adding event when viewport width pass through breakpoint
 * 
 * @module Breakpoint
 * @param {number} value breakpoint in pixel
 * @method Breakpoint.under trig if under the breakoint
 * @method Breakpoint.above trig if above the breakoint
 * 
 */

 function Breakpoint(value){
	this.above = () => {};
	this.under = () => {};
	const breakpointChecker = e => e.matches ? this.above() : this.under();
	const list = window.matchMedia(`(min-width:${value}px)`);
	list.addEventListener('change', breakpointChecker)
   setTimeout(() => list.matches ?  this.above() : this.under(), 1);
}

export default Breakpoint;
function Breakpoint(value){
    this.above = function(){};
    this.under = function(){};
    const breakpointChecker = e => e.matches ? this.above() : this.under();
    const gg = window.matchMedia(`(min-width:${value}px)`);
    gg.addListener(breakpointChecker);
    setTimeout(() => gg.matches ?  this.above() : this.under(), 1);
}

export default Breakpoint;

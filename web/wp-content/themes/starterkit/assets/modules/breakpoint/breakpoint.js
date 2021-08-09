function Breakpoint(value) {
  this.above = () => {};

  this.under = () => {};

  const breakpointChecker = e => e.matches ? this.above() : this.under();

  const list = window.matchMedia(`(min-width:${value}px)`);
  list.addEventListener('change', breakpointChecker);
  setTimeout(() => list.matches ? this.above() : this.under(), 1);
}

export default Breakpoint;
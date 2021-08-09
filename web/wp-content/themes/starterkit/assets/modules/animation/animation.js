function animation(name, container, selectors, speed = .6, delay = 100) {
  let inc = 0;
  const observer = new IntersectionObserver(items => items.forEach(e => {
    if (e.isIntersecting) {
      e.target.style.animation = `${name} ease ${speed}s both ${inc * delay}ms`;
      observer.unobserve(e.target);
      inc++;
    }
  }));
  container.querySelectorAll(selectors).forEach(item => observer.observe(item));
}

export default animation;
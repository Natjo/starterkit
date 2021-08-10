import header_nav from './modules/header_nav/header_nav.js';
header_nav();
window.addEventListener('load', () => {
  import('./modules/rgpd/rgpd.js').then(module => {
    module.default(cat => {
      console.log(cat);
    });
  });
  const link = document.createElement('link');
  link.rel = 'stylesheet';
  link.media = 'print';
  link.href = `${paramsData.theme_url}/assets/print.css`;
  document.head.appendChild(link);
});
const observer = new IntersectionObserver(items => items.forEach(e => {
  if (e.isIntersecting) {
    const view = e.target.dataset.view;
    const script = document.createElement('script');
    script.type = 'module';
    script.src = `${paramsData.theme_url}assets/views/${view}/${view}.js`;
    script.setAttribute('defer', '');
    document.body.appendChild(script);
    observer.unobserve(e.target);
  }
}));

for (const view of paramsData.views) observer.observe(document.querySelector(`[data-view=${view}]`));
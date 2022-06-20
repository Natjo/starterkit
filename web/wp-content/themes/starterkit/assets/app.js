import "./views/header_nav/header_nav.js";
window.addEventListener('load', () => {
  const style = document.createElement('link');
  style.rel = 'stylesheet';
  style.media = 'screen';
  style.href = `${paramsData.theme_url}/assets/modules/rgpd/rgpd.css`;
  document.head.appendChild(style);
  import("./modules/rgpd/rgpd.js").then(module => {
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
window.paramsData = JSON.parse(appjs.dataset.params_data);

for (const view of JSON.parse(appjs.dataset.views)) {
  observer.observe(document.querySelector(`[data-view=${view}]`));
}
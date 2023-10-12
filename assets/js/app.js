// paramsData
window.paramsData = {};
for(let key in appjs.dataset){
    window.paramsData[key] = appjs.dataset[key]
}

// Views observe
const observer = new IntersectionObserver(items => items.forEach(e => {
    if (e.isIntersecting) {
        const view = e.target.dataset.view;
        import(`${paramsData.theme_url}assets/views/${view}/${view}.js?v=${paramsData.version}`).then((view) => view.default(e.target));
        observer.unobserve(e.target);
    }
}));
for (const view of JSON.parse(appjs.dataset.viewsJs)) {
    observer.observe(document.querySelector(`[data-view=${view}]`));
}
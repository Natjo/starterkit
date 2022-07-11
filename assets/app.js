import "./views/header_nav/header_nav.js";

// Third part
window.addEventListener('load', () => {   

    // rgpd
    const style = document.createElement('link');
    style.rel = 'stylesheet';
    style.media = 'screen';
    style.href = `${paramsData.theme_url}/assets/modules/rgpd/rgpd.css`;
    document.head.appendChild(style);
    import("./modules/rgpd/rgpd.js")
        .then((module) => {
        module.default(cat => {
            console.log(cat);
            /*if (cat === 'statistiques') {
            }*/
        });
    });
    
    // print css
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.media = 'print';
    link.href = `${paramsData.theme_url}/assets/print.css`;
    document.head.appendChild(link);
});

// views observe
const observer = new IntersectionObserver(items => items.forEach(e => {
    if (e.isIntersecting) {
        const view = e.target.dataset.view;
        import(`${paramsData.theme_url}assets/views/${view}/${view}.js`).then((view) => view.default(e.target));
        observer.unobserve(e.target);
    }
}));
for (const view of JSON.parse(appjs.dataset.views)) {
    observer.observe(document.querySelector(`[data-view=${view}]`));
}

// paramsData
window.paramsData = JSON.parse(appjs.dataset.params_data);

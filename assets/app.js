import header_nav from './modules/header_nav/header_nav.js';

header_nav();

// Third part
window.addEventListener('load', () => {
    import('./modules/rgpd/rgpd.js')
        .then((module) => {
        module.default(cat => {
            console.log(cat);
            /*if (cat === 'statistiques') {
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
                ga('create', ParamsData.gtag_key, 'auto');
                ga('send', 'pageview'); 
            }*/
        });
    });

    // add print
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.media = 'print';
    link.href = `${paramsData.theme_url}/assets/print.css`;
    document.head.appendChild(link);
});

// views obsrve
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
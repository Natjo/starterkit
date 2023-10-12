window.addEventListener('load', () => {

    // rgpd
    const style = document.createElement('link');
    style.rel = 'stylesheet';
    style.media = 'screen';
    style.href = `${paramsData.theme_url}/assets/modules/rgpd/rgpd.css?v=` + paramsData.version;
    document.head.appendChild(style);
    import("./modules/rgpd/rgpd.js?v=" + paramsData.version)
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
    link.href = `${paramsData.theme_url}/assets/print.css?v=` + paramsData.version;
    document.head.appendChild(link);
});



import header_nav from "./modules/header_nav/header_nav.js";
header_nav();

/*import Rgpd from "./modules/rgpd/rgpd.js";
window.addEventListener('load', () => {
    Rgpd((status) => {
        console.log(status);
    });
});
*/

console.log('po',ParamsData.views);

// lazyload
if ('loading' in HTMLImageElement.prototype) {
    document.querySelectorAll('img[loading]').forEach((img) => {
        if (img.complete) img.loading = 'eager';
        img.onload = () => img.loading = 'eager';
    });
} else {
    document.querySelectorAll('img[loading]').forEach((img) => img.removeAttribute('loading'));
}

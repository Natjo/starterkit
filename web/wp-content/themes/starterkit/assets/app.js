import"./modules/header_nav/header_nav.js";window.addEventListener("load",()=>{import("./modules/rgpd/rgpd.js").then(a=>{a.default(a=>{console.log(a)})});const a=document.createElement("link");a.rel="stylesheet",a.media="print",a.href=`${paramsData.theme_url}/assets/print.css`,document.head.appendChild(a)});const observer=new IntersectionObserver(a=>a.forEach(a=>{if(a.isIntersecting){const b=a.target.dataset.view,c=document.createElement("script");c.type="module",c.src=`${paramsData.theme_url}assets/views/${b}/${b}.js`,c.setAttribute("defer",""),document.body.appendChild(c),observer.unobserve(a.target)}}));window.paramsData=JSON.parse(appjs.dataset.params_data);for(const a of JSON.parse(appjs.dataset.views))observer.observe(document.querySelector(`[data-view=${a}]`));
const Rgpd=a=>{const b=document.createElement("link");b.rel="stylesheet",b.media="screen",b.href=`${paramsData.theme_url}/assets/modules/rgpd/rgpd.css`,document.head.appendChild(b);const c=()=>{const b=document.documentElement||window,c="ontouchstart"in b?"touchstart":"click",d=document.querySelector("#rgpd-modal"),e=d.querySelector(".btn-accept"),f=d.querySelector(".btn-refuse"),g=document.querySelector("#rgpd-manage"),h=g.querySelector(".box"),i=g.querySelector(".btn-save"),j=g.querySelectorAll("input[type=\"checkbox\"]"),k=g.querySelector(".btn-close"),l=document.querySelectorAll(".rgpd-manage-link"),m=document.querySelectorAll("a[href=\"#rgpd-manage\"]");let n,o={};const p={create(a,b,c){const d=new Date;d.setTime(d.getTime()+1e3*(60*(60*(24*c)))),document.cookie=`${a}=${b}${c?`; expires=${d.toGMTString()}`:""}; path=/`},read(a){const b=`${a}=`;for(let c of document.cookie.split(";")){for(;" "===c.charAt(0);)c=c.substring(1,c.length);if(0===c.indexOf(b))return c.substring(b.length,c.length)}return null},erase(a){let b=document.location.hostname;0===b.indexOf("www.")&&(b=b.substring(4)),document.cookie=`${a}=; domain=.${b}; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=`,document.cookie=`${a}=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/`},eraseUnused(){for(const a of j)a.checked||a.dataset.cookies.split(",").forEach(a=>p.erase(a))}};let q=!!p.read("rgpd");const r=()=>{for(const b in o)o[b]&&"function"==typeof a&&a(b)},s=()=>j.forEach(a=>{o[a.value]=!!a.checked}),t=()=>{q=!0,d.classList.remove("active"),p.create("rgpd",JSON.stringify(o),30);const a=g.getAttribute("data-nonce"),b=g.getAttribute("data-action"),c=new window.FormData;c.append("nonce",a),c.append("action",b)},u=()=>{s(),r(),t()},v=()=>{j.forEach(a=>{a.checked=!1}),s(),t()},w=()=>{s(),q||r(),t(),p.eraseUnused()},x=()=>window.scrollTo(0,n);for(let a in!0==q?o=JSON.parse(p.read("rgpd")):j.forEach(a=>{o[a.value]=!0}),o)for(let b of j)b.value===a&&(b.checked=o[a]);e.onclick=()=>u(),f.onclick=()=>v(),!0==q?r():d.classList.add("active");const y=a=>!h.contains(a.target)&&B(),z={index:0,els:[],isShifted:!1,init(){g.querySelectorAll("button,a,input").forEach(a=>z.els.push(a))},keyup(a){"Escape"===a.key&&B(),"Shift"===a.key&&(z.isShifted=!1)},keydown(a){"Shift"===a.key&&(z.isShifted=!0),"Tab"===a.key&&(a.preventDefault?a.preventDefault():a.returnValue=!1,z.isShifted?z.index--:z.index++,0>z.index&&(z.index=z.els.length-1),z.index>=z.els.length&&(z.index=0),z.els[z.index].focus())},add(){k.focus(),document.addEventListener("keydown",z.keydown,!1),document.addEventListener("keyup",z.keyup,!1)},remove(){document.removeEventListener("keydown",z.keydown),document.removeEventListener("keyup",z.keyup)}};z.init();const A=()=>{s(),d.classList.remove("active"),g.classList.add("open"),g.addEventListener("animationend",()=>{window.addEventListener(c,y,{once:!0})},{once:!0}),n=window.pageYOffset||window.scrollY,window.addEventListener("scroll",x),z.add(),document.querySelector("body").classList.add("no-scroll")},B=()=>{q||d.classList.add("active"),g.classList.add("close"),window.removeEventListener(c,y),window.removeEventListener("scroll",x),g.addEventListener("animationend",()=>{g.classList.remove("open"),g.classList.remove("close")},{once:!0}),z.remove(),document.querySelector("body").classList.remove("no-scroll")};m.forEach(a=>{a.onclick=a=>{a.stopPropagation(),a.preventDefault(),A()}}),l.forEach(a=>{a.onclick=a=>{a.stopPropagation(),a.preventDefault(),A()}}),i.onclick=()=>{w(),B()},k.onclick=a=>B(a)},d=new window.FormData;d.append("nonce",paramsData.rgpdNonce),d.append("action","rgpd");var e=new XMLHttpRequest;e.open("POST",paramsData.ajax_url),e.onload=()=>{const a=JSON.parse(e.response);document.body.insertAdjacentHTML("afterend",a.markup),c()},e.send(d)};export default Rgpd;
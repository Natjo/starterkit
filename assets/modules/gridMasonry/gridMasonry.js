function GridMasonry(selector, item_selector) {
    const el = document.querySelector(selector);
    let masonry;
    let items;
    let cols = [];

    const create = (nb) => {
        items = document.createElement("div");
        items.className = "masonry-items";
        el.parentNode.insertBefore(items, el);

        masonry = document.createElement("ul");
        masonry.className = "masonry";
        items.parentNode.insertBefore(masonry, items);

        const clone = el.cloneNode(true);
        items.appendChild(clone);
        const cards = items.querySelectorAll(item_selector);

        masonry.style.gridTemplateColumns = `repeat(${nb}, 1fr)`;
        cols.forEach((item) => item.remove());
        cols = [];
        let arr = [];

        for (let u = 0; u < nb; u++) {
            const li = document.createElement("li");
            masonry.appendChild(li);
            cols.push(li);

            if(u == 1) arr.push(-150);
            else arr.push(0);
        }

        for (let card of cards) {
            const index = arr.indexOf(Math.min(...arr));
            arr[index] = arr[index] + card.clientHeight;
            cols[index].appendChild(card);
        }
    };
    this.destroy = () => {
        if(masonry) masonry.remove();
        if(items) items.remove();
    };
    this.column = (val) => create(val);
}

export default GridMasonry;
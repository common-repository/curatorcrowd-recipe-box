!function(w,d){
    if (!w._snup || !w._snup.siteid || !w._snup.target) return;
    let t; if (t = d.querySelector(w._snup.target), !t) return;
    const burl = 'https://japfg-trending-content.appspot.com';
    const s = d.createElement('div'); s.id = "_snup-target";
    t.parentNode.insertBefore(s, t.nextSibling);
    function add(u) {const s = d.createElement('script');s.async = true;s.defer = true;s.src = u;d.head.appendChild(s);}
    function recipe() {
        let g=d.querySelector('script[type="application/ld+json"]');
        return (g && g.textContent && g.textContent.search(/"Recipe"/) > 0)
        || d.querySelector('[itemtype="https://schema.org/Recipe"]')
        || d.querySelector('[itemtype="http://schema.org/Recipe"]')
        || d.querySelector('[itemprop="recipeIngredient"]');
    }
    if (recipe()) add(`${burl}/init.php`);
    add(`${burl}/widgey.php?t=pop&d=m&o=0&s=${w._snup.siteid}&p=${encodeURIComponent(d.location.pathname)}`);
}(window,document);
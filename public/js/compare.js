(function () {
    const MAX = 4;
    const KEY = 'icetech_compare';

    function getList() {
        try { return JSON.parse(localStorage.getItem(KEY) || '[]'); }
        catch (e) { return []; }
    }

    function save(list) {
        localStorage.setItem(KEY, JSON.stringify(list));
    }

    window.compareToggle = function (btn) {
        const id   = parseInt(btn.dataset.id);
        const list = getList();
        const idx  = list.findIndex(p => p.id === id);

        if (idx > -1) {
            list.splice(idx, 1);
        } else {
            if (list.length >= MAX) {
                showToast('მაქსიმუმ ' + MAX + ' პროდუქტის შედარებაა შესაძლებელი', 'warn');
                return;
            }
            list.push({
                id:    id,
                slug:  btn.dataset.slug,
                name:  btn.dataset.name,
                image: btn.dataset.image,
                price: btn.dataset.price,
            });
        }

        save(list);
        updateTray();
        updateAllButtons();
    };

    window.compareRemove = function (id) {
        save(getList().filter(p => p.id !== id));
        updateTray();
        updateAllButtons();
    };

    // Remove product on the /compare page itself and navigate to the new URL
    window.compareRemoveOnPage = function (id) {
        const list = getList().filter(p => p.id !== id);
        save(list);
        if (list.length === 0) {
            window.location.href = '/compare';
        } else {
            window.location.href = '/compare?ids=' + list.map(p => p.id).join(',');
        }
    };

    window.compareClear = function () {
        localStorage.removeItem(KEY);
        updateTray();
        updateAllButtons();
    };

    function updateAllButtons() {
        const ids = getList().map(p => p.id);
        document.querySelectorAll('.btn-compare, .btn-compare-full').forEach(function (btn) {
            const active = ids.includes(parseInt(btn.dataset.id));
            btn.classList.toggle('active', active);
            const textEl = btn.querySelector('.compare-btn-text');
            if (textEl) textEl.textContent = active ? 'შედარებიდან ამოღება' : 'შედარებაში დამატება';
            btn.title = active ? 'შედარებიდან ამოღება' : 'შედარებაში დამატება';
        });
    }

    function updateTray() {
        const list  = getList();
        const tray  = document.getElementById('compareTray');
        const items = document.getElementById('compareTrayItems');
        const count = document.getElementById('compareCount');
        const link  = document.getElementById('compareTrayLink');
        if (!tray) return;

        if (list.length === 0) {
            tray.classList.remove('show');
            return;
        }

        tray.classList.add('show');
        count.textContent = list.length;
        link.href = '/compare?ids=' + list.map(p => p.id).join(',');

        items.innerHTML = list.map(function (p) {
            return '<div class="ctr-item">' +
                '<img src="' + p.image + '" alt="">' +
                '<span>' + p.name + '</span>' +
                '<button onclick="compareRemove(' + p.id + ')" class="ctr-remove"><i class="bi bi-x"></i></button>' +
            '</div>';
        }).join('');
    }

    function showToast(msg, type) {
        const t = document.createElement('div');
        t.className = 'compare-toast' + (type === 'warn' ? ' warn' : '');
        t.textContent = msg;
        document.body.appendChild(t);
        requestAnimationFrame(function () { t.classList.add('show'); });
        setTimeout(function () {
            t.classList.remove('show');
            setTimeout(function () { t.remove(); }, 300);
        }, 2500);
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateTray();
        updateAllButtons();
    });
})();

(function () {
    const body = document.body;
    const sidebar = document.querySelector('.neo-sidebar');
    const toggles = document.querySelectorAll('[data-sidebar-toggle]');
    const overlay = document.querySelector('.neo-overlay');
    const media = window.matchMedia('(max-width: 1024px)');

    if (!sidebar) {
        return;
    }

    const saved = window.localStorage ? window.localStorage.getItem('neoSidebarCollapsed') : null;
    const initialCollapsed = media.matches ? true : saved === 'true';

    const applyState = (collapsed, persist = true) => {
        if (media.matches) {
            body.classList.toggle('sidebar-open', !collapsed);
        } else {
            body.classList.remove('sidebar-open');
        }
        body.classList.toggle('sidebar-collapsed', collapsed);
        if (persist && window.localStorage) {
            window.localStorage.setItem('neoSidebarCollapsed', collapsed ? 'true' : 'false');
        }
    };

    applyState(initialCollapsed, false);

    toggles.forEach(btn => btn.addEventListener('click', () => {
        const collapsedNow = body.classList.contains('sidebar-collapsed');
        applyState(!collapsedNow);
    }));

    if (overlay) {
        overlay.addEventListener('click', () => applyState(true));
    }

    media.addEventListener('change', () => {
        const collapsed = media.matches ? true : body.classList.contains('sidebar-collapsed');
        applyState(collapsed, false);
    });
})();

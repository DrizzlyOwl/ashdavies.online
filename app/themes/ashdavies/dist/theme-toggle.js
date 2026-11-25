(function () {
    var root = document.documentElement;
    var button = document.getElementById('theme-toggle');
    if (!button) {
        return;
    }

    var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

    function getEffectiveTheme() {
        var explicit = root.getAttribute('data-theme');
        if (explicit === 'dark' || explicit === 'light') {
            return explicit;
        }
        return mediaQuery.matches ? 'dark' : 'light';
    }

    function syncButton() {
        var isDark = getEffectiveTheme() === 'dark';
        button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
    }

    syncButton();

    button.addEventListener('click', function () {
        var next = getEffectiveTheme() === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        try {
            localStorage.setItem('ash-theme', next);
        } catch (e) {}
        syncButton();
    });

    if (typeof mediaQuery.addEventListener === 'function') {
        mediaQuery.addEventListener('change', syncButton);
    }
})();

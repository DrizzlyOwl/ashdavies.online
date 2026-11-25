(function () {
    if (
        !('IntersectionObserver' in window) ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches
    ) {
        return;
    }

    var targets = document.querySelectorAll('.skill, .avatar-card, .latest-posts .card__item');
    if (!targets.length) {
        return;
    }

    var viewportHeight = window.innerHeight || document.documentElement.clientHeight;
    var pending = [];

    targets.forEach(function (el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < viewportHeight * 0.94) {
            return;
        }

        var parent = el.parentElement;
        if (parent) {
            var siblings = Array.prototype.slice.call(parent.children);
            var index = siblings.indexOf(el);
            if (index > 0) {
                el.style.setProperty('--reveal-delay', Math.min(index * 60, 180) + 'ms');
            }
        }

        el.classList.add('reveal-pending');
        pending.push(el);
    });

    if (!pending.length) {
        return;
    }

    var observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -32px 0px',
        }
    );

    pending.forEach(function (el) {
        observer.observe(el);
    });
})();

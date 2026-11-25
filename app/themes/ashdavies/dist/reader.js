(function () {
    // 1. Copy button for .wp-block-code blocks
    var codeBlocks = document.querySelectorAll('.wp-block-code');
    if (codeBlocks.length) {
        var liveRegion = document.createElement('span');
        liveRegion.className = 'screen-reader-text';
        liveRegion.setAttribute('aria-live', 'polite');
        document.body.appendChild(liveRegion);

        var copyText = function (text) {
            if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
                return navigator.clipboard.writeText(text);
            }
            return new Promise(function (resolve, reject) {
                try {
                    var ta = document.createElement('textarea');
                    ta.value = text;
                    ta.setAttribute('readonly', '');
                    ta.style.position = 'fixed';
                    ta.style.opacity = '0';
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    resolve();
                } catch (err) {
                    reject(err);
                }
            });
        };

        codeBlocks.forEach(function (block) {
            var codeEl = block.querySelector('code');
            if (!codeEl) {
                return;
            }

            block.classList.add('has-copy-btn');
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'code-copy-btn';
            btn.textContent = 'Copy';
            btn.setAttribute('aria-label', 'Copy code to clipboard');

            var resetTimer = null;
            btn.addEventListener('click', function () {
                copyText(codeEl.textContent || '').then(function () {
                    btn.textContent = 'Copied!';
                    btn.setAttribute('data-copied', 'true');
                    liveRegion.textContent = 'Code copied to clipboard';
                    if (resetTimer) {
                        window.clearTimeout(resetTimer);
                    }
                    resetTimer = window.setTimeout(function () {
                        btn.textContent = 'Copy';
                        btn.removeAttribute('data-copied');
                        liveRegion.textContent = '';
                    }, 1800);
                }).catch(function () {});
            });

            block.appendChild(btn);
        });
    }

    // 2. Active section highlight in sticky .page-anchors TOC
    var tocLinks = Array.prototype.slice.call(
        document.querySelectorAll('.page-anchors__link[href^="#"]')
    );
    if (!tocLinks.length || !('IntersectionObserver' in window)) {
        return;
    }

    var headingToLink = new Map();
    var headings = [];
    tocLinks.forEach(function (link) {
        var id = link.getAttribute('href').slice(1);
        if (!id) {
            return;
        }
        var target = document.getElementById(id);
        if (target) {
            headingToLink.set(target, link);
            headings.push(target);
        }
    });

    if (!headings.length) {
        return;
    }

    var setActive = function (activeLink) {
        tocLinks.forEach(function (link) {
            if (link === activeLink) {
                link.classList.add('is-active');
                link.setAttribute('aria-current', 'location');
            } else {
                link.classList.remove('is-active');
                link.removeAttribute('aria-current');
            }
        });
    };

    var observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var link = headingToLink.get(entry.target);
                    if (link) {
                        setActive(link);
                    }
                }
            });
        },
        {
            rootMargin: '-12% 0px -70% 0px',
            threshold: 0,
        }
    );

    headings.forEach(function (h) {
        observer.observe(h);
    });
})();

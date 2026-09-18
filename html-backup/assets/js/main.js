document.addEventListener("DOMContentLoaded", function () {
    var toggle = document.querySelector(".nav-toggle");
    if (toggle) {
        toggle.addEventListener("click", function () {
            document.body.classList.toggle("nav-open");
            var expanded = document.body.classList.contains("nav-open");
            toggle.setAttribute("aria-expanded", expanded ? "true" : "false");
        });
    }

    // On touch/small screens, tapping a dropdown parent link toggles its submenu
    document.querySelectorAll(".has-dropdown > a").forEach(function (link) {
        link.addEventListener("click", function (e) {
            if (window.innerWidth <= 900) {
                var parent = link.parentElement;
                var isOpen = parent.classList.contains("open");
                document.querySelectorAll(".has-dropdown.open").forEach(function (el) {
                    el.classList.remove("open");
                });
                if (!isOpen) {
                    e.preventDefault();
                    parent.classList.add("open");
                }
            }
        });
    });

    initHeader();
    initHero();
    initHeroDiagram();
    initCookieBanner();
});

function initHeroDiagram() {
    var diagram = document.querySelector(".hero-diagram");
    if (!diagram) return;
    var nodes = Array.prototype.slice.call(diagram.querySelectorAll(".hero-diagram__node"));
    var lines = {};
    diagram.querySelectorAll(".hero-diagram__line").forEach(function (line) {
        lines[line.getAttribute("data-line")] = line;
    });

    var userInteracting = false;
    var pauseTimeout = null;

    function clearAll() {
        nodes.forEach(function (n) { n.classList.remove("is-highlighted"); });
        Object.keys(lines).forEach(function (k) { lines[k].classList.remove("is-active"); });
    }
    function activateById(id) {
        clearAll();
        var node = nodes.find(function (n) { return n.getAttribute("data-node") === String(id); });
        if (node) node.classList.add("is-highlighted");
        if (lines[id]) lines[id].classList.add("is-active");
    }

    nodes.forEach(function (node) {
        var id = node.getAttribute("data-node");
        if (!id) return;
        var onEnter = function () {
            userInteracting = true;
            clearTimeout(pauseTimeout);
            activateById(id);
        };
        var onLeave = function () {
            pauseTimeout = setTimeout(function () { userInteracting = false; }, 1200);
        };
        node.addEventListener("mouseenter", onEnter);
        node.addEventListener("mouseleave", onLeave);
        node.addEventListener("focusin", onEnter);
        node.addEventListener("focusout", onLeave);
    });

    // Auto-cycle activation for continuous motion
    var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (!reduced) {
        var cursor = 1;
        setInterval(function () {
            if (userInteracting) return;
            activateById(cursor);
            cursor = cursor >= nodes.length ? 1 : cursor + 1;
        }, 1800);
    }

    // Parallax tilt toward pointer
    var fine = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
    if (fine && !reduced) {
        var rect = null;
        diagram.addEventListener("pointerenter", function () { rect = diagram.getBoundingClientRect(); });
        diagram.addEventListener("pointermove", function (e) {
            if (!rect) rect = diagram.getBoundingClientRect();
            var x = (e.clientX - rect.left) / rect.width - 0.5;
            var y = (e.clientY - rect.top) / rect.height - 0.5;
            diagram.style.transform = "perspective(900px) rotateY(" + (x * 6) + "deg) rotateX(" + (-y * 6) + "deg)";
        });
        diagram.addEventListener("pointerleave", function () {
            diagram.style.transform = "";
            rect = null;
        });
    }
}

function initCookieBanner() {
    var STORAGE_KEY = "sov_cookie_consent";
    var COOKIE_ICON_SVG =
        '<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false">' +
            '<path fill="currentColor" d="M21.6 11.1a4.2 4.2 0 0 1-4.7-4.7 4.2 4.2 0 0 1-4.6-4.3A10 10 0 1 0 22 12c0-.3 0-.6-.4-.9zM8 15.5a1.3 1.3 0 1 1 1.3-1.3A1.3 1.3 0 0 1 8 15.5zm-.5-5a1 1 0 1 1 1-1 1 1 0 0 1-1 1zm4.5 6a1 1 0 1 1 1-1 1 1 0 0 1-1 1zm3-3a1.3 1.3 0 1 1 1.3-1.3 1.3 1.3 0 0 1-1.3 1.3z"/>' +
        '</svg>';

    var banner = null;
    var toggle = null;

    function readConsent() {
        try { return localStorage.getItem(STORAGE_KEY); } catch (e) { return null; }
    }
    function writeConsent(value) {
        try { localStorage.setItem(STORAGE_KEY, value); } catch (e) { /* ignore */ }
    }

    function buildBanner() {
        var el = document.createElement("div");
        el.className = "cookie-banner";
        el.setAttribute("role", "dialog");
        el.setAttribute("aria-live", "polite");
        el.setAttribute("aria-label", "Cookie consent");
        el.innerHTML =
            '<button type="button" class="cookie-banner__close" data-cookie-action="close" aria-label="Close">&times;</button>' +
            '<div class="cookie-banner__icon" aria-hidden="true">' + COOKIE_ICON_SVG + '</div>' +
            '<p class="cookie-banner__title">We value your privacy</p>' +
            '<p class="cookie-banner__text">This site uses cookies to keep the essentials working and, with your consent, to help us understand how the site is used. See our <a href="/cookie-policy/">Cookie Policy</a> and <a href="/privacy-policy/">Privacy Policy</a>.</p>' +
            '<div class="cookie-banner__actions">' +
                '<button type="button" class="cookie-banner__btn cookie-banner__btn--ghost" data-cookie-action="decline">Decline</button>' +
                '<button type="button" class="cookie-banner__btn cookie-banner__btn--primary" data-cookie-action="accept">Accept</button>' +
            '</div>';
        el.addEventListener("click", function (e) {
            var target = e.target.closest("[data-cookie-action]");
            if (!target) return;
            var action = target.getAttribute("data-cookie-action");
            if (action === "accept" || action === "decline") writeConsent(action);
            hideBanner();
            showToggle();
        });
        return el;
    }

    function buildToggle() {
        var btn = document.createElement("button");
        btn.type = "button";
        btn.className = "cookie-toggle";
        btn.setAttribute("aria-label", "Cookie preferences");
        btn.innerHTML = COOKIE_ICON_SVG;
        btn.addEventListener("click", function () {
            hideToggle();
            showBanner();
        });
        return btn;
    }

    function showBanner() {
        if (!banner) banner = buildBanner();
        if (!banner.parentNode) document.body.appendChild(banner);
        requestAnimationFrame(function () { banner.classList.add("is-visible"); });
    }
    function hideBanner() {
        if (!banner) return;
        banner.classList.remove("is-visible");
        setTimeout(function () { if (banner && banner.parentNode) banner.parentNode.removeChild(banner); }, 320);
    }
    function showToggle() {
        if (!toggle) toggle = buildToggle();
        if (!toggle.parentNode) document.body.appendChild(toggle);
        requestAnimationFrame(function () { toggle.classList.add("is-visible"); });
    }
    function hideToggle() {
        if (!toggle) return;
        toggle.classList.remove("is-visible");
        setTimeout(function () { if (toggle && toggle.parentNode) toggle.parentNode.removeChild(toggle); }, 240);
    }

    if (readConsent()) {
        showToggle();
    } else {
        showBanner();
    }
}

function initHeader() {
    var header = document.querySelector(".site-header");
    if (!header) return;

    if (document.querySelector(".hero")) {
        document.body.classList.add("has-hero");
    }

    var scrolled = false;
    function onScroll() {
        var isScrolled = window.scrollY > 24;
        if (isScrolled !== scrolled) {
            scrolled = isScrolled;
            header.classList.toggle("is-scrolled", scrolled);
        }
    }
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
}

function initHero() {
    var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    // Word-by-word reveal for hero headline
    var titles = document.querySelectorAll("[data-reveal-words]");
    titles.forEach(function (el) {
        // Walk child nodes so we preserve <em> and other inline wrappers
        var frag = document.createDocumentFragment();
        var wordIndex = 0;
        function processNode(node, parentWrapper) {
            if (node.nodeType === Node.TEXT_NODE) {
                var parts = node.textContent.split(/(\s+)/);
                parts.forEach(function (part) {
                    if (part === "") return;
                    if (/^\s+$/.test(part)) {
                        (parentWrapper || frag).appendChild(document.createTextNode(part));
                    } else {
                        var mask = document.createElement("span");
                        mask.className = "word";
                        var inner = document.createElement("span");
                        inner.textContent = part;
                        inner.style.animationDelay = (0.08 * wordIndex + 0.15) + "s";
                        wordIndex++;
                        mask.appendChild(inner);
                        (parentWrapper || frag).appendChild(mask);
                    }
                });
            } else if (node.nodeType === Node.ELEMENT_NODE) {
                var clone = node.cloneNode(false);
                Array.from(node.childNodes).forEach(function (child) {
                    processNode(child, clone);
                });
                (parentWrapper || frag).appendChild(clone);
            }
        }
        Array.from(el.childNodes).forEach(function (n) { processNode(n, null); });
        el.textContent = "";
        el.appendChild(frag);
    });

    // Generic reveal-on-load (aside, brandline, etc.)
    document.querySelectorAll(".reveal").forEach(function (el) {
        var delay = parseInt(el.getAttribute("data-reveal-delay") || "0", 10);
        setTimeout(function () { el.classList.add("is-in"); }, delay + 120);
    });

    if (reduced) return;

    // Cursor-tracked aura on the hero
    var hero = document.getElementById("hero");
    var aura = hero && hero.querySelector(".hero-aura");
    if (hero && aura) {
        var rafId = null;
        var target = { x: 60, y: 30 };
        var current = { x: 60, y: 30 };
        hero.addEventListener("pointermove", function (e) {
            var rect = hero.getBoundingClientRect();
            target.x = ((e.clientX - rect.left) / rect.width) * 100;
            target.y = ((e.clientY - rect.top) / rect.height) * 100;
            if (!rafId) tick();
        });
        hero.addEventListener("pointerleave", function () {
            target.x = 60; target.y = 30;
            if (!rafId) tick();
        });
        function tick() {
            current.x += (target.x - current.x) * 0.08;
            current.y += (target.y - current.y) * 0.08;
            aura.style.setProperty("--aura-x", current.x + "%");
            aura.style.setProperty("--aura-y", current.y + "%");
            if (Math.abs(target.x - current.x) > 0.1 || Math.abs(target.y - current.y) > 0.1) {
                rafId = requestAnimationFrame(tick);
            } else {
                rafId = null;
            }
        }
    }

    // Magnetic buttons
    var fine = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
    if (fine) {
        document.querySelectorAll(".btn-magnetic").forEach(function (btn) {
            var strength = 14;
            btn.addEventListener("pointermove", function (e) {
                var rect = btn.getBoundingClientRect();
                var x = e.clientX - rect.left - rect.width / 2;
                var y = e.clientY - rect.top - rect.height / 2;
                btn.style.transform = "translate(" + (x / rect.width) * strength + "px, " + (y / rect.height) * strength + "px)";
            });
            btn.addEventListener("pointerleave", function () {
                btn.style.transform = "";
            });
        });
    }
}

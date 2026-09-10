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
});

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

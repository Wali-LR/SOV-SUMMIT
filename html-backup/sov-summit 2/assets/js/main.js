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
});

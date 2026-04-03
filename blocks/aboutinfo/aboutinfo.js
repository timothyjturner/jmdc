(function () {
  function initReveal() {
    var items = document.querySelectorAll(
      ".jmdc-about-info .jmdc-about-info__container.jmdc-reveal"
    );

    if (!items.length) {
      return;
    }

    if (!("IntersectionObserver" in window)) {
      items.forEach(function (el) {
        el.classList.add("is-visible");
      });
      return;
    }

    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) {
            return;
          }

          entry.target.classList.add("is-visible");
          io.unobserve(entry.target);
        });
      },
      {
        root: null,
        rootMargin: "0px 0px -10% 0px",
        threshold: 0.15,
      }
    );

    items.forEach(function (el) {
      io.observe(el);
    });
  }

  function trapFocus(modal, event) {
    if (event.key !== "Tab") {
      return;
    }

    var focusable = modal.querySelectorAll(
      'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
    );

    if (!focusable.length) {
      event.preventDefault();
      return;
    }

    var first = focusable[0];
    var last = focusable[focusable.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  }

  function initBioModals() {
    var openButtons = document.querySelectorAll("[data-about-info-open]");

    if (!openButtons.length) {
      return;
    }

    openButtons.forEach(function (button) {
      if (button.dataset.aboutInfoBound === "true") {
        return;
      }

      button.dataset.aboutInfoBound = "true";

      var modalId = button.getAttribute("data-about-info-open");
      var modal = document.getElementById(modalId);

      if (!modal) {
        return;
      }

      var closeButtons = modal.querySelectorAll("[data-about-info-close]");
      var previousActiveElement = null;

      function openModal(event) {
        if (event) {
          event.preventDefault();
        }

        previousActiveElement = document.activeElement;
        modal.hidden = false;
        document.documentElement.classList.add("jmdc-about-info-modal-open");
        document.body.classList.add("jmdc-about-info-modal-open");

        window.requestAnimationFrame(function () {
          modal.classList.add("is-open");

          var firstFocusable = modal.querySelector(
            '.jmdc-about-info__modal-close, a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
          );

          if (firstFocusable) {
            firstFocusable.focus();
          }
        });
      }

      function closeModal() {
        modal.classList.remove("is-open");
        document.documentElement.classList.remove("jmdc-about-info-modal-open");
        document.body.classList.remove("jmdc-about-info-modal-open");

        window.setTimeout(function () {
          modal.hidden = true;
        }, 200);

        if (
          previousActiveElement &&
          typeof previousActiveElement.focus === "function"
        ) {
          previousActiveElement.focus();
        }
      }

      button.addEventListener("click", openModal);

      closeButtons.forEach(function (closeButton) {
        closeButton.addEventListener("click", function (event) {
          event.preventDefault();
          closeModal();
        });
      });

      modal.addEventListener("click", function (event) {
        if (
          event.target.hasAttribute("data-about-info-close") ||
          event.target.classList.contains("jmdc-about-info__modal-backdrop")
        ) {
          closeModal();
        }
      });

      modal.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
          event.preventDefault();
          closeModal();
          return;
        }

        trapFocus(modal, event);
      });
    });
  }

  function onReady() {
    initReveal();
    initBioModals();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", onReady);
  } else {
    onReady();
  }
})();
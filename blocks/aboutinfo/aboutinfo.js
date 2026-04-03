(function () {
  function initReveal() {
    var items = document.querySelectorAll(
      ".jmdc-about-info .jmdc-about-info__container.jmdc-reveal"
    );

    if (!items.length) return;

    if (!("IntersectionObserver" in window)) {
      items.forEach(function (el) {
        el.classList.add("is-visible");
      });
      return;
    }

    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;

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

  function initBioModal() {
    var triggers = document.querySelectorAll("[data-bio-trigger]");

    if (!triggers.length) return;

    triggers.forEach(function (trigger) {
      var targetId = trigger.getAttribute("data-bio-trigger");
      var modal = document.querySelector('[data-bio-modal="' + targetId + '"]');

      if (!modal) return;

      var closeBtn = modal.querySelector("[data-bio-close]");

      function openModal() {
        modal.classList.add("is-active");
        document.body.classList.add("jmdc-modal-open");
      }

      function closeModal() {
        modal.classList.remove("is-active");
        document.body.classList.remove("jmdc-modal-open");
      }

      trigger.addEventListener("click", function (e) {
        e.preventDefault();
        openModal();
      });

      if (closeBtn) {
        closeBtn.addEventListener("click", function () {
          closeModal();
        });
      }

      modal.addEventListener("click", function (e) {
        if (e.target === modal) {
          closeModal();
        }
      });

      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
          closeModal();
        }
      });
    });
  }

  function onReady() {
    initReveal();
    initBioModal();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", onReady);
  } else {
    onReady();
  }
})();
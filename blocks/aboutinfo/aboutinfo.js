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

  function setViewportHeight(scope, activePanel) {
    var viewport = scope.querySelector(".jmdc-about-info__viewport");

    if (!viewport || !activePanel) {
      return;
    }

    activePanel.removeAttribute("hidden");
    viewport.style.height = activePanel.offsetHeight + "px";
  }

  function showPanel(scope, panelName) {
    var panels = scope.querySelectorAll("[data-about-info-panel]");
    var button = scope.querySelector("[data-about-info-button]");
    var viewBioLabel =
      scope.getAttribute("data-view-bio-label") || "VIEW BIO";
    var viewQuoteLabel =
      scope.getAttribute("data-view-quote-label") || "VIEW QUOTE";
    var activePanel = null;

    panels.forEach(function (panel) {
      var isActive = panel.getAttribute("data-about-info-panel") === panelName;

      panel.classList.toggle("is-active", isActive);

      if (isActive) {
        panel.removeAttribute("hidden");
        activePanel = panel;
      } else {
        panel.setAttribute("hidden", "hidden");
      }
    });

    if (button) {
      var showingBio = panelName === "bio";
      button.textContent = showingBio ? viewQuoteLabel : viewBioLabel;
      button.setAttribute("aria-pressed", showingBio ? "true" : "false");
    }

    setViewportHeight(scope, activePanel);
  }

  function initAboutInfoToggle() {
    var toggles = document.querySelectorAll("[data-about-info-toggle]");

    if (!toggles.length) {
      return;
    }

    toggles.forEach(function (scope) {
      if (scope.dataset.aboutInfoBound === "true") {
        return;
      }

      scope.dataset.aboutInfoBound = "true";

      var button = scope.querySelector("[data-about-info-button]");
      var quotePanel = scope.querySelector('[data-about-info-panel="quote"]');
      var bioPanel = scope.querySelector('[data-about-info-panel="bio"]');

      if (!quotePanel && !bioPanel) {
        return;
      }

      var startingPanel = quotePanel ? "quote" : "bio";
      showPanel(scope, startingPanel);

      if (button && quotePanel && bioPanel) {
        button.addEventListener("click", function () {
          var isShowingBio = button.getAttribute("aria-pressed") === "true";
          showPanel(scope, isShowingBio ? "quote" : "bio");
        });
      }

      var resizeTimer = null;
      window.addEventListener("resize", function () {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(function () {
          var activePanel = scope.querySelector(
            '[data-about-info-panel].is-active'
          );
          setViewportHeight(scope, activePanel);
        }, 120);
      });
    });
  }

  function onReady() {
    initReveal();
    initAboutInfoToggle();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", onReady);
  } else {
    onReady();
  }
})();
(function () {
  function wrapTextLines(textEl) {
    if (!textEl || textEl.dataset.linesWrapped === "true") {
      return;
    }

    textEl.dataset.linesWrapped = "true";

    var paragraphs = textEl.querySelectorAll("p");

    if (!paragraphs.length) {
      wrapSingleElementLines(textEl);
      return;
    }

    paragraphs.forEach(function (paragraph) {
      wrapSingleElementLines(paragraph);
    });
  }

  function wrapSingleElementLines(el) {
    var nodes = Array.prototype.slice.call(el.childNodes);
    var fragment = document.createDocumentFragment();
    var line = document.createElement("span");
    var hasContent = false;

    line.className = "jmdc-about-info__line";

    function appendLine() {
      if (!hasContent) {
        return;
      }

      fragment.appendChild(line);
      line = document.createElement("span");
      line.className = "jmdc-about-info__line";
      hasContent = false;
    }

    nodes.forEach(function (node) {
      if (node.nodeName === "BR") {
        appendLine();
        return;
      }

      line.appendChild(node.cloneNode(true));

      if (
        node.nodeType === Node.TEXT_NODE &&
        node.textContent.trim() === ""
      ) {
        return;
      }

      hasContent = true;
    });

    appendLine();

    if (!fragment.childNodes.length) {
      return;
    }

    el.innerHTML = "";
    el.appendChild(fragment);
  }

  function prepareLineAnimations() {
    var texts = document.querySelectorAll(
      ".jmdc-about-info__text--quote, .jmdc-about-info__text--bio"
    );

    texts.forEach(function (textEl) {
      wrapTextLines(textEl);

      var lines = textEl.querySelectorAll(".jmdc-about-info__line");

      lines.forEach(function (line, index) {
        line.style.transitionDelay = index * 90 + "ms";
      });
    });
  }

  function playLineAnimation(container) {
    if (!container || container.dataset.linesAnimated === "true") {
      return;
    }

    container.dataset.linesAnimated = "true";
    container.classList.add("jmdc-about-info--lines-visible");
  }

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
        playLineAnimation(el.closest(".jmdc-about-info"));
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
          playLineAnimation(entry.target.closest(".jmdc-about-info"));
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
    var viewBioLabel = scope.getAttribute("data-view-bio-label") || "VIEW BIO";
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
    prepareLineAnimations();
    initReveal();
    initAboutInfoToggle();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", onReady);
  } else {
    onReady();
  }
})();
(function () {
  function run() {
    var sections = document.querySelectorAll(".jmdc-herotext--animate-on-load");
    if (!sections.length) return;

    var reduce =
      window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    sections.forEach(function (section) {
      if (section.dataset.heroAnimated === "1") return;
      section.dataset.heroAnimated = "1";

      var desc = section.querySelector(".jmdc-herotext__description");
      if (desc) {
        desc.style.transitionDelay = "160ms";
      }

      if (reduce) {
        section.classList.add("is-visible");
        return;
      }

      setTimeout(function () {
        section.classList.add("is-visible");
      }, 120);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", run);
  } else {
    run();
  }
})();

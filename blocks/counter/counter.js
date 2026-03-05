(function () {
  var sections = document.querySelectorAll(".jmdc-counter");
  if (!sections.length) return;

  var reduce = window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function animate(el) {
    var m = (el.textContent || "").trim().match(/^([^0-9-]*)(-?[\d,.]+)(.*)$/);
    if (!m) return;
    var end = Number(m[2].replace(/,/g, ""));
    if (!Number.isFinite(end)) return;
    if (reduce) {
      el.textContent = m[1] + Math.round(end).toLocaleString() + m[3];
      return;
    }

    var start = null;
    function frame(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / 1200, 1);
      el.textContent = m[1] + Math.round(end * p).toLocaleString() + m[3];
      if (p < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }

  function run(section) {
    if (section.dataset.counterDone) return;
    section.dataset.counterDone = "1";
    section.querySelectorAll(".jmdc-counter__number").forEach(function (el, i) {
      setTimeout(function () { animate(el); }, i * 90);
    });
  }

  if (!("IntersectionObserver" in window)) return sections.forEach(run);
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      run(entry.target);
      io.unobserve(entry.target);
    });
  }, { threshold: 0.25, rootMargin: "0px 0px -10% 0px" });

  sections.forEach(function (section) { io.observe(section); });
})();

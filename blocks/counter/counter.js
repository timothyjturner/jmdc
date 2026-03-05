(function () {
  var counterSections = document.querySelectorAll(".jmdc-counter");

  if (!counterSections.length) {
    return;
  }

  var prefersReducedMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function parseCounterValue(text) {
    var cleanText = (text || "").trim();
    var match = cleanText.match(/^([^0-9-]*)(-?[0-9][\d,]*\.?\d*)(.*)$/);

    if (!match) {
      return null;
    }

    var numericText = match[2].replace(/,/g, "");
    var target = Number(numericText);

    if (!Number.isFinite(target)) {
      return null;
    }

    var decimalPart = numericText.split(".")[1];

    return {
      prefix: match[1] || "",
      suffix: match[3] || "",
      target: target,
      decimals: decimalPart ? decimalPart.length : 0,
    };
  }

  function formatNumber(value, decimals) {
    if (decimals > 0) {
      return value.toLocaleString(undefined, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
      });
    }

    return Math.round(value).toLocaleString();
  }

  function animateCounter(el, duration) {
    var parsed = parseCounterValue(el.textContent);

    if (!parsed) {
      return;
    }

    var startTime = null;

    function frame(timestamp) {
      if (!startTime) {
        startTime = timestamp;
      }

      var elapsed = timestamp - startTime;
      var progress = Math.min(elapsed / duration, 1);
      var currentValue = parsed.target * progress;

      el.textContent =
        parsed.prefix +
        formatNumber(currentValue, parsed.decimals) +
        parsed.suffix;

      if (progress < 1) {
        window.requestAnimationFrame(frame);
      }
    }

    if (prefersReducedMotion) {
      el.textContent =
        parsed.prefix +
        formatNumber(parsed.target, parsed.decimals) +
        parsed.suffix;
      return;
    }

    window.requestAnimationFrame(frame);
  }

  function runCounters(section) {
    if (section.dataset.counterAnimated === "true") {
      return;
    }

    section.dataset.counterAnimated = "true";
    var numbers = section.querySelectorAll(".jmdc-counter__number");

    numbers.forEach(function (el, index) {
      window.setTimeout(function () {
        animateCounter(el, 1500);
      }, index * 120);
    });
  }

  if (!("IntersectionObserver" in window)) {
    counterSections.forEach(runCounters);
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }

        runCounters(entry.target);
        observer.unobserve(entry.target);
      });
    },
    {
      threshold: 0.3,
      rootMargin: "0px 0px -10% 0px",
    }
  );

  counterSections.forEach(function (section) {
    observer.observe(section);
  });
})();

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

  function initSliders() {
    var sliders = document.querySelectorAll(".jmdc-about-info [data-slider]");

    if (!sliders.length) {
      return;
    }

    sliders.forEach(function (slider) {
      var slides = slider.querySelectorAll("[data-slide]");
      var dots = slider.querySelectorAll("[data-dot]");
      var autoplay = slider.getAttribute("data-autoplay") === "true";
      var autoplaySpeed = parseInt(
        slider.getAttribute("data-autoplay-speed") || "5000",
        10
      );
      var current = 0;
      var timer = null;

      if (!slides.length) {
        return;
      }

      function showSlide(index) {
        slides.forEach(function (slide, i) {
          var isActive = i === index;
          slide.classList.toggle("is-active", isActive);

          if (isActive) {
            slide.removeAttribute("hidden");
          } else {
            slide.setAttribute("hidden", "hidden");
          }
        });

        dots.forEach(function (dot, i) {
          var isActive = i === index;
          dot.classList.toggle("is-active", isActive);
          dot.setAttribute("aria-pressed", isActive ? "true" : "false");
        });

        current = index;
      }

      function nextSlide() {
        var next = current + 1 >= slides.length ? 0 : current + 1;
        showSlide(next);
      }

      function startAutoplay() {
        if (!autoplay || slides.length < 2) {
          return;
        }

        stopAutoplay();
        timer = window.setInterval(nextSlide, autoplaySpeed);
      }

      function stopAutoplay() {
        if (timer) {
          window.clearInterval(timer);
          timer = null;
        }
      }

      dots.forEach(function (dot) {
        dot.addEventListener("click", function () {
          var index = parseInt(dot.getAttribute("data-dot"), 10);

          if (Number.isNaN(index)) {
            return;
          }

          showSlide(index);
          startAutoplay();
        });
      });

      slider.addEventListener("mouseenter", stopAutoplay);
      slider.addEventListener("mouseleave", startAutoplay);
      slider.addEventListener("focusin", stopAutoplay);
      slider.addEventListener("focusout", startAutoplay);

      showSlide(0);
      startAutoplay();
    });
  }

  function onReady() {
    initReveal();
    initSliders();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", onReady);
  } else {
    onReady();
  }
})();
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

  function fitQuoteText(slider) {
    var slides = slider.querySelectorAll("[data-slide]");
    var viewport = slider.querySelector(".jmdc-about-info__slider-track");

    if (!slides.length || !viewport) {
      return;
    }

    slides.forEach(function (slide) {
      var text = slide.querySelector("[data-fit-text]");

      if (!text) {
        return;
      }

      text.style.setProperty("--quote-font-size", "");
      slide.removeAttribute("hidden");
      slide.classList.add("is-measuring");

      var maxSize = window.innerWidth <= 600 ? 22 :
                    window.innerWidth <= 1024 ? 32 :
                    window.innerWidth <= 1200 ? 38 : 44;

      var minSize = window.innerWidth <= 600 ? 16 : 20;
      var size = maxSize;

      text.style.setProperty("--quote-font-size", size + "px");

      var availableHeight = viewport.clientHeight || 420;

      while (
        size > minSize &&
        (text.scrollHeight > availableHeight * 0.92 || text.scrollWidth > text.clientWidth + 2)
      ) {
        size -= 1;
        text.style.setProperty("--quote-font-size", size + "px");
      }

      slide.classList.remove("is-measuring");

      if (!slide.classList.contains("is-active")) {
        slide.setAttribute("hidden", "hidden");
      }
    });

    var maxHeight = 0;

    slides.forEach(function (slide) {
      slide.removeAttribute("hidden");
      slide.classList.add("is-measuring");

      var height = slide.offsetHeight;
      if (height > maxHeight) {
        maxHeight = height;
      }

      slide.classList.remove("is-measuring");

      if (!slide.classList.contains("is-active")) {
        slide.setAttribute("hidden", "hidden");
      }
    });

    if (maxHeight) {
      viewport.style.minHeight = maxHeight + "px";
    }
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

      fitQuoteText(slider);
      showSlide(0);
      startAutoplay();

      var resizeTimer = null;
      window.addEventListener("resize", function () {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(function () {
          fitQuoteText(slider);
          showSlide(current);
        }, 120);
      });
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
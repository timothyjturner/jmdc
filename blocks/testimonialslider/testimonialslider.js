(function () {
  function getMaxFontSize() {
    if (window.innerWidth <= 767) return 18;
    if (window.innerWidth <= 1024) return 22;
    return 26;
  }

  function getMinFontSize() {
    return window.innerWidth <= 767 ? 14 : 16;
  }

  function fitSlideText(slide) {
    var text = slide.querySelector("[data-fit-text]");
    var wrap = slide.querySelector(".jmdc-testimonial-slider__text-wrap");

    if (!text || !wrap) {
      return;
    }

    var maxSize = getMaxFontSize();
    var minSize = getMinFontSize();
    var size = maxSize;

    text.style.setProperty("--quote-font-size", maxSize + "px");

    var previousHidden = slide.hasAttribute("hidden");
    var previousVisibility = slide.style.visibility;
    var previousPosition = slide.style.position;
    var previousPointerEvents = slide.style.pointerEvents;
    var previousOpacity = slide.style.opacity;

    slide.removeAttribute("hidden");
    slide.style.visibility = "hidden";
    slide.style.position = "relative";
    slide.style.pointerEvents = "none";
    slide.style.opacity = "1";

    var maxWidth = wrap.clientWidth;
    var maxHeight = window.innerWidth <= 767 ? 260 : 380;

    while (
      size > minSize &&
      (text.scrollWidth > maxWidth + 2 || text.scrollHeight > maxHeight)
    ) {
      size -= 1;
      text.style.setProperty("--quote-font-size", size + "px");
    }

    slide.style.visibility = previousVisibility;
    slide.style.position = previousPosition;
    slide.style.pointerEvents = previousPointerEvents;
    slide.style.opacity = previousOpacity;

    if (previousHidden) {
      slide.setAttribute("hidden", "hidden");
    }
  }

  function measureActiveSlideHeight(swiper) {
    if (!swiper || !swiper.slides || typeof swiper.activeIndex === "undefined") {
      return;
    }

    var activeSlide = swiper.slides[swiper.activeIndex];
    var wrapper = swiper.wrapperEl;

    if (!activeSlide || !wrapper) {
      return;
    }

    wrapper.style.height = activeSlide.offsetHeight + "px";
  }

  function fitAllSlides(containerEl) {
    var slides = containerEl.querySelectorAll(".swiper-slide");

    slides.forEach(function (slide) {
      fitSlideText(slide);
    });
  }

  function initTestimonialSlider() {
    if (typeof Swiper === "undefined") return;

    var testimonialSliders = document.querySelectorAll(".jmdc-testimonial-slider__container.swiper");
    if (!testimonialSliders.length) return;

    testimonialSliders.forEach(function (containerEl) {
      if (containerEl.swiper) return;

      var paginationEl = containerEl.querySelector(".jmdc-testimonial-slider__pagination");
      if (!paginationEl) return;

      fitAllSlides(containerEl);

      var swiper = new Swiper(containerEl, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        speed: 900,
        loop: true,
        autoHeight: false,
        spaceBetween: 20,
        autoplay: {
          delay: 6000,
          disableOnInteraction: false
        },
        pagination: {
          el: paginationEl,
          clickable: true,
          bulletClass: "jmdc-testimonial-slider__bullet swiper-pagination-bullet",
          bulletActiveClass: "jmdc-testimonial-slider__bullet--active swiper-pagination-bullet-active"
        },
        on: {
          init: function () {
            measureActiveSlideHeight(this);
          },
          slideChangeTransitionStart: function () {
            measureActiveSlideHeight(this);
          },
          resize: function () {
            fitAllSlides(containerEl);
            this.update();
            measureActiveSlideHeight(this);
          }
        }
      });

      var resizeTimer = null;
      window.addEventListener("resize", function () {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(function () {
          fitAllSlides(containerEl);
          swiper.update();
          measureActiveSlideHeight(swiper);
        }, 120);
      });
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initTestimonialSlider);
  } else {
    initTestimonialSlider();
  }
})();
(function () {
  function getMaxFontSize() {
    if (window.innerWidth <= 767) return 18;
    if (window.innerWidth <= 1024) return 22;
    return 26;
  }

  function getMinFontSize() {
    return window.innerWidth <= 767 ? 14 : 16;
  }

  function getMaxTextHeight() {
    if (window.innerWidth <= 767) return 340;
    if (window.innerWidth <= 1024) return 440;
    return 520;
  }

  function fitSlideText(slide) {
    var text = slide.querySelector("[data-fit-text]");
    var wrap = slide.querySelector(".jmdc-testimonial-slider__text-wrap");

    if (!text || !wrap) {
      return;
    }

    var maxSize = getMaxFontSize();
    var minSize = getMinFontSize();
    var maxHeight = getMaxTextHeight();
    var size = maxSize;

    text.style.setProperty("--quote-font-size", maxSize + "px");

    var prevHidden = slide.hasAttribute("hidden");
    var prevDisplay = slide.style.display;
    var prevVisibility = slide.style.visibility;
    var prevPosition = slide.style.position;
    var prevOpacity = slide.style.opacity;
    var prevPointerEvents = slide.style.pointerEvents;

    slide.removeAttribute("hidden");
    slide.style.display = "block";
    slide.style.visibility = "hidden";
    slide.style.position = "relative";
    slide.style.opacity = "1";
    slide.style.pointerEvents = "none";

    while (size > minSize && text.scrollHeight > maxHeight) {
      size -= 1;
      text.style.setProperty("--quote-font-size", size + "px");
    }

    slide.style.display = prevDisplay;
    slide.style.visibility = prevVisibility;
    slide.style.position = prevPosition;
    slide.style.opacity = prevOpacity;
    slide.style.pointerEvents = prevPointerEvents;

    if (prevHidden) {
      slide.setAttribute("hidden", "hidden");
    }
  }

  function fitAllSlides(containerEl) {
    var slides = containerEl.querySelectorAll(".swiper-slide");

    slides.forEach(function (slide) {
      fitSlideText(slide);
    });
  }

  function refreshSliderHeight(swiper) {
    if (!swiper) return;

    swiper.updateSlides();
    swiper.update();
    swiper.updateAutoHeight(300);
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
        autoHeight: true,
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
            refreshSliderHeight(this);
          },
          slideChangeTransitionStart: function () {
            refreshSliderHeight(this);
          },
          slideChangeTransitionEnd: function () {
            refreshSliderHeight(this);
          },
          resize: function () {
            fitAllSlides(containerEl);
            refreshSliderHeight(this);
          }
        }
      });

      var resizeTimer = null;
      window.addEventListener("resize", function () {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(function () {
          fitAllSlides(containerEl);
          refreshSliderHeight(swiper);
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
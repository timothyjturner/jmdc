(function () {
  function setActiveSlideHeight(swiper) {
    if (!swiper || !swiper.slides || typeof swiper.activeIndex === "undefined") {
      return;
    }

    var activeSlide = swiper.slides[swiper.activeIndex];
    var wrapper = swiper.wrapperEl;

    if (!activeSlide || !wrapper) {
      return;
    }

    var minHeight = window.innerWidth <= 767 ? 320 : window.innerWidth <= 1024 ? 380 : 420;
    var measuredHeight = activeSlide.offsetHeight;
    wrapper.style.height = Math.max(measuredHeight, minHeight) + "px";
  }

  function initTestimonialSlider() {
    if (typeof Swiper === "undefined") return;

    var testimonialSliders = document.querySelectorAll(".jmdc-testimonial-slider__container.swiper");
    if (!testimonialSliders.length) return;

    testimonialSliders.forEach(function (containerEl) {
      if (containerEl.swiper) return;

      var paginationEl = containerEl.querySelector(".jmdc-testimonial-slider__pagination");
      if (!paginationEl) return;

      new Swiper(containerEl, {
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
            setActiveSlideHeight(this);
          },
          slideChangeTransitionStart: function () {
            setActiveSlideHeight(this);
          },
          slideChangeTransitionEnd: function () {
            setActiveSlideHeight(this);
          },
          resize: function () {
            this.update();
            setActiveSlideHeight(this);
          }
        }
      });
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initTestimonialSlider);
  } else {
    initTestimonialSlider();
  }
})();
;(function () {
  function initCaseStudySlider() {
    if (typeof Swiper === 'undefined') return;

    var sliderContainers = document.querySelectorAll('.jmdc-case-study-slider.swiper');
    if (!sliderContainers.length) return;

    sliderContainers.forEach(function (container) {
      if (container.swiper) return;

      var paginationEl = container.querySelector('.jmdc-case-study-slider__pagination');
      if (!paginationEl) return;

      new Swiper(container, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 16,
        speed: 1500,
        watchSlidesProgress: true,
        breakpoints: {
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 }
        },
        pagination: {
          el: paginationEl,
          clickable: true
        }
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCaseStudySlider);
  } else {
    initCaseStudySlider();
  }
})();


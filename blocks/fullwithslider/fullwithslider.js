(function () {
  function initFullWidthSlider() {
    if (typeof Swiper === 'undefined') return;

    var fullWidthSliders = document.querySelectorAll('.jmdc-full-with-slider__container.swiper');
    if (!fullWidthSliders.length) return;

    fullWidthSliders.forEach(function (containerEl) {
      // Avoid re-initializing if Swiper instance already exists.
      if (containerEl.swiper) return;

      var sectionEl = containerEl.closest('.jmdc-full-with-slider');
      var paginationEl = sectionEl ? sectionEl.querySelector('.jmdc-full-with-slider__pagination') : null;

      var options = {
        slidesPerView: 1,
        slidesPerGroup: 1,
        loop: true,
        speed: 1200,
        spaceBetween: 0,
      };

      if (paginationEl) {
        options.pagination = {
          el: paginationEl,
          clickable: true
        };
      }

      new Swiper(containerEl, options);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFullWidthSlider);
  } else {
    initFullWidthSlider();
  }
})();


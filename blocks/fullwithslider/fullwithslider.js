(function () {
  function initFullWidthSlider() {
    if (typeof Swiper === 'undefined') return;

    var fullWidthSliders = document.querySelectorAll('.jmdc-full-with-slider__container.swiper');
    if (!fullWidthSliders.length) return;

    fullWidthSliders.forEach(function (containerEl) {
      // Avoid re-initializing if Swiper instance already exists.
      if (containerEl.swiper) return;

      new Swiper(containerEl, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        loop: true,
        speed: 1200,
        spaceBetween: 0,
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFullWidthSlider);
  } else {
    initFullWidthSlider();
  }
})();


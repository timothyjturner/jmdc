document.addEventListener('DOMContentLoaded', function () {
  if (typeof Swiper === 'undefined') return;

  var sliderContainers = document.querySelectorAll('.jmdc-case-study-slider');
  if (!sliderContainers.length) return;

  sliderContainers.forEach(function (container) {

    if (container.swiper) return;

    var paginationEl = container.querySelector('.jmdc-case-study-slider__pagination');
    if (!paginationEl) return;

    new Swiper(container, {
      wrapperClass: 'jmdc-case-study-slider__track',
      slideClass: 'jmdc-case-study-slider__slide',

      slidesPerView: 1,
      slidesPerGroup: 1,
      spaceBetween: 16,
      speed: 1500,

      watchSlidesProgress: true,

      breakpoints: {
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
      },

      pagination: {
        el: paginationEl,
        clickable: true,
      }
    });
  });
});


(function () {
  function initTestimonialSlider() {
    if (typeof Swiper === 'undefined') return;

    var testimonialSliders = document.querySelectorAll('.jmdc-testimonial-slider__container.swiper');
    if (!testimonialSliders.length) return;

    testimonialSliders.forEach(function (containerEl) {
      if (containerEl.swiper) return;

      var paginationEl = containerEl.querySelector('.jmdc-testimonial-slider__pagination');
      if (!paginationEl) return;

      new Swiper(containerEl, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        speed: 1500,
        loop: true,
        autoHeight: true,
        spaceBetween: 20,
        autoplay: {
          delay: 6000, // 6 seconds per slide
          disableOnInteraction: false
        },
        pagination: {
          el: paginationEl,
          clickable: true,
          bulletClass: 'jmdc-testimonial-slider__bullet swiper-pagination-bullet',
          bulletActiveClass: 'jmdc-testimonial-slider__bullet--active swiper-pagination-bullet-active'
        }
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTestimonialSlider);
  } else {
    initTestimonialSlider();
  }
})();

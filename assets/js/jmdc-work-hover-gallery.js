(function () {
    function makeGallery(el) {
      const imgs = Array.from(el.querySelectorAll(".jmdc-hover-gallery__img"));
      if (imgs.length <= 1) return;
  
      let idx = 0;
      let timer = null;
      const interval = parseInt(el.getAttribute("data-interval") || "1800", 10);
  
      function show(i) {
        imgs.forEach((img, n) => {
          img.classList.toggle("is-active", n === i);
        });
      }
  
      function start() {
        if (timer) return;
        timer = window.setInterval(() => {
          idx = (idx + 1) % imgs.length;
          show(idx);
        }, interval);
      }
  
      function stop() {
        if (!timer) return;
        window.clearInterval(timer);
        timer = null;
        idx = 0;
        show(idx);
      }
  
      // Hover/touch behavior
      el.addEventListener("mouseenter", start);
      el.addEventListener("mouseleave", stop);
  
      // Mobile: tap to start/stop
      el.addEventListener("click", function (e) {
        // Prevent accidental drags selecting images
        e.preventDefault();
        if (timer) stop();
        else start();
      });
    }
  
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll('[data-hover-gallery="1"]').forEach(makeGallery);
    });
  })();
(function() {
  "use strict";

  // ===============================
  // Scroll Header
  // ===============================
  function toggleScrolled() {
    const body = document.querySelector('body');
    const header = document.querySelector('#header');

    if (!header) return;

    if (!header.classList.contains('scroll-up-sticky') &&
        !header.classList.contains('sticky-top') &&
        !header.classList.contains('fixed-top')) return;

    window.scrollY > 100
      ? body.classList.add('scrolled')
      : body.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  // ===============================
  // Mobile Nav
  // ===============================
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.body.classList.toggle('mobile-nav-active');

    if (mobileNavToggleBtn) {
      mobileNavToggleBtn.classList.toggle('bi-list');
      mobileNavToggleBtn.classList.toggle('bi-x');
    }
  }

  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  document.querySelectorAll('#navmenu a').forEach(el => {
    el.addEventListener('click', () => {
      if (document.body.classList.contains('mobile-nav-active')) {
        mobileNavToogle();
      }
    });
  });

  // ===============================
  // Preloader
  // ===============================
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => preloader.remove());
  }

  // ===============================
  // Scroll Top
  // ===============================
  const scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (!scrollTop) return;

    window.scrollY > 100
      ? scrollTop.classList.add('active')
      : scrollTop.classList.remove('active');
  }

  if (scrollTop) {
    scrollTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('load', toggleScrollTop);
    document.addEventListener('scroll', toggleScrollTop);
  }

  // ===============================
  // AOS
  // ===============================
  function aosInit() {
    if (typeof AOS !== "undefined") {
      AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        mirror: false
      });
    }
  }
  window.addEventListener('load', aosInit);

  // ===============================
  // Typed.js
  // ===============================
  const typedEl = document.querySelector('.typed');

  if (typedEl && typeof Typed !== "undefined") {
    let strings = typedEl.getAttribute('data-typed-items');
    if (strings) {
      strings = strings.split(',');
      new Typed('.typed', {
        strings: strings,
        loop: true,
        typeSpeed: 100,
        backSpeed: 50,
        backDelay: 2000
      });
    }
  }

  // ===============================
  // PureCounter
  // ===============================
  if (typeof PureCounter !== "undefined") {
    new PureCounter();
  }

  // ===============================
  // Waypoints (Skills)
  // ===============================
  if (typeof Waypoint !== "undefined") {
    document.querySelectorAll('.skills-animation').forEach((item) => {
      new Waypoint({
        element: item,
        offset: '80%',
        handler: function() {
          item.querySelectorAll('.progress-bar').forEach(el => {
            el.style.width = el.getAttribute('aria-valuenow') + '%';
          });
        }
      });
    });
  }

  // ===============================
  // Swiper
  // ===============================
  function initSwiper() {
    if (typeof Swiper === "undefined") return;

    document.querySelectorAll(".init-swiper").forEach((el) => {
      const configEl = el.querySelector(".swiper-config");
      if (!configEl) return;

      const config = JSON.parse(configEl.innerHTML.trim());
      new Swiper(el, config);
    });
  }

  window.addEventListener("load", initSwiper);

  // ===============================
  // GLightbox
  // ===============================
  if (typeof GLightbox !== "undefined") {
    GLightbox({ selector: '.glightbox' });
  }

  // ===============================
  // Isotope (Portfolio)
  // ===============================
  document.querySelectorAll('.isotope-layout').forEach((layoutEl) => {

    if (typeof Isotope === "undefined") return;

    const container = layoutEl.querySelector('.isotope-container');
    if (!container) return;

    let iso;

    const init = () => {
      iso = new Isotope(container, {
        itemSelector: '.isotope-item',
        layoutMode: layoutEl.dataset.layout || 'masonry',
      });
    };

    if (typeof imagesLoaded !== "undefined") {
      imagesLoaded(container, init);
    } else {
      init();
    }

    layoutEl.querySelectorAll('.isotope-filters li').forEach(btn => {
      btn.addEventListener('click', function() {

        layoutEl.querySelector('.filter-active')?.classList.remove('filter-active');
        this.classList.add('filter-active');

        iso?.arrange({
          filter: this.getAttribute('data-filter')
        });

        if (typeof AOS !== "undefined") {
          AOS.refresh();
        }
      });
    });

  });

})();
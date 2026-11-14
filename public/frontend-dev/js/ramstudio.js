/* ==========================================================================
   RAMStudio — Frontend interactions
   Vanilla JS · no dependencies beyond Bootstrap 5 bundle
   ========================================================================== */
(function () {
  'use strict';

  /* ------------------------------------------------------------------
     Navbar: shadow on scroll + mobile drawer
     ------------------------------------------------------------------ */
  function initNavbar() {
    var navbar = document.querySelector('.rs-navbar');
    if (navbar) {
      var onScroll = function () {
        navbar.classList.toggle('is-scrolled', window.scrollY > 12);
      };
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    }

    var toggle = document.querySelector('[data-rs-menu-toggle]');
    var menu = document.querySelector('[data-rs-menu]');
    if (toggle && menu) {
      toggle.addEventListener('click', function () {
        var open = menu.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        var icon = toggle.querySelector('i');
        if (icon) {
          icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
        }
      });

      // Close when a link is clicked
      menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          menu.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
          var icon = toggle.querySelector('i');
          if (icon) icon.className = 'bi bi-list';
        });
      });

      // Close on resize to desktop
      window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
          menu.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
          var icon = toggle.querySelector('i');
          if (icon) icon.className = 'bi bi-list';
        }
      });
    }
  }

  /* ------------------------------------------------------------------
     Scroll reveal
     ------------------------------------------------------------------ */
  function initReveal() {
    var items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
      items.forEach(function (el) {
        el.classList.add('is-revealed');
      });
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            var el = entry.target;
            var delay = parseInt(el.getAttribute('data-reveal-delay') || '0', 10);
            setTimeout(function () {
              el.classList.add('is-revealed');
            }, delay);
            observer.unobserve(el);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -60px 0px' }
    );

    items.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* ------------------------------------------------------------------
     Animated counters
     ------------------------------------------------------------------ */
  function initCounters() {
    var counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    var animate = function (el) {
      var target = parseFloat(el.getAttribute('data-count')) || 0;
      var duration = 1400;
      var start = null;
      var decimals = (el.getAttribute('data-count').split('.')[1] || '').length;

      function step(ts) {
        if (!start) start = ts;
        var progress = Math.min((ts - start) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        var value = target * eased;
        el.textContent = decimals
          ? value.toFixed(decimals)
          : Math.round(value).toLocaleString('id-ID');
        if (progress < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    };

    if (!('IntersectionObserver' in window)) {
      counters.forEach(animate);
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animate(entry.target);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.4 }
    );

    counters.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* ------------------------------------------------------------------
     Back to top
     ------------------------------------------------------------------ */
  function initBackToTop() {
    var btn = document.querySelector('[data-rs-totop]');
    if (!btn) return;

    var onScroll = function () {
      btn.classList.toggle('is-visible', window.scrollY > 500);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ------------------------------------------------------------------
     Portfolio / blog client-side filtering
     ------------------------------------------------------------------ */
  function initFilters() {
    var groups = document.querySelectorAll('[data-rs-filter-group]');
    groups.forEach(function (group) {
      var targetSelector = group.getAttribute('data-rs-filter-group');
      var target = document.querySelector(targetSelector);
      if (!target) return;

      var buttons = group.querySelectorAll('[data-filter]');
      var items = target.querySelectorAll('[data-category]');

      buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var value = btn.getAttribute('data-filter');

          buttons.forEach(function (b) {
            b.classList.remove('is-active');
            b.setAttribute('aria-pressed', 'false');
          });
          btn.classList.add('is-active');
          btn.setAttribute('aria-pressed', 'true');

          items.forEach(function (item) {
            var match =
              value === 'all' || item.getAttribute('data-category') === value;
            item.style.display = match ? '' : 'none';
          });
        });
      });
    });
  }

  /* ------------------------------------------------------------------
     Portfolio toolbar: auto-submit when a filter select changes
     ------------------------------------------------------------------ */
  function initPortfolioToolbar() {
    var toolbar = document.querySelector('[data-rs-pf-toolbar]');
    if (!toolbar) return;

    toolbar.querySelectorAll('[data-rs-pf-tech]').forEach(function (select) {
      select.addEventListener('change', function () {
        if (typeof toolbar.requestSubmit === 'function') {
          toolbar.requestSubmit();
        } else {
          toolbar.submit();
        }
      });
    });
  }

  /* ------------------------------------------------------------------
     FAQ centre: instant search, collapse/expand all, copy link
     ------------------------------------------------------------------ */
  function initFaqSearch() {
    var root = document.querySelector('[data-rs-faq-root]');
    if (!root) return;

    var input = root.querySelector('[data-rs-faq-search]');
    var clearBtn = root.querySelector('[data-rs-faq-clear]');
    var counters = Array.prototype.slice.call(root.querySelectorAll('[data-rs-faq-count]'));
    var empty = root.querySelector('[data-rs-faq-empty]');
    var items = Array.prototype.slice.call(root.querySelectorAll('[data-rs-faq-item]'));
    var total = items.length;

    // Accordion panel that wraps every item, used by collapse/expand all.
    var panels = Array.prototype.slice.call(root.querySelectorAll('.accordion-collapse'));

    function normalize(value) {
      return (value || '').toLowerCase().trim();
    }

    function apply() {
      var term = normalize(input ? input.value : '');
      var visible = 0;

      items.forEach(function (item) {
        var match = term === '' || normalize(item.getAttribute('data-rs-faq-text')).indexOf(term) !== -1;
        item.hidden = !match;
        if (match) {
          // Reveal immediately so the scroll-reveal animation cannot hide a
          // row that the visitor just searched for.
          item.classList.add('is-revealed');
          visible++;
        }
      });

      counters.forEach(function (el) {
        el.textContent = visible;
      });
      if (clearBtn) clearBtn.classList.toggle('is-visible', term !== '');
      if (empty) empty.hidden = visible !== 0;
      root.classList.toggle('is-filtering', term !== '');
    }

    if (input) {
      input.addEventListener('input', apply);
      input.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
          input.value = '';
          apply();
        }
      });
    }

    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        if (!input) return;
        input.value = '';
        apply();
        input.focus();
      });
    }

    root.querySelectorAll('[data-rs-faq-toggle-all]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var expand = btn.getAttribute('data-rs-faq-toggle-all') === 'expand';

        panels.forEach(function (panel) {
          var instance = window.bootstrap && window.bootstrap.Collapse
            ? window.bootstrap.Collapse.getOrCreateInstance(panel, { toggle: false })
            : null;

          if (instance) {
            expand ? instance.show() : instance.hide();
          } else {
            panel.classList.toggle('show', expand);
          }
        });
      });
    });

    // Deep link support: /faq?q=... pre-fills the search box.
    if (input) {
      var params = new URLSearchParams(window.location.search);
      var initial = params.get('q');
      if (initial) input.value = initial;
    }

    apply();

    // Keep the total count available for the aria-live region.
    counters.forEach(function (el) {
      if (!el.textContent) el.textContent = total;
    });
  }

  /* ------------------------------------------------------------------
     Contact form: client-side validation + AJAX submit
     ------------------------------------------------------------------ */
  function initInquiryForm() {
    var form = document.querySelector('[data-rs-inquiry-form]');
    if (!form) return;

    // The alert container may live outside the <form> (e.g. in the panel head),
    // so fall back to a document-level lookup.
    var alertBox =
      form.querySelector('[data-rs-form-alert]') || document.querySelector('[data-rs-form-alert]');
    var submitBtn = form.querySelector('[type="submit"]');
    var submitLabel = submitBtn ? submitBtn.innerHTML : '';

    function showAlert(type, message) {
      if (!alertBox) return;
      var icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
      var cls = type === 'success' ? 'rs-alert--success' : 'rs-alert--danger';
      alertBox.innerHTML =
        '<div class="rs-alert ' + cls + '"><i class="bi ' + icon + '"></i><div>' + message + '</div></div>';
      alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearAlert() {
      if (alertBox) alertBox.innerHTML = '';
    }

    function setLoading(loading) {
      if (!submitBtn) return;
      submitBtn.disabled = loading;
      submitBtn.innerHTML = loading
        ? '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...'
        : submitLabel;
    }

    function markInvalid(field, message) {
      field.classList.add('is-invalid');
      var feedback = field.parentElement.querySelector('.invalid-feedback');
      if (feedback) feedback.textContent = message;
    }

    function clearInvalid() {
      form.querySelectorAll('.is-invalid').forEach(function (el) {
        el.classList.remove('is-invalid');
      });
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      clearAlert();
      clearInvalid();

      var name = form.querySelector('[name="name"]');
      var email = form.querySelector('[name="email"]');
      var message = form.querySelector('[name="message"]');
      var valid = true;

      if (!name.value.trim()) {
        markInvalid(name, 'Nama wajib diisi.');
        valid = false;
      }
      if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        markInvalid(email, 'Masukkan alamat email yang valid.');
        valid = false;
      }
      if (!message.value.trim() || message.value.trim().length < 10) {
        markInvalid(message, 'Pesan minimal 10 karakter.');
        valid = false;
      }

      if (!valid) {
        showAlert('error', 'Mohon lengkapi data yang diperlukan dengan benar.');
        return;
      }

      var endpoint = form.getAttribute('data-endpoint');
      var csrf = document.querySelector('meta[name="csrf-token"]');
      var payload = new FormData(form);

      setLoading(true);

      fetch(endpoint, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...(csrf ? { 'X-CSRF-TOKEN': csrf.getAttribute('content') } : {}),
        },
        body: payload,
      })
        .then(function (response) {
          return response.json().then(function (data) {
            return { ok: response.ok, status: response.status, data: data };
          });
        })
        .then(function (result) {
          if (result.ok && result.data.success) {
            showAlert(
              'success',
              result.data.message ||
                'Terima kasih! Pesan Anda telah kami terima dan akan segera dihubungi.'
            );
            form.reset();
          } else if (result.status === 422 && result.data.errors) {
            Object.keys(result.data.errors).forEach(function (key) {
              var field = form.querySelector('[name="' + key + '"]');
              if (field) markInvalid(field, result.data.errors[key][0]);
            });
            showAlert('error', 'Mohon periksa kembali data yang Anda masukkan.');
          } else {
            showAlert(
              'error',
              result.data.message || 'Maaf, terjadi kesalahan. Silakan coba lagi.'
            );
          }
        })
        .catch(function () {
          showAlert(
            'error',
            'Tidak dapat terhubung ke server. Periksa koneksi Anda lalu coba lagi.'
          );
        })
        .finally(function () {
          setLoading(false);
        });
    });
  }

  /* ------------------------------------------------------------------
     Testimonials: auto-slider (marquee) fed by the REST API
     ------------------------------------------------------------------ */
  function initTestimonialSlider() {
    var root = document.querySelector('[data-rs-testimonials]');
    if (!root) return;

    var viewport = root.querySelector('[data-rs-marquee-viewport]');
    var track = root.querySelector('[data-rs-marquee-track]');
    var controls = root.querySelector('[data-rs-marquee-controls]');
    var prevBtn = root.querySelector('[data-rs-marquee-prev]');
    var nextBtn = root.querySelector('[data-rs-marquee-next]');
    if (!viewport || !track) return;

    var endpoint = root.getAttribute('data-endpoint');
    var speed = parseFloat(root.getAttribute('data-speed') || '46') || 46; // px per second
    var reduceMotion =
      window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var groupWidth = 0;
    var rafId = null;
    var lastTs = 0;
    var paused = false;
    var dragging = false;
    var dragStartX = 0;
    var dragStartScroll = 0;
    var resumeTimer = null;

    function escapeHtml(value) {
      return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function truncate(value, max) {
      var text = String(value == null ? '' : value).replace(/<[^>]*>/g, ' ').trim();
      return text.length > max ? text.slice(0, max).replace(/\s+\S*$/, '') + '…' : text;
    }

    function starsMarkup(rating) {
      var score = Math.max(0, Math.min(5, parseInt(rating, 10) || 0));
      var html = '';
      for (var i = 1; i <= 5; i++) {
        html += '<i class="bi ' + (i <= score ? 'bi-star-fill' : 'bi-star') + '"></i>';
      }
      return html;
    }

    function cardMarkup(item) {
      var name = escapeHtml(item.client_name || 'Klien');
      var role = [item.client_position, item.company_name]
        .filter(function (part) {
          return part;
        })
        .map(escapeHtml)
        .join(' · ');

      var avatar = item.client_photo
        ? '<img src="' +
          escapeHtml(item.client_photo) +
          '" alt="' +
          name +
          '" class="rs-quote__avatar" loading="lazy">'
        : '<div class="rs-quote__avatar">' +
          escapeHtml((item.client_name || 'K').charAt(0).toUpperCase()) +
          '</div>';

      return (
        '<div class="rs-marquee__card">' +
        '<div class="rs-quote">' +
        '<div class="rs-quote__stars">' +
        starsMarkup(item.rating) +
        '</div>' +
        '<p class="rs-quote__text">&ldquo;' +
        escapeHtml(truncate(item.message, 220)) +
        '&rdquo;</p>' +
        '<div class="rs-quote__author">' +
        avatar +
        '<div><strong>' +
        name +
        '</strong><span>' +
        role +
        '</span></div>' +
        '</div>' +
        '</div>' +
        '</div>'
      );
    }

    function buildGroup(items) {
      var group = document.createElement('div');
      group.className = 'rs-marquee__group';
      group.setAttribute('data-rs-marquee-group', '');
      group.innerHTML = items.map(cardMarkup).join('');
      return group;
    }

    function measure() {
      var group = track.querySelector('[data-rs-marquee-group]');
      groupWidth = group ? group.getBoundingClientRect().width : 0;
    }

    /**
     * Duplicate the group until the track is wide enough to scroll forever
     * without ever showing an empty gap.
     */
    function fillTrack() {
      var groups = track.querySelectorAll('[data-rs-marquee-group]');
      if (!groups.length) return;

      var base = groups[0];
      // Remove extra clones from a previous run.
      for (var i = groups.length - 1; i >= 1; i--) {
        groups[i].parentNode.removeChild(groups[i]);
      }

      measure();
      if (!groupWidth) return;

      // Keep at least two full groups of slack on both sides so the wrap
      // never exposes an empty gap.
      var needed = Math.max(3, Math.ceil(viewport.clientWidth / groupWidth) + 2);
      for (var n = 1; n < needed; n++) {
        var clone = base.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        track.appendChild(clone);
      }

      measure();
      // Start one group in so the marquee can travel to the right.
      viewport.scrollLeft = groupWidth;
    }

    function normalize() {
      if (!groupWidth) return;
      if (viewport.scrollLeft <= 0) {
        viewport.scrollLeft += groupWidth;
      } else if (viewport.scrollLeft >= groupWidth * 2) {
        viewport.scrollLeft -= groupWidth;
      }
    }

    function step(ts) {
      rafId = window.requestAnimationFrame(step);
      if (!lastTs) lastTs = ts;
      var delta = Math.min(ts - lastTs, 64);
      lastTs = ts;

      if (paused || dragging || !groupWidth) return;

      // Cards travel to the right, so the scroll position decreases. Wrap the
      // value before assigning it to avoid the browser clamping at 0.
      var next = viewport.scrollLeft - (speed * delta) / 1000;
      if (next <= 0) next += groupWidth;
      if (next >= groupWidth * 2) next -= groupWidth;
      viewport.scrollLeft = next;
    }

    function start() {
      if (reduceMotion || rafId) return;
      lastTs = 0;
      rafId = window.requestAnimationFrame(step);
    }

    function stop() {
      if (rafId) {
        window.cancelAnimationFrame(rafId);
        rafId = null;
      }
    }

    function pause() {
      paused = true;
    }

    function resume() {
      paused = false;
      lastTs = 0;
    }

    function resumeSoon() {
      window.clearTimeout(resumeTimer);
      resumeTimer = window.setTimeout(resume, 1200);
    }

    function slideBy(direction) {
      var card = track.querySelector('.rs-marquee__card');
      var distance = card ? card.getBoundingClientRect().width + 20 : 320;
      var from = viewport.scrollLeft;
      var to = from - direction * distance;

      // Wrap the target so the animation never stalls against a scroll edge.
      if (groupWidth) {
        if (to <= 0) {
          from += groupWidth;
          to += groupWidth;
        } else if (to >= groupWidth * 2) {
          from -= groupWidth;
          to -= groupWidth;
        }
      }

      var duration = 420;
      var startTs = null;

      pause();

      function animate(ts) {
        if (!startTs) startTs = ts;
        var progress = Math.min((ts - startTs) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        viewport.scrollLeft = from + (to - from) * eased;
        if (progress < 1) {
          window.requestAnimationFrame(animate);
        } else {
          normalize();
          resumeSoon();
        }
      }

      window.requestAnimationFrame(animate);
    }

    /* ---------------- manual dragging (mouse / pen) ---------------- */
    viewport.addEventListener('pointerdown', function (event) {
      // Touch devices use native momentum scrolling instead.
      if (event.pointerType === 'touch') return;
      if (event.pointerType === 'mouse' && event.button !== 0) return;
      dragging = true;
      dragStartX = event.clientX;
      dragStartScroll = viewport.scrollLeft;
      viewport.classList.add('is-dragging');
      if (viewport.setPointerCapture) {
        try {
          viewport.setPointerCapture(event.pointerId);
        } catch (e) {
          /* ignore */
        }
      }
    });

    viewport.addEventListener('pointermove', function (event) {
      if (!dragging) return;
      var delta = event.clientX - dragStartX;
      var next = dragStartScroll - delta;

      // Shift the drag origin when wrapping so the gesture stays continuous.
      if (groupWidth) {
        if (next <= 0) {
          dragStartScroll += groupWidth;
          next += groupWidth;
        } else if (next >= groupWidth * 2) {
          dragStartScroll -= groupWidth;
          next -= groupWidth;
        }
      }

      viewport.scrollLeft = next;
    });

    function endDrag() {
      if (!dragging) return;
      dragging = false;
      viewport.classList.remove('is-dragging');
      normalize();
      resumeSoon();
    }

    viewport.addEventListener('pointerup', endDrag);
    viewport.addEventListener('pointercancel', endDrag);

    /* ---------------- touch: pause while the user swipes ---------------- */
    viewport.addEventListener(
      'touchstart',
      function () {
        pause();
      },
      { passive: true }
    );
    viewport.addEventListener(
      'touchend',
      function () {
        normalize();
        resumeSoon();
      },
      { passive: true }
    );

    /* ---------------- focus / keyboard ---------------- */
    // The marquee keeps moving on hover (it must run continuously), but it
    // pauses while a control inside it has keyboard focus.
    root.addEventListener('focusin', pause);
    root.addEventListener('focusout', function () {
      if (!dragging) resume();
    });

    viewport.addEventListener('keydown', function (event) {
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        slideBy(-1);
      } else if (event.key === 'ArrowRight') {
        event.preventDefault();
        slideBy(1);
      }
    });

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        slideBy(-1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        slideBy(1);
      });
    }

    /* ---------------- data loading ---------------- */
    function section() {
      return root.closest('section') || root;
    }

    function render(items) {
      if (!items.length) {
        section().hidden = true;
        return;
      }

      track.innerHTML = '';
      track.appendChild(buildGroup(items));
      section().hidden = false;
      fillTrack();

      if (controls) controls.hidden = false;
      start();
    }

    function loadFromApi() {
      if (!endpoint || !window.fetch) return Promise.resolve(false);

      return fetch(endpoint, {
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      })
        .then(function (response) {
          if (!response.ok) throw new Error('HTTP ' + response.status);
          return response.json();
        })
        .then(function (payload) {
          var items = (payload && payload.data) || [];
          items = items.filter(function (item) {
            return item && item.is_published !== false;
          });
          if (!items.length) return false;
          render(items);
          return true;
        })
        .catch(function () {
          return false;
        });
    }

    // Server-rendered cards are already in place: start the animation right
    // away, then refresh the content from the API.
    var hasCards = !!track.querySelector('.rs-marquee__card');
    if (hasCards) {
      fillTrack();
      if (controls) controls.hidden = false;
      start();
    }

    loadFromApi().then(function (loaded) {
      if (!loaded && hasCards) {
        // Keep the server-rendered fallback running.
        fillTrack();
        start();
      }
    });

    var resizeTimer = null;
    window.addEventListener('resize', function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(function () {
        var wasPaused = paused;
        paused = true;
        fillTrack();
        paused = wasPaused;
      }, 180);
    });

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        stop();
      } else {
        start();
      }
    });
  }

  /* ------------------------------------------------------------------
     Portfolio gallery lightbox (zoom)
     ------------------------------------------------------------------ */
  function initGalleryLightbox() {
    var galleries = document.querySelectorAll('[data-rs-gallery]');
    var box = document.querySelector('[data-rs-lightbox]');
    if (!galleries.length || !box) return;

    var image = box.querySelector('[data-rs-lightbox-image]');
    var caption = box.querySelector('[data-rs-lightbox-caption]');
    var closeBtn = box.querySelector('[data-rs-lightbox-close]');
    var prevBtn = box.querySelector('[data-rs-lightbox-prev]');
    var nextBtn = box.querySelector('[data-rs-lightbox-next]');

    var items = [];
    var current = 0;
    var lastFocused = null;

    function show(index) {
      if (!items.length) return;
      current = (index + items.length) % items.length;
      var item = items[current];
      image.src = item.src;
      image.alt = item.alt;
      if (caption) {
        caption.textContent = items.length > 1 ? item.alt + ' · ' + (current + 1) + '/' + items.length : item.alt;
      }
      var multiple = items.length > 1;
      if (prevBtn) prevBtn.hidden = !multiple;
      if (nextBtn) nextBtn.hidden = !multiple;
    }

    function open(index) {
      lastFocused = document.activeElement;
      box.hidden = false;
      document.body.classList.add('rs-lightbox-open');
      show(index);
      if (closeBtn) closeBtn.focus();
    }

    function close() {
      box.hidden = true;
      document.body.classList.remove('rs-lightbox-open');
      image.removeAttribute('src');
      if (lastFocused && lastFocused.focus) lastFocused.focus();
    }

    galleries.forEach(function (gallery) {
      var buttons = Array.prototype.slice.call(gallery.querySelectorAll('[data-rs-gallery-item]'));

      buttons.forEach(function (button, index) {
        button.addEventListener('click', function () {
          items = buttons.map(function (btn) {
            return {
              src: btn.getAttribute('data-src'),
              alt: btn.getAttribute('data-alt') || '',
            };
          });
          open(index);
        });
      });
    });

    if (closeBtn) closeBtn.addEventListener('click', close);
    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        show(current - 1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        show(current + 1);
      });
    }

    box.addEventListener('click', function (event) {
      if (event.target === box) close();
    });

    document.addEventListener('keydown', function (event) {
      if (box.hidden) return;
      if (event.key === 'Escape') {
        close();
      } else if (event.key === 'ArrowLeft') {
        event.preventDefault();
        show(current - 1);
      } else if (event.key === 'ArrowRight') {
        event.preventDefault();
        show(current + 1);
      }
    });
  }

  /* ------------------------------------------------------------------
     Founder bio: expand / collapse
     ------------------------------------------------------------------ */
  function initFounderBio() {
    var toggles = document.querySelectorAll('[data-rs-founder-toggle]');
    if (!toggles.length) return;

    toggles.forEach(function (toggle) {
      var targetId = toggle.getAttribute('aria-controls');
      var panel = targetId ? document.getElementById(targetId) : null;
      if (!panel) return;

      var label = toggle.querySelector('span');
      var openLabel = toggle.getAttribute('data-label-open') || 'Baca selengkapnya';
      var closeLabel = toggle.getAttribute('data-label-close') || 'Tutup';

      toggle.addEventListener('click', function () {
        var open = panel.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (label) label.textContent = open ? closeLabel : openLabel;
      });
    });
  }

  /* ------------------------------------------------------------------
     Lazy image fade-in
     ------------------------------------------------------------------ */
  function initImageFade() {
    document.querySelectorAll('img[loading="lazy"]').forEach(function (img) {
      if (img.complete) {
        img.classList.add('is-loaded');
        return;
      }
      img.addEventListener('load', function () {
        img.classList.add('is-loaded');
      });
    });
  }

  /* ------------------------------------------------------------------
     Boot
     ------------------------------------------------------------------ */
  function boot() {
    initNavbar();
    initReveal();
    initCounters();
    initBackToTop();
    initFilters();
    initPortfolioToolbar();
    initFaqSearch();
    initInquiryForm();
    initTestimonialSlider();
    initGalleryLightbox();
    initFounderBio();
    initImageFade();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();

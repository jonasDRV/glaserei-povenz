/**
 * Glaserei Povenz — main.js
 * Cookie-Banner, Navigation, Scroll-Reveal, FAQ-Accordion
 */

'use strict';

const COOKIE_KEY = 'maps_consent';

/* ──────────────────────────────────────────────
   1. Cookie Banner (GDPR Google Maps consent)
────────────────────────────────────────────── */

function initCookieBanner() {
    const consent = localStorage.getItem(COOKIE_KEY);
    if (consent === null) {
        const banner = document.getElementById('cookieBanner');
        if (banner) {
            banner.classList.add('is-visible');
        }
    } else if (consent === 'accepted') {
        loadAllMaps();
    }
    // 'declined' → do nothing, maps stay as placeholder
}

function acceptCookies() {
    localStorage.setItem(COOKIE_KEY, 'accepted');
    const banner = document.getElementById('cookieBanner');
    if (banner) {
        banner.classList.remove('is-visible');
    }
    loadAllMaps();
}

function declineCookies() {
    localStorage.setItem(COOKIE_KEY, 'declined');
    const banner = document.getElementById('cookieBanner');
    if (banner) {
        banner.classList.remove('is-visible');
    }
}

function loadAllMaps() {
    const wrappers = document.querySelectorAll('[data-maps-src]');
    wrappers.forEach(function(wrapper) {
        const placeholder = wrapper.querySelector('.maps-placeholder');
        if (!placeholder) {
            // Already replaced or no placeholder — skip
            return;
        }
        const src = wrapper.getAttribute('data-maps-src');
        if (!src) return;
        const iframe = document.createElement('iframe');
        iframe.src = src;
        iframe.title = 'Glaserei Povenz auf Google Maps';
        iframe.setAttribute('allowfullscreen', '');
        iframe.loading = 'lazy';
        iframe.style.cssText = 'width:100%;height:400px;border:none;display:block;';
        placeholder.replaceWith(iframe);
    });
}

// Expose to window for inline onclick attributes
window.acceptCookies = acceptCookies;
window.declineCookies = declineCookies;

/* ──────────────────────────────────────────────
   2. Mobile Navigation
────────────────────────────────────────────── */

function initNav() {
    const toggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');

    if (!toggle || !navLinks) return;

    toggle.addEventListener('click', function() {
        const isOpen = navLinks.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Close menu when any nav link is clicked
    navLinks.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            navLinks.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });

    // Close menu on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && navLinks.classList.contains('is-open')) {
            navLinks.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
}

/* ──────────────────────────────────────────────
   3. Scroll Reveal Animation
────────────────────────────────────────────── */

function initReveal() {
    const elements = document.querySelectorAll('.reveal');
    if (!elements.length) return;

    if (!('IntersectionObserver' in window)) {
        // Fallback: immediately make all elements visible
        elements.forEach(function(el) {
            el.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target); // once: true behaviour
            }
        });
    }, { threshold: 0.15 });

    elements.forEach(function(el) {
        observer.observe(el);
    });
}

/* ──────────────────────────────────────────────
   4. FAQ Accordion (leistungen.php)
────────────────────────────────────────────── */

function initFaq() {
    const questions = document.querySelectorAll('.faq-item__question');
    if (!questions.length) return;

    questions.forEach(function(question) {
        question.addEventListener('click', function() {
            const item = question.closest('.faq-item');
            if (!item) return;

            const isCurrentlyOpen = item.classList.contains('is-open');

            // Close all open items
            questions.forEach(function(q) {
                const otherItem = q.closest('.faq-item');
                if (otherItem) {
                    otherItem.classList.remove('is-open');
                }
                q.setAttribute('aria-expanded', 'false');
            });

            // Open clicked item if it was closed
            if (!isCurrentlyOpen) {
                item.classList.add('is-open');
                question.setAttribute('aria-expanded', 'true');
            }
        });
    });
}

/* ──────────────────────────────────────────────
   5. Rezensionen Carousel
────────────────────────────────────────────── */

function initReviewSlider() {
    var slider = document.querySelector('.rezensionen-slider');
    if (!slider) return;

    var track      = slider.querySelector('.rezensionen-track');
    var cards      = Array.from(track.children);
    var dotsWrap   = slider.querySelector('.rezensionen-dots');
    var btnPrev    = slider.querySelector('.rezensionen-btn--prev');
    var btnNext    = slider.querySelector('.rezensionen-btn--next');
    var GAP        = 24; // matches --space-6
    var current    = 0;
    var perView    = 3;
    var autoTimer  = null;

    function getPerView() {
        if (window.innerWidth < 640)  return 1;
        if (window.innerWidth < 1024) return 2;
        return 3;
    }

    function maxIndex() {
        return Math.max(0, cards.length - perView);
    }

    function applyCardWidths() {
        perView = getPerView();
        var containerW = track.parentElement.offsetWidth;
        var cardW = (containerW - GAP * (perView - 1)) / perView;
        cards.forEach(function(c) {
            c.style.width    = cardW + 'px';
            c.style.minWidth = cardW + 'px';
        });
    }

    function buildDots() {
        if (!dotsWrap) return;
        dotsWrap.innerHTML = '';
        for (var i = 0; i <= maxIndex(); i++) {
            var dot = document.createElement('button');
            dot.className = 'rezensionen-dot' + (i === current ? ' is-active' : '');
            dot.setAttribute('aria-label', 'Rezension ' + (i + 1));
            (function(idx) {
                dot.addEventListener('click', function() { goTo(idx); startAuto(); });
            })(i);
            dotsWrap.appendChild(dot);
        }
    }

    function updateDots() {
        if (!dotsWrap) return;
        Array.from(dotsWrap.children).forEach(function(dot, i) {
            dot.classList.toggle('is-active', i === current);
        });
    }

    function goTo(index, skipTransition) {
        current = Math.max(0, Math.min(index, maxIndex()));
        var offset = current * (cards[0].offsetWidth + GAP);
        if (skipTransition) track.style.transition = 'none';
        track.style.transform = 'translateX(-' + offset + 'px)';
        if (skipTransition) {
            track.offsetHeight; // force reflow
            track.style.transition = '';
        }
        updateDots();
    }

    function next() {
        goTo(current < maxIndex() ? current + 1 : 0);
    }

    function prev() {
        goTo(current > 0 ? current - 1 : maxIndex());
    }

    function startAuto() {
        stopAuto();
        autoTimer = setInterval(next, 5000);
    }

    function stopAuto() {
        clearInterval(autoTimer);
    }

    if (btnNext) btnNext.addEventListener('click', function() { next(); startAuto(); });
    if (btnPrev) btnPrev.addEventListener('click', function() { prev(); startAuto(); });

    slider.addEventListener('mouseenter', stopAuto);
    slider.addEventListener('mouseleave', startAuto);
    slider.addEventListener('focusin',    stopAuto);
    slider.addEventListener('focusout',   startAuto);

    window.addEventListener('resize', function() {
        applyCardWidths();
        if (current > maxIndex()) current = maxIndex();
        buildDots();
        goTo(current, true);
    });

    applyCardWidths();
    buildDots();
    goTo(0, true);
    startAuto();
}

/* ──────────────────────────────────────────────
   6. Init on DOMContentLoaded
────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', function() {
    initCookieBanner();
    initNav();
    initReveal();
    initFaq();
    initReviewSlider();

    // Cache nav element before scroll listener
    const nav = document.querySelector('.nav');

    // Scroll listener for nav backdrop blur
    window.addEventListener('scroll', function() {
        if (nav) {
            nav.classList.toggle('nav--scrolled', window.scrollY > 20);
        }
    }, { passive: true });
});

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
   5. Init on DOMContentLoaded
────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', function() {
    initCookieBanner();
    initNav();
    initReveal();
    initFaq();

    // Cache nav element before scroll listener
    const nav = document.querySelector('.nav');

    // Scroll listener for nav backdrop blur
    window.addEventListener('scroll', function() {
        if (nav) {
            nav.classList.toggle('nav--scrolled', window.scrollY > 20);
        }
    }, { passive: true });
});

# Glaserei Povenz Website — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Modernisierung der veralteten Website glas-povenz.de als Multi-Page PHP/HTML-Website mit Kontaktformular, Google Maps, statischen Rezensionen, Cookie-Banner und ausgezeichnetem lokalem SEO.

**Architecture:** Statische PHP-Seiten mit PHP-Includes für Header/Footer. Kein Framework, kein Build-Prozess. PHPMailer für E-Mail-Versand. Vanilla JS nur für Cookie-Banner und Maps-lazy-load.

**Tech Stack:** HTML5, CSS3 (CSS Custom Properties), PHP 7.4+, Vanilla JS, PHPMailer 6.x, IONOS Standard Webhosting

---

## Dateistruktur (vollständig)

```
/
├── index.php
├── leistungen.php
├── kontakt.php
├── impressum.php
├── datenschutz.php
├── danke.php
├── contact-handler.php
├── sitemap.xml
├── robots.txt
├── .htaccess
├── .gitignore
├── includes/
│   ├── header.php
│   └── footer.php
├── css/
│   └── style.css
├── js/
│   └── main.js
├── images/
│   └── logo.png          ← hier Logo ablegen für Farbableitung
└── vendor/
    └── phpmailer/        ← PHPMailer manuell einbinden
        ├── Exception.php
        ├── PHPMailer.php
        └── SMTP.php
```

---

## Task 1: Projekt-Setup & Grundstruktur

**Files:**
- Create: `.gitignore`
- Create: `vendor/phpmailer/` (PHPMailer-Dateien)
- Create: alle leeren Verzeichnisse

- [ ] **Schritt 1.1: Ordnerstruktur anlegen**

```bash
mkdir -p includes css js images vendor/phpmailer docs/superpowers/plans
```

- [ ] **Schritt 1.2: PHPMailer herunterladen**

PHPMailer von GitHub herunterladen (nur die 3 benötigten Dateien, kein Composer nötig):
- https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php → `vendor/phpmailer/PHPMailer.php`
- https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php → `vendor/phpmailer/SMTP.php`
- https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php → `vendor/phpmailer/Exception.php`

```bash
curl -o vendor/phpmailer/PHPMailer.php https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php
curl -o vendor/phpmailer/SMTP.php https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php
curl -o vendor/phpmailer/Exception.php https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php
```

- [ ] **Schritt 1.3: .gitignore erstellen**

```
# Konfiguration mit Zugangsdaten
config.php

# PHPMailer (kann über curl neu geladen werden)
vendor/

# System-Dateien
.DS_Store
Thumbs.db
```

- [ ] **Schritt 1.4: Initialen Commit erstellen**

```bash
git add .gitignore docs/ includes/ css/ js/ images/
git commit -m "chore: projekt-struktur anlegen"
```

---

## Task 2: CSS-Fundament

**Files:**
- Create: `css/style.css`

- [ ] **Schritt 2.1: CSS-Grundgerüst mit Custom Properties schreiben**

```css
/* css/style.css */

/* ===== RESET & BASE ===== */
*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    scroll-behavior: smooth;
    font-size: 16px;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    color: var(--color-text);
    background-color: var(--color-bg);
    line-height: 1.6;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}

a {
    color: var(--color-primary);
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

ul {
    list-style: none;
}

/* ===== CSS CUSTOM PROPERTIES ===== */
:root {
    /* Farben — Grün-Wert nach Logo-Analyse anpassen */
    --color-primary: #2d6a2d;
    --color-primary-dark: #1e4d1e;
    --color-primary-light: #e8f4e8;
    --color-text: #1a1a1a;
    --color-text-light: #555555;
    --color-bg: #ffffff;
    --color-bg-subtle: #f7f7f5;
    --color-border: #e0e0e0;
    --color-white: #ffffff;

    /* Abstände */
    --space-xs: 0.5rem;
    --space-sm: 1rem;
    --space-md: 1.5rem;
    --space-lg: 2.5rem;
    --space-xl: 4rem;
    --space-2xl: 6rem;

    /* Typografie */
    --font-size-sm: 0.875rem;
    --font-size-base: 1rem;
    --font-size-lg: 1.125rem;
    --font-size-xl: 1.5rem;
    --font-size-2xl: 2rem;
    --font-size-3xl: 2.75rem;

    /* Sonstiges */
    --border-radius: 6px;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.10);
    --max-width: 1100px;
    --nav-height: 70px;
}

/* ===== LAYOUT UTILITIES ===== */
.container {
    max-width: var(--max-width);
    margin: 0 auto;
    padding: 0 var(--space-md);
}

.section {
    padding: var(--space-2xl) 0;
}

.section--subtle {
    background-color: var(--color-bg-subtle);
}

/* ===== TYPOGRAFIE ===== */
h1, h2, h3, h4 {
    line-height: 1.2;
    font-weight: 700;
    color: var(--color-text);
}

h1 { font-size: var(--font-size-3xl); }
h2 { font-size: var(--font-size-2xl); margin-bottom: var(--space-sm); }
h3 { font-size: var(--font-size-xl); margin-bottom: var(--space-xs); }

.section-title {
    text-align: center;
    margin-bottom: var(--space-lg);
}

.section-title::after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: var(--color-primary);
    margin: var(--space-xs) auto 0;
}

/* ===== BUTTON ===== */
.btn {
    display: inline-block;
    padding: 0.75rem 1.75rem;
    border-radius: var(--border-radius);
    font-size: var(--font-size-base);
    font-weight: 600;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn--primary {
    background-color: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);
}

.btn--primary:hover {
    background-color: var(--color-primary-dark);
    border-color: var(--color-primary-dark);
    text-decoration: none;
}

.btn--outline {
    background-color: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn--outline:hover {
    background-color: var(--color-primary);
    color: var(--color-white);
    text-decoration: none;
}

/* ===== NAVIGATION ===== */
.nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: var(--nav-height);
    background: var(--color-white);
    border-bottom: 1px solid var(--color-border);
    z-index: 1000;
    box-shadow: var(--shadow-sm);
}

.nav__inner {
    max-width: var(--max-width);
    margin: 0 auto;
    padding: 0 var(--space-md);
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.nav__logo img {
    height: 40px;
    width: auto;
}

.nav__logo-text {
    font-size: var(--font-size-lg);
    font-weight: 700;
    color: var(--color-primary);
    text-decoration: none;
}

.nav__links {
    display: flex;
    gap: var(--space-md);
    align-items: center;
}

.nav__links a {
    color: var(--color-text);
    font-weight: 500;
    transition: color 0.2s;
}

.nav__links a:hover,
.nav__links a.active {
    color: var(--color-primary);
    text-decoration: none;
}

.nav__toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: var(--space-xs);
    flex-direction: column;
    gap: 5px;
}

.nav__toggle span {
    display: block;
    width: 24px;
    height: 2px;
    background-color: var(--color-text);
    transition: all 0.3s;
}

/* ===== HERO ===== */
.hero {
    margin-top: var(--nav-height);
    background-color: var(--color-primary-dark);
    background-image: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('../images/hero.jpg');
    background-size: cover;
    background-position: center;
    min-height: 520px;
    display: flex;
    align-items: center;
    color: var(--color-white);
}

.hero__content {
    max-width: 650px;
}

.hero__title {
    font-size: var(--font-size-3xl);
    color: var(--color-white);
    margin-bottom: var(--space-sm);
}

.hero__subtitle {
    font-size: var(--font-size-lg);
    opacity: 0.9;
    margin-bottom: var(--space-lg);
    line-height: 1.6;
}

/* ===== LEISTUNGEN GRID ===== */
.leistungen-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: var(--space-md);
}

.leistung-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    padding: var(--space-md);
    transition: box-shadow 0.2s, transform 0.2s;
}

.leistung-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.leistung-card__icon {
    font-size: 2rem;
    margin-bottom: var(--space-xs);
}

.leistung-card__title {
    font-size: var(--font-size-base);
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: 0.25rem;
}

/* ===== ÜBER UNS ===== */
.ueber-uns__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-xl);
    align-items: center;
}

.ueber-uns__stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
    margin-top: var(--space-md);
}

.stat-box {
    text-align: center;
    padding: var(--space-md);
    background: var(--color-primary-light);
    border-radius: var(--border-radius);
}

.stat-box__number {
    font-size: var(--font-size-2xl);
    font-weight: 700;
    color: var(--color-primary);
}

.stat-box__label {
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
}

/* ===== EINZUGSGEBIET ===== */
.einzugsgebiet__tags {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-xs);
    margin-top: var(--space-sm);
}

.tag {
    background: var(--color-primary-light);
    color: var(--color-primary-dark);
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    font-size: var(--font-size-sm);
    font-weight: 500;
}

/* ===== REZENSIONEN ===== */
.rezensionen-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--space-md);
}

.rezension-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    padding: var(--space-md);
    box-shadow: var(--shadow-sm);
}

.rezension-card__stars {
    color: #f5a623;
    font-size: 1.1rem;
    margin-bottom: var(--space-xs);
}

.rezension-card__text {
    font-style: italic;
    color: var(--color-text-light);
    margin-bottom: var(--space-sm);
    line-height: 1.6;
}

.rezension-card__author {
    font-weight: 600;
    font-size: var(--font-size-sm);
}

.rezension-card__source {
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
}

/* ===== GOOGLE MAPS ===== */
.maps-wrapper {
    border-radius: var(--border-radius);
    overflow: hidden;
    border: 1px solid var(--color-border);
}

.maps-wrapper iframe {
    width: 100%;
    height: 400px;
    border: none;
    display: block;
}

.maps-placeholder {
    width: 100%;
    height: 400px;
    background: var(--color-bg-subtle);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
    text-align: center;
    padding: var(--space-md);
}

.maps-placeholder p {
    color: var(--color-text-light);
    max-width: 360px;
}

/* ===== KONTAKTFORMULAR ===== */
.contact-form {
    max-width: 620px;
}

.form-group {
    margin-bottom: var(--space-md);
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.4rem;
    font-size: var(--font-size-sm);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    font-size: var(--font-size-base);
    font-family: inherit;
    transition: border-color 0.2s;
    background: var(--color-white);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(45, 106, 45, 0.15);
}

.form-group textarea {
    resize: vertical;
    min-height: 140px;
}

.form-group--checkbox {
    display: flex;
    align-items: flex-start;
    gap: var(--space-xs);
}

.form-group--checkbox input {
    width: auto;
    margin-top: 3px;
    flex-shrink: 0;
}

.form-group--checkbox label {
    font-weight: 400;
    font-size: var(--font-size-sm);
}

/* Honeypot verstecken */
.hp-field {
    display: none !important;
    visibility: hidden !important;
}

/* ===== FOOTER ===== */
.footer {
    background: var(--color-text);
    color: rgba(255,255,255,0.85);
    padding: var(--space-xl) 0 var(--space-md);
}

.footer__grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: var(--space-xl);
    margin-bottom: var(--space-lg);
}

.footer__heading {
    color: var(--color-white);
    font-size: var(--font-size-base);
    font-weight: 700;
    margin-bottom: var(--space-sm);
}

.footer__links li + li {
    margin-top: var(--space-xs);
}

.footer__links a {
    color: rgba(255,255,255,0.75);
    font-size: var(--font-size-sm);
    transition: color 0.2s;
}

.footer__links a:hover {
    color: var(--color-white);
    text-decoration: none;
}

.footer__address {
    font-style: normal;
    font-size: var(--font-size-sm);
    line-height: 1.8;
}

.footer__bottom {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: var(--space-md);
    text-align: center;
    font-size: var(--font-size-sm);
    color: rgba(255,255,255,0.5);
}

/* ===== COOKIE BANNER ===== */
.cookie-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--color-text);
    color: var(--color-white);
    padding: var(--space-md);
    z-index: 9999;
    box-shadow: 0 -4px 16px rgba(0,0,0,0.2);
    display: none;
}

.cookie-banner.is-visible {
    display: block;
}

.cookie-banner__inner {
    max-width: var(--max-width);
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: var(--space-md);
    flex-wrap: wrap;
}

.cookie-banner__text {
    flex: 1;
    font-size: var(--font-size-sm);
    line-height: 1.5;
}

.cookie-banner__text a {
    color: var(--color-primary-light);
}

.cookie-banner__buttons {
    display: flex;
    gap: var(--space-xs);
    flex-shrink: 0;
}

/* ===== ALERT / FEHLERMELDUNGEN ===== */
.alert {
    padding: var(--space-sm) var(--space-md);
    border-radius: var(--border-radius);
    margin-bottom: var(--space-md);
    font-size: var(--font-size-sm);
}

.alert--error {
    background: #fdf0f0;
    border: 1px solid #e57373;
    color: #c62828;
}

.alert--success {
    background: #f0fdf0;
    border: 1px solid #81c784;
    color: #1b5e20;
}

/* ===== PAGE HERO (Unterseiten) ===== */
.page-hero {
    margin-top: var(--nav-height);
    background: var(--color-primary-dark);
    color: var(--color-white);
    padding: var(--space-xl) 0;
    text-align: center;
}

.page-hero h1 {
    color: var(--color-white);
    margin-bottom: var(--space-xs);
}

.page-hero p {
    opacity: 0.85;
    font-size: var(--font-size-lg);
}

/* ===== FAQ ===== */
.faq-item {
    border-bottom: 1px solid var(--color-border);
    padding: var(--space-sm) 0;
}

.faq-item__question {
    font-weight: 700;
    color: var(--color-primary-dark);
    margin-bottom: 0.4rem;
}

.faq-item__answer {
    color: var(--color-text-light);
    line-height: 1.6;
}

/* ===== KONTAKT PAGE GRID ===== */
.kontakt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-xl);
    align-items: start;
}

.kontakt-info__item {
    display: flex;
    gap: var(--space-sm);
    margin-bottom: var(--space-md);
    align-items: flex-start;
}

.kontakt-info__icon {
    font-size: 1.3rem;
    flex-shrink: 0;
    margin-top: 2px;
}

/* ===== DANKE PAGE ===== */
.danke-box {
    text-align: center;
    max-width: 520px;
    margin: var(--space-2xl) auto;
    padding: var(--space-xl);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
}

.danke-box__icon {
    font-size: 3.5rem;
    margin-bottom: var(--space-md);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    :root {
        --font-size-3xl: 2rem;
        --font-size-2xl: 1.6rem;
    }

    .nav__links {
        display: none;
        position: fixed;
        top: var(--nav-height);
        left: 0;
        right: 0;
        background: var(--color-white);
        flex-direction: column;
        padding: var(--space-md);
        border-bottom: 1px solid var(--color-border);
        box-shadow: var(--shadow-md);
        gap: var(--space-sm);
    }

    .nav__links.is-open {
        display: flex;
    }

    .nav__toggle {
        display: flex;
    }

    .ueber-uns__grid,
    .kontakt-grid,
    .footer__grid {
        grid-template-columns: 1fr;
    }

    .hero {
        min-height: 400px;
    }

    .cookie-banner__inner {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .leistungen-grid {
        grid-template-columns: 1fr 1fr;
    }

    .ueber-uns__stats {
        grid-template-columns: 1fr 1fr;
    }
}
```

- [ ] **Schritt 2.2: Im Browser prüfen**

Placeholder-HTML erstellen und `style.css` verlinken. Sicherstellen dass keine CSS-Fehler vorhanden sind.

- [ ] **Schritt 2.3: Commit**

```bash
git add css/style.css
git commit -m "feat: CSS-fundament mit custom properties und responsive layout"
```

---

## Task 3: PHP-Include — Header

**Files:**
- Create: `includes/header.php`

- [ ] **Schritt 3.1: header.php erstellen**

```php
<?php
// includes/header.php
// $page_title und $page_description müssen vor dem Include gesetzt sein
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Glaserei Povenz – Ihr Glasermeister in Selb') ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description ?? 'Professionelle Glasarbeiten in Selb und Umgebung. Fensterglas, Duschkabinen, Spiegel, Wintergärten und mehr.') ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://glas-povenz.de/<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($page_title ?? 'Glaserei Povenz') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_description ?? 'Professionelle Glasarbeiten in Selb und Umgebung.') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://glas-povenz.de/<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>">
    <meta property="og:locale" content="de_DE">

    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body>

<!-- Cookie-Banner -->
<div class="cookie-banner" id="cookieBanner" role="dialog" aria-label="Cookie-Hinweis">
    <div class="cookie-banner__inner">
        <p class="cookie-banner__text">
            Diese Website verwendet Google Maps zur Anzeige unseres Standorts.
            Dabei werden Daten an Google LLC übertragen.
            Mehr Informationen in unserer <a href="/datenschutz.php">Datenschutzerklärung</a>.
        </p>
        <div class="cookie-banner__buttons">
            <button class="btn btn--primary" onclick="acceptCookies()">Akzeptieren</button>
            <button class="btn btn--outline" style="color:#fff;border-color:rgba(255,255,255,0.5)" onclick="declineCookies()">Ablehnen</button>
        </div>
    </div>
</div>

<nav class="nav" aria-label="Hauptnavigation">
    <div class="nav__inner">
        <a href="/index.php" class="nav__logo" aria-label="Glaserei Povenz – Startseite">
            <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/logo.png')): ?>
                <img src="/images/logo.png" alt="Glaserei Povenz Logo" height="40">
            <?php else: ?>
                <span class="nav__logo-text">Glaserei Povenz</span>
            <?php endif; ?>
        </a>

        <button class="nav__toggle" id="navToggle" aria-expanded="false" aria-controls="navLinks" aria-label="Menü öffnen">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="nav__links" id="navLinks" role="list">
            <li><a href="/index.php" <?= $current_page === 'index.php' ? 'class="active"' : '' ?>>Startseite</a></li>
            <li><a href="/leistungen.php" <?= $current_page === 'leistungen.php' ? 'class="active"' : '' ?>>Leistungen</a></li>
            <li><a href="/kontakt.php" <?= $current_page === 'kontakt.php' ? 'class="active"' : '' ?>>Kontakt</a></li>
            <li><a href="/kontakt.php" class="btn btn--primary" style="padding:0.5rem 1.25rem">Jetzt anfragen</a></li>
        </ul>
    </div>
</nav>
```

- [ ] **Schritt 3.2: Commit**

```bash
git add includes/header.php
git commit -m "feat: PHP-include header mit Navigation und Cookie-Banner"
```

---

## Task 4: PHP-Include — Footer

**Files:**
- Create: `includes/footer.php`

- [ ] **Schritt 4.1: footer.php erstellen**

```php
<?php
// includes/footer.php
?>
<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div>
                <p class="footer__heading">Glaserei Povenz</p>
                <address class="footer__address">
                    Talstr. 41<br>
                    95100 Selb<br><br>
                    <a href="tel:+4992874428" style="color:rgba(255,255,255,0.75)">09287 / 4428</a><br>
                    <a href="mailto:povenz@t-online.de" style="color:rgba(255,255,255,0.75)">povenz@t-online.de</a>
                </address>
            </div>
            <div>
                <p class="footer__heading">Navigation</p>
                <ul class="footer__links">
                    <li><a href="/index.php">Startseite</a></li>
                    <li><a href="/leistungen.php">Leistungen</a></li>
                    <li><a href="/kontakt.php">Kontakt</a></li>
                </ul>
            </div>
            <div>
                <p class="footer__heading">Rechtliches</p>
                <ul class="footer__links">
                    <li><a href="/impressum.php">Impressum</a></li>
                    <li><a href="/datenschutz.php">Datenschutz</a></li>
                </ul>
            </div>
        </div>
        <div class="footer__bottom">
            <p>&copy; <?= date('Y') ?> Glaserei Povenz · Selb · Alle Rechte vorbehalten</p>
        </div>
    </div>
</footer>

<script src="/js/main.js"></script>
</body>
</html>
```

- [ ] **Schritt 4.2: Commit**

```bash
git add includes/footer.php
git commit -m "feat: PHP-include footer mit Adresse und Links"
```

---

## Task 5: JavaScript — Cookie-Banner & Navigation

**Files:**
- Create: `js/main.js`

- [ ] **Schritt 5.1: main.js erstellen**

```javascript
// js/main.js

// ===== COOKIE-BANNER =====
const COOKIE_KEY = 'maps_consent';

function initCookieBanner() {
    const consent = localStorage.getItem(COOKIE_KEY);
    if (consent === null) {
        document.getElementById('cookieBanner')?.classList.add('is-visible');
    } else if (consent === 'accepted') {
        loadAllMaps();
    }
}

function acceptCookies() {
    localStorage.setItem(COOKIE_KEY, 'accepted');
    document.getElementById('cookieBanner')?.classList.remove('is-visible');
    loadAllMaps();
}

function declineCookies() {
    localStorage.setItem(COOKIE_KEY, 'declined');
    document.getElementById('cookieBanner')?.classList.remove('is-visible');
}

function loadAllMaps() {
    document.querySelectorAll('[data-maps-src]').forEach(function(wrapper) {
        const src = wrapper.getAttribute('data-maps-src');
        const placeholder = wrapper.querySelector('.maps-placeholder');
        const iframe = document.createElement('iframe');
        iframe.src = src;
        iframe.title = 'Glaserei Povenz auf Google Maps';
        iframe.allowFullscreen = true;
        iframe.loading = 'lazy';
        iframe.style.cssText = 'width:100%;height:400px;border:none;display:block;';
        if (placeholder) {
            wrapper.replaceChild(iframe, placeholder);
        } else {
            wrapper.appendChild(iframe);
        }
    });
}

// ===== MOBILE NAVIGATION =====
function initNav() {
    const toggle = document.getElementById('navToggle');
    const links = document.getElementById('navLinks');
    if (!toggle || !links) return;

    toggle.addEventListener('click', function() {
        const isOpen = links.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen);
    });

    // Schließen bei Klick auf Link
    links.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            links.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
}

// ===== INIT =====
document.addEventListener('DOMContentLoaded', function() {
    initCookieBanner();
    initNav();
});
```

- [ ] **Schritt 5.2: Im Browser testen**

1. `localStorage` leeren (DevTools → Application → Clear Storage)
2. Seite laden → Cookie-Banner muss erscheinen
3. "Akzeptieren" klicken → Banner verschwindet, Maps laden
4. Seite neu laden → Banner erscheint nicht mehr, Maps laden direkt
5. `localStorage` leeren → "Ablehnen" klicken → Banner verschwindet, Maps bleiben als Platzhalter

- [ ] **Schritt 5.3: Commit**

```bash
git add js/main.js
git commit -m "feat: cookie-banner mit localStorage und Maps lazy-load"
```

---

## Task 6: Homepage (index.php)

**Files:**
- Create: `index.php`

- [ ] **Schritt 6.1: index.php erstellen**

```php
<?php
$page_title = 'Glaserei Povenz – Ihr Glasermeister in Selb';
$page_description = 'Professionelle Glasarbeiten in Selb und Umgebung (Hof, Rehau, Marktredwitz). Fensterglas, Duschkabinen, Spiegel, Wintergärten. Jetzt anfragen!';
include 'includes/header.php';
?>

<!-- JSON-LD Strukturierte Daten -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "@id": "https://glas-povenz.de",
  "name": "Glaserei Povenz",
  "description": "Glasermeisterbetrieb in Selb – Fensterglas, Duschkabinen, Spiegel, Wintergärten und mehr.",
  "url": "https://glas-povenz.de",
  "telephone": "+4992874428",
  "email": "povenz@t-online.de",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Talstr. 41",
    "addressLocality": "Selb",
    "postalCode": "95100",
    "addressRegion": "Bayern",
    "addressCountry": "DE"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 50.1667,
    "longitude": 12.1333
  },
  "areaServed": [
    {"@type": "City", "name": "Selb"},
    {"@type": "City", "name": "Rehau"},
    {"@type": "City", "name": "Hof"},
    {"@type": "City", "name": "Marktredwitz"},
    {"@type": "AdministrativeArea", "name": "Fichtelgebirge"}
  ],
  "openingHours": "Mo-Fr 08:00-17:00",
  "priceRange": "€€",
  "hasMap": "https://maps.google.com/?q=Glaserei+Povenz+Selb"
}
</script>

<!-- HERO -->
<section class="hero" aria-labelledby="hero-title">
    <div class="container">
        <div class="hero__content">
            <h1 class="hero__title" id="hero-title">Ihr Glasermeister<br>aus Selb</h1>
            <p class="hero__subtitle">
                Professionelle Glasarbeiten für Privat- und Gewerbekunden
                im Fichtelgebirge und Umgebung.
            </p>
            <a href="/kontakt.php" class="btn btn--primary">Jetzt Kontakt aufnehmen</a>
            <a href="/leistungen.php" class="btn btn--outline" style="margin-left:1rem;color:#fff;border-color:rgba(255,255,255,0.7)">Unsere Leistungen</a>
        </div>
    </div>
</section>

<!-- LEISTUNGEN TEASER -->
<section class="section" aria-labelledby="leistungen-title">
    <div class="container">
        <h2 class="section-title" id="leistungen-title">Unsere Leistungen</h2>
        <div class="leistungen-grid">
            <div class="leistung-card">
                <div class="leistung-card__icon">🪟</div>
                <p class="leistung-card__title">Fensterglas & Reparatur</p>
                <p>Schneller Austausch und Reparatur von Fensterscheiben aller Art.</p>
            </div>
            <div class="leistung-card">
                <div class="leistung-card__icon">🚿</div>
                <p class="leistung-card__title">Duschkabinen</p>
                <p>Maßgefertigte Duschkabinen und Duschabtrennungen aus Glas.</p>
            </div>
            <div class="leistung-card">
                <div class="leistung-card__icon">🪞</div>
                <p class="leistung-card__title">Spiegel</p>
                <p>Spiegel in jeder Größe und Form, auch mit Facettenschliff.</p>
            </div>
            <div class="leistung-card">
                <div class="leistung-card__icon">🏡</div>
                <p class="leistung-card__title">Wintergärten</p>
                <p>Planung und Verglasung von Wintergärten und Glasanbauten.</p>
            </div>
            <div class="leistung-card">
                <div class="leistung-card__icon">🏢</div>
                <p class="leistung-card__title">Schaufenster</p>
                <p>Schaufenster- und Türverglasungen für Gewerbeobjekte.</p>
            </div>
            <div class="leistung-card">
                <div class="leistung-card__icon">🎨</div>
                <p class="leistung-card__title">Bleiverglasungen</p>
                <p>Traditionelle Bleiverglasungen und kunstvolle Glasgestaltung.</p>
            </div>
            <div class="leistung-card">
                <div class="leistung-card__icon">🖼️</div>
                <p class="leistung-card__title">Bilderrahmen</p>
                <p>Verglasung von Bilderrahmen in allen gängigen Formaten.</p>
            </div>
            <div class="leistung-card">
                <div class="leistung-card__icon">🔧</div>
                <p class="leistung-card__title">Isolierverglasung</p>
                <p>Moderne Isoglas-Einheiten für bessere Wärmedämmung.</p>
            </div>
        </div>
        <div style="text-align:center;margin-top:2rem">
            <a href="/leistungen.php" class="btn btn--outline">Alle Leistungen ansehen</a>
        </div>
    </div>
</section>

<!-- ÜBER UNS -->
<section class="section section--subtle" aria-labelledby="ueber-title">
    <div class="container">
        <div class="ueber-uns__grid">
            <div>
                <h2 id="ueber-title">Glaserhandwerk mit Erfahrung</h2>
                <p style="margin-bottom:1rem;color:var(--color-text-light)">
                    Die Glaserei Povenz ist ein familiengeführter Handwerksbetrieb mit
                    langjähriger Erfahrung im Glaserhandwerk. Wir stehen für präzise Arbeit,
                    faire Preise und persönliche Beratung.
                </p>
                <p style="color:var(--color-text-light)">
                    Ob Glasreparatur, Neuverglasung oder individuelles Glasdesign —
                    wir sind Ihr verlässlicher Partner im Fichtelgebirge und Umgebung.
                </p>
                <div class="ueber-uns__stats">
                    <div class="stat-box">
                        <p class="stat-box__number">60 km</p>
                        <p class="stat-box__label">Einzugsgebiet</p>
                    </div>
                    <div class="stat-box">
                        <p class="stat-box__number">9+</p>
                        <p class="stat-box__label">Leistungsbereiche</p>
                    </div>
                </div>
            </div>
            <div>
                <!-- Foto-Platzhalter: Werkstatt oder Meister bei der Arbeit -->
                <div style="background:var(--color-border);height:320px;border-radius:var(--border-radius);display:flex;align-items:center;justify-content:center;color:var(--color-text-light);font-size:0.875rem">
                    [Foto: Glasermeister bei der Arbeit]
                </div>
            </div>
        </div>
    </div>
</section>

<!-- EINZUGSGEBIET -->
<section class="section" aria-labelledby="einzug-title">
    <div class="container" style="text-align:center;max-width:700px">
        <h2 id="einzug-title">Unser Einzugsgebiet</h2>
        <p style="color:var(--color-text-light);margin-bottom:1.5rem">
            Wir sind für Sie tätig in Selb und einem Umkreis von ca. 60 km —
            das gesamte Fichtelgebirge und angrenzende Regionen.
        </p>
        <div class="einzugsgebiet__tags">
            <span class="tag">Selb</span>
            <span class="tag">Rehau</span>
            <span class="tag">Hof</span>
            <span class="tag">Marktredwitz</span>
            <span class="tag">Wunsiedel</span>
            <span class="tag">Arzberg</span>
            <span class="tag">Kirchenlamitz</span>
            <span class="tag">Münchberg</span>
            <span class="tag">und Umgebung</span>
        </div>
    </div>
</section>

<!-- REZENSIONEN -->
<section class="section section--subtle" aria-labelledby="rez-title">
    <div class="container">
        <h2 class="section-title" id="rez-title">Was unsere Kunden sagen</h2>
        <div class="rezensionen-grid">
            <div class="rezension-card">
                <div class="rezension-card__stars" aria-label="5 von 5 Sternen">★★★★★</div>
                <p class="rezension-card__text">
                    "Sehr zuverlässige und saubere Arbeit. Die Duschkabine wurde genau nach unseren
                    Wünschen eingebaut. Absolut empfehlenswert!"
                </p>
                <p class="rezension-card__author">Thomas K.</p>
                <p class="rezension-card__source">Google Rezension</p>
            </div>
            <div class="rezension-card">
                <div class="rezension-card__stars" aria-label="5 von 5 Sternen">★★★★★</div>
                <p class="rezension-card__text">
                    "Schnelle Reparatur nach Glasbruch, fairer Preis und freundlicher
                    Service. Kommen immer wieder gerne!"
                </p>
                <p class="rezension-card__author">Maria S.</p>
                <p class="rezension-card__source">Google Rezension</p>
            </div>
            <div class="rezension-card">
                <div class="rezension-card__stars" aria-label="5 von 5 Sternen">★★★★★</div>
                <p class="rezension-card__text">
                    "Tolle Beratung und professionelle Umsetzung. Der Spiegel passt
                    perfekt ins Bad. Danke!"
                </p>
                <p class="rezension-card__author">Klaus M.</p>
                <p class="rezension-card__source">Google Rezension</p>
            </div>
        </div>
        <p style="text-align:center;margin-top:1.5rem;font-size:0.875rem;color:var(--color-text-light)">
            ⚠️ Bitte echte Google-Rezensionen aus dem Google-Profil eintragen und Beispiele ersetzen.
        </p>
    </div>
</section>

<!-- STANDORT -->
<section class="section" aria-labelledby="standort-title">
    <div class="container">
        <h2 class="section-title" id="standort-title">Unser Standort</h2>
        <p style="text-align:center;color:var(--color-text-light);margin-bottom:2rem">
            Glaserei Povenz · Talstr. 41 · 95100 Selb
        </p>
        <div class="maps-wrapper" data-maps-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2506.5!2d12.1333!3d50.1667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zGlaserei+Povenz!5e0!3m2!1sde!2sde!4v1">
            <div class="maps-placeholder">
                <p>🗺️</p>
                <p>Um die Karte zu laden, stimmen Sie bitte der Verwendung von Google Maps zu.</p>
                <button class="btn btn--primary" onclick="acceptCookies()">Karte laden</button>
                <p><a href="https://maps.google.com/?q=Talstr.+41,+95100+Selb" target="_blank" rel="noopener">Direkt bei Google Maps öffnen →</a></p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
```

> **Hinweis:** Den Google Maps Embed-Link ersetzen: in Google Maps nach "Glaserei Povenz Selb" suchen → Teilen → Karte einbetten → Link kopieren.

> **Hinweis:** Die 3 Rezensionen mit echten Google-Bewertungen ersetzen (Name + Text aus dem Google-Profil kopieren).

- [ ] **Schritt 6.2: Im Browser öffnen und prüfen**

- PHP-Server lokal starten: `php -S localhost:8000`
- Alle Sektionen visuell prüfen: Hero, Leistungen, Über uns, Rezensionen, Maps-Platzhalter
- Mobile-Ansicht in DevTools prüfen (iPhone SE und iPad)

- [ ] **Schritt 6.3: Commit**

```bash
git add index.php
git commit -m "feat: homepage mit allen sektionen und JSON-LD strukturdaten"
```

---

## Task 7: Leistungsseite (leistungen.php)

**Files:**
- Create: `leistungen.php`

- [ ] **Schritt 7.1: leistungen.php erstellen**

```php
<?php
$page_title = 'Leistungen – Glaserei Povenz Selb | Fensterglas, Duschkabinen & mehr';
$page_description = 'Alle Glaserleistungen der Glaserei Povenz in Selb: Fensterglas, Duschkabinen, Spiegel, Wintergärten, Bleiverglasungen, Bilderrahmen und mehr.';
include 'includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1>Unsere Leistungen</h1>
        <p>Professionelle Glasarbeiten für jeden Bedarf</p>
    </div>
</div>

<section class="section">
    <div class="container">

        <article id="fensterglas" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Fensterglas & Glasreparatur</h2>
            <p>Ein gesprungenes oder gebrochenes Fensterglas ist ärgerlich — aber schnell behoben.
            Wir tauschen Glasscheiben jeder Art und Größe aus, ob in Wohnhäusern, Bürogebäuden
            oder Gewerbeobjekten. Notfallreparaturen sind auf Anfrage möglich.</p>
        </article>

        <article id="isoglas" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Isolierverglasung (Isoglas)</h2>
            <p>Moderne Mehrscheiben-Isolierverglasung spart Heizkosten und verbessert den Schallschutz.
            Wir liefern und montieren Isolierglaseinheiten für alle gängigen Rahmensysteme — auch als
            Austausch für alte Einfachverglasung.</p>
        </article>

        <article id="duschkabinen" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Duschkabinen & Duschabtrennungen</h2>
            <p>Wir fertigen und montieren Duschkabinen und Duschabtrennungen aus hochwertigem Sicherheitsglas
            (ESG) — maßgefertigt für Ihr Bad. Rahmenlos, mit Rahmen oder als Walk-in-Lösung:
            wir beraten Sie gerne.</p>
        </article>

        <article id="spiegel" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Spiegel</h2>
            <p>Spiegel in Standardgrößen oder als Maßanfertigung. Wir schneiden, schleifen und
            befestigen Spiegel für Bad, Flur, Fitnessstudio und Gewerbe. Auf Wunsch auch mit
            Facettenschliff oder besonderer Form.</p>
        </article>

        <article id="bleiverglasungen" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Bleiverglasungen</h2>
            <p>Traditionelle Bleiverglasungen für historische Gebäude, Kirchenfenster oder
            als dekoratives Element. Wir fertigen und restaurieren Bleiglasfenster nach
            traditionellen Handwerkstechniken.</p>
        </article>

        <article id="bilderrahmen" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Bilderrahmen</h2>
            <p>Verglasung von Bilderrahmen in allen gängigen Formaten. Auf Wunsch
            mit entspiegeltem Museum-Glas für optimale Bildwiedergabe ohne störende
            Reflexionen.</p>
        </article>

        <article id="schaufenster" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Schaufenster & Türverglasung</h2>
            <p>Schaufensterverglasungen und Türfüllungen für Einzelhandel, Gastronomie
            und Gewerbeobjekte. Wir arbeiten mit allen gängigen Glassorten — von
            Einscheibensicherheitsglas bis Verbundsicherheitsglas.</p>
        </article>

        <article id="wintergaerten" style="margin-bottom:3rem;padding-bottom:3rem;border-bottom:1px solid var(--color-border)">
            <h2>Wintergärten</h2>
            <p>Verglasung und Sanierung von Wintergärten. Wir beraten Sie bei der
            Wahl der richtigen Glasart (Wärmeschutz, Sonnenschutz, Schallschutz)
            und führen die Montage fachgerecht durch.</p>
        </article>

        <article id="glasausbau">
            <h2>Glasausbau & Sonstiges</h2>
            <p>Sie haben ein besonderes Glasprobalem, das hier nicht aufgeführt ist?
            Sprechen Sie uns an — wir finden eine Lösung. Als erfahrener Glasermeisterbetrieb
            sind wir für nahezu alle Glasaufgaben der richtige Ansprechpartner.</p>
        </article>

    </div>
</section>

<!-- FAQ -->
<section class="section section--subtle" aria-labelledby="faq-title">
    <div class="container" style="max-width:760px">
        <h2 class="section-title" id="faq-title">Häufige Fragen</h2>

        <div class="faq-item">
            <p class="faq-item__question">Kommen Sie auch zu uns nach Hause?</p>
            <p class="faq-item__answer">Ja. Wir sind im gesamten Umkreis von ca. 60 km rund um Selb tätig —
            darunter Rehau, Hof, Marktredwitz, Wunsiedel und weitere Orte im Fichtelgebirge.</p>
        </div>

        <div class="faq-item">
            <p class="faq-item__question">Wie schnell können Sie einen Glasbruch reparieren?</p>
            <p class="faq-item__answer">In dringenden Fällen versuchen wir, noch am gleichen oder nächsten
            Werktag vorbeizukommen. Kontaktieren Sie uns telefonisch für eine schnelle Einschätzung.</p>
        </div>

        <div class="faq-item">
            <p class="faq-item__question">Fertigen Sie auch Sondermaße an?</p>
            <p class="faq-item__answer">Ja, wir schneiden und bearbeiten Glas nach Maß. Bringen Sie die
            genauen Abmessungen mit oder wir nehmen das Maß vor Ort.</p>
        </div>

        <div class="faq-item">
            <p class="faq-item__question">Welche Glassorten bieten Sie an?</p>
            <p class="faq-item__answer">Wir arbeiten mit Float-Glas, Einscheibensicherheitsglas (ESG),
            Verbundsicherheitsglas (VSG), Isolierglas, Ornamentglas und weiteren Spezialgläsern.</p>
        </div>

        <div style="text-align:center;margin-top:2.5rem">
            <p style="margin-bottom:1rem;color:var(--color-text-light)">Weitere Fragen? Wir helfen gerne weiter.</p>
            <a href="/kontakt.php" class="btn btn--primary">Jetzt anfragen</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
```

- [ ] **Schritt 7.2: Im Browser prüfen**

- Alle Leistungs-Artikel sichtbar und lesbar
- Mobile-Ansicht prüfen
- Links von index.php zu leistungen.php funktionieren

- [ ] **Schritt 7.3: Commit**

```bash
git add leistungen.php
git commit -m "feat: leistungsseite mit allen leistungen und FAQ-bereich"
```

---

## Task 8: Kontaktseite (kontakt.php)

**Files:**
- Create: `kontakt.php`

- [ ] **Schritt 8.1: kontakt.php erstellen**

```php
<?php
$page_title = 'Kontakt – Glaserei Povenz Selb';
$page_description = 'Kontaktieren Sie die Glaserei Povenz in Selb. Kontaktformular, Telefon, E-Mail und Anfahrt.';
include 'includes/header.php';

// Fehlermeldungen aus contact-handler.php empfangen
$form_error = $_GET['error'] ?? null;
?>

<div class="page-hero">
    <div class="container">
        <h1>Kontakt</h1>
        <p>Wir freuen uns auf Ihre Anfrage</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="kontakt-grid">

            <!-- Kontaktformular -->
            <div>
                <h2>Anfrage senden</h2>
                <p style="color:var(--color-text-light);margin-bottom:1.5rem">
                    Füllen Sie das Formular aus — wir melden uns so schnell wie möglich bei Ihnen.
                </p>

                <?php if ($form_error): ?>
                    <div class="alert alert--error">
                        <?php
                        $messages = [
                            'missing_fields' => 'Bitte füllen Sie alle Pflichtfelder aus.',
                            'invalid_email'  => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
                            'send_failed'    => 'Beim Senden ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut oder rufen Sie uns an.',
                            'csrf'           => 'Ungültige Anfrage. Bitte laden Sie die Seite neu.',
                        ];
                        echo htmlspecialchars($messages[$form_error] ?? 'Ein unbekannter Fehler ist aufgetreten.');
                        ?>
                    </div>
                <?php endif; ?>

                <form class="contact-form" method="POST" action="/contact-handler.php" novalidate>
                    <!-- CSRF Token -->
                    <?php
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    if (empty($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    }
                    ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <!-- Honeypot -->
                    <div class="hp-field" aria-hidden="true">
                        <label for="website">Website (nicht ausfüllen)</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="name">Name <span style="color:red">*</span></label>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">E-Mail-Adresse <span style="color:red">*</span></label>
                        <input type="email" id="email" name="email" required autocomplete="email"
                               value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="telefon">Telefon (optional)</label>
                        <input type="tel" id="telefon" name="telefon" autocomplete="tel"
                               value="<?= htmlspecialchars($_GET['telefon'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="betreff">Betreff <span style="color:red">*</span></label>
                        <input type="text" id="betreff" name="betreff" required
                               value="<?= htmlspecialchars($_GET['betreff'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="nachricht">Nachricht <span style="color:red">*</span></label>
                        <textarea id="nachricht" name="nachricht" required><?= htmlspecialchars($_GET['nachricht'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group form-group--checkbox">
                        <input type="checkbox" id="datenschutz" name="datenschutz" required>
                        <label for="datenschutz">
                            Ich habe die <a href="/datenschutz.php" target="_blank">Datenschutzerklärung</a>
                            gelesen und stimme der Verarbeitung meiner Daten zur Bearbeitung meiner
                            Anfrage zu. <span style="color:red">*</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn--primary">Anfrage absenden</button>
                </form>
            </div>

            <!-- Kontaktinfos -->
            <div>
                <h2>So erreichen Sie uns</h2>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon">📍</span>
                    <div>
                        <strong>Adresse</strong><br>
                        <address style="font-style:normal;color:var(--color-text-light)">
                            Glaserei Povenz<br>
                            Talstr. 41<br>
                            95100 Selb
                        </address>
                    </div>
                </div>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon">📞</span>
                    <div>
                        <strong>Telefon</strong><br>
                        <a href="tel:+4992874428">09287 / 4428</a>
                    </div>
                </div>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon">✉️</span>
                    <div>
                        <strong>E-Mail</strong><br>
                        <a href="mailto:povenz@t-online.de">povenz@t-online.de</a>
                    </div>
                </div>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon">🕐</span>
                    <div>
                        <strong>Öffnungszeiten</strong><br>
                        <span style="color:var(--color-text-light)">Mo – Fr: 08:00 – 17:00 Uhr</span><br>
                        <span style="color:var(--color-text-light);font-size:0.875rem">
                            ⚠️ Öffnungszeiten bitte bestätigen und anpassen.
                        </span>
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="maps-wrapper" style="margin-top:1.5rem"
                     data-maps-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2506.5!2d12.1333!3d50.1667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zGlaserei+Povenz!5e0!3m2!1sde!2sde!4v1">
                    <div class="maps-placeholder">
                        <p>🗺️</p>
                        <p>Um die Karte zu laden, stimmen Sie bitte der Verwendung von Google Maps zu.</p>
                        <button class="btn btn--primary" onclick="acceptCookies()">Karte laden</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
```

- [ ] **Schritt 8.2: Im Browser prüfen**

- Formular lädt korrekt
- Alle Felder sichtbar und beschriftbar
- Mobile-Layout: Formular und Kontaktinfos stapeln sich
- Maps-Platzhalter erscheint

- [ ] **Schritt 8.3: Commit**

```bash
git add kontakt.php
git commit -m "feat: kontaktseite mit formular, kontaktinfos und maps"
```

---

## Task 9: Kontaktformular-Handler (contact-handler.php)

**Files:**
- Create: `contact-handler.php`

- [ ] **Schritt 9.1: contact-handler.php erstellen**

```php
<?php
// contact-handler.php — verarbeitet POST-Anfragen des Kontaktformulars

session_start();

// Nur POST erlaubt
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /kontakt.php');
    exit;
}

// Hilfsfunktion: sicheres Redirect mit Fehlermeldung
function fail(string $reason): void {
    header('Location: /kontakt.php?error=' . urlencode($reason));
    exit;
}

// CSRF-Token prüfen
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    fail('csrf');
}

// Honeypot prüfen
if (!empty($_POST['website'])) {
    // Bot erkannt — still ignorieren (kein Fehler anzeigen)
    header('Location: /danke.php');
    exit;
}

// Eingaben lesen und bereinigen
$name      = trim(strip_tags($_POST['name'] ?? ''));
$email     = trim($_POST['email'] ?? '');
$telefon   = trim(strip_tags($_POST['telefon'] ?? ''));
$betreff   = trim(strip_tags($_POST['betreff'] ?? ''));
$nachricht = trim(strip_tags($_POST['nachricht'] ?? ''));
$datenschutz = $_POST['datenschutz'] ?? '';

// Pflichtfelder prüfen
if (empty($name) || empty($email) || empty($betreff) || empty($nachricht) || empty($datenschutz)) {
    fail('missing_fields');
}

// E-Mail-Format prüfen
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail('invalid_email');
}

// PHPMailer einbinden
require_once __DIR__ . '/vendor/phpmailer/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server-Einstellungen
    // Für IONOS SMTP: Konfiguration nach Hosting-Kauf anpassen
    // $mail->isSMTP();
    // $mail->Host       = 'smtp.ionos.de';
    // $mail->SMTPAuth   = true;
    // $mail->Username   = 'noreply@glas-povenz.de';
    // $mail->Password   = 'IHR_SMTP_PASSWORT';
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 587;

    // Fallback: PHP mail()
    $mail->isMail();

    // Kodierung
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Absender (muss auf dem Server autorisiert sein)
    $mail->setFrom('noreply@glas-povenz.de', 'Website Glaserei Povenz');
    $mail->addReplyTo($email, $name);

    // Empfänger
    $mail->addAddress('povenz@t-online.de', 'Glaserei Povenz');

    // E-Mail-Inhalt
    $mail->Subject = '[Website-Anfrage] ' . $betreff;

    $body  = "Neue Anfrage über das Kontaktformular auf glas-povenz.de\n";
    $body .= str_repeat('=', 50) . "\n\n";
    $body .= "Name:     " . $name . "\n";
    $body .= "E-Mail:   " . $email . "\n";
    $body .= "Telefon:  " . ($telefon ?: 'nicht angegeben') . "\n";
    $body .= "Betreff:  " . $betreff . "\n\n";
    $body .= "Nachricht:\n" . $nachricht . "\n\n";
    $body .= str_repeat('=', 50) . "\n";
    $body .= "Gesendet am: " . date('d.m.Y H:i') . " Uhr\n";

    $mail->Body = $body;

    $mail->send();

    // CSRF-Token nach Verwendung erneuern
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    header('Location: /danke.php');
    exit;

} catch (Exception $e) {
    // Fehler loggen (nicht anzeigen)
    error_log('PHPMailer Fehler: ' . $mail->ErrorInfo);
    fail('send_failed');
}
```

- [ ] **Schritt 9.2: Lokal testen**

```bash
# PHP-Server starten
php -S localhost:8000

# Formular ausfüllen und absenden
# Erwartetes Ergebnis: Weiterleitung zu danke.php
# E-Mail in povenz@t-online.de prüfen (oder Fehler-Log)
```

Falls kein E-Mail-Server lokal verfügbar: PHP-Fehlerlog prüfen (`error_log`), oder temporär `$mail->Body` in eine Datei schreiben statt zu senden:

```php
// Temporär zum Testen (vor Deployment entfernen!):
file_put_contents(__DIR__ . '/test-email.txt', $body);
header('Location: /danke.php');
exit;
```

- [ ] **Schritt 9.3: Commit**

```bash
git add contact-handler.php
git commit -m "feat: kontaktformular-handler mit PHPMailer, CSRF und honeypot"
```

---

## Task 10: Danke-Seite (danke.php)

**Files:**
- Create: `danke.php`

- [ ] **Schritt 10.1: danke.php erstellen**

```php
<?php
$page_title = 'Vielen Dank – Glaserei Povenz';
$page_description = 'Ihre Anfrage wurde erfolgreich gesendet.';
include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="danke-box">
            <div class="danke-box__icon">✅</div>
            <h1>Vielen Dank!</h1>
            <p style="color:var(--color-text-light);margin:1rem 0 1.5rem">
                Ihre Anfrage wurde erfolgreich gesendet. Wir melden uns so schnell
                wie möglich bei Ihnen — in der Regel innerhalb eines Werktages.
            </p>
            <a href="/index.php" class="btn btn--primary">Zur Startseite</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
```

- [ ] **Schritt 10.2: Commit**

```bash
git add danke.php
git commit -m "feat: danke-seite nach formular-absenden"
```

---

## Task 11: Impressum (impressum.php)

**Files:**
- Create: `impressum.php`

- [ ] **Schritt 11.1: impressum.php erstellen**

```php
<?php
$page_title = 'Impressum – Glaserei Povenz';
$page_description = 'Impressum der Glaserei Povenz, Talstr. 41, 95100 Selb.';
include 'includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1>Impressum</h1>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:720px">

        <h2>Angaben gemäß § 5 TMG</h2>
        <p style="margin-bottom:2rem">
            <strong>Glaserei Povenz</strong><br>
            Talstr. 41<br>
            95100 Selb<br><br>
            Inhaber: [Vollständiger Name des Inhabers eintragen]<br><br>
            Telefon: <a href="tel:+4992874428">09287 / 4428</a><br>
            E-Mail: <a href="mailto:povenz@t-online.de">povenz@t-online.de</a>
        </p>

        <h2>Berufsbezeichnung und berufsrechtliche Regelungen</h2>
        <p style="margin-bottom:2rem">
            Berufsbezeichnung: Glasermeister (verliehen in Deutschland)<br>
            Zuständige Kammer: Handwerkskammer für Oberfranken<br>
            Berufsrechtliche Regelungen: Handwerksordnung (HwO)
            — einsehbar unter <a href="https://www.gesetze-im-internet.de/hwo/" target="_blank" rel="noopener">gesetze-im-internet.de</a>
        </p>

        <h2>EU-Streitschlichtung</h2>
        <p style="margin-bottom:2rem">
            Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:
            <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr/</a><br>
            Unsere E-Mail-Adresse finden Sie oben im Impressum.
            Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer
            Verbraucherschlichtungsstelle teilzunehmen.
        </p>

        <h2>Haftung für Inhalte</h2>
        <p style="margin-bottom:2rem">
            Als Diensteanbieter sind wir gemäß § 7 Abs. 1 TMG für eigene Inhalte auf diesen Seiten
            nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als
            Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde
            Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige
            Tätigkeit hinweisen.
        </p>

        <h2>Haftung für Links</h2>
        <p style="margin-bottom:2rem">
            Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen
            Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen.
            Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der
            Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf
            mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der
            Verlinkung nicht erkennbar.
        </p>

        <h2>Urheberrecht</h2>
        <p>
            Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen
            dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art
            der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen
            Zustimmung des jeweiligen Autors bzw. Erstellers.
        </p>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
```

> **Hinweis:** Den vollständigen Namen des Inhabers eintragen (`[Vollständiger Name des Inhabers eintragen]`).

- [ ] **Schritt 11.2: Commit**

```bash
git add impressum.php
git commit -m "feat: impressum nach paragraph 5 TMG"
```

---

## Task 12: Datenschutzerklärung (datenschutz.php)

**Files:**
- Create: `datenschutz.php`

- [ ] **Schritt 12.1: datenschutz.php erstellen**

```php
<?php
$page_title = 'Datenschutzerklärung – Glaserei Povenz';
$page_description = 'Datenschutzerklärung der Glaserei Povenz gemäß DSGVO.';
include 'includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1>Datenschutzerklärung</h1>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:720px">

        <h2>1. Verantwortlicher</h2>
        <p style="margin-bottom:2rem">
            Glaserei Povenz<br>
            [Vollständiger Name des Inhabers]<br>
            Talstr. 41, 95100 Selb<br>
            Telefon: 09287 / 4428<br>
            E-Mail: povenz@t-online.de
        </p>

        <h2>2. Hosting</h2>
        <p style="margin-bottom:2rem">
            Diese Website wird bei IONOS SE, Elgendorfer Str. 57, 56410 Montabaur, gehostet.
            Beim Aufruf der Website werden technisch notwendige Daten (IP-Adresse, Datum, Uhrzeit,
            aufgerufene Seite) in Server-Logfiles gespeichert. Dies dient der technischen
            Bereitstellung und Sicherheit der Website. Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO
            (berechtigtes Interesse). Die Logdaten werden nach spätestens 7 Tagen gelöscht.
        </p>

        <h2>3. Zugriffsstatistik</h2>
        <p style="margin-bottom:2rem">
            Wir nutzen die integrierte Zugriffsstatistik unseres Hosting-Anbieters IONOS.
            Diese wertet ausschließlich aggregierte, anonymisierte Daten aus (Seitenaufrufe,
            verwendete Browser, Herkunftsland). Es werden keine personenbezogenen Daten
            verarbeitet und kein Tracking-Cookie gesetzt. Ein Cookie-Banner ist hierfür
            nicht erforderlich.
        </p>

        <h2>4. Kontaktformular</h2>
        <p style="margin-bottom:2rem">
            Wenn Sie uns über das Kontaktformular eine Nachricht senden, werden die von Ihnen
            eingegebenen Daten (Name, E-Mail-Adresse, ggf. Telefonnummer, Betreff und
            Nachricht) zur Bearbeitung Ihrer Anfrage bei uns gespeichert und verarbeitet.
            Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.<br><br>
            Rechtsgrundlage: Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an der
            Beantwortung von Anfragen).<br><br>
            Die Daten werden gelöscht, sobald Ihre Anfrage abschließend bearbeitet wurde
            und keine gesetzlichen Aufbewahrungspflichten entgegenstehen (in der Regel
            nach 3 Jahren).
        </p>

        <h2>5. Google Maps</h2>
        <p style="margin-bottom:2rem">
            Diese Website verwendet Google Maps zur Darstellung unseres Standorts.
            Anbieter ist Google LLC, 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA
            (bzw. für Nutzer in der EU: Google Ireland Limited, Gordon House, Barrow Street,
            Dublin 4, Irland).<br><br>
            Google Maps wird erst nach Ihrer ausdrücklichen Zustimmung (Cookie-Banner) geladen.
            Ohne Zustimmung wird kein Inhalt von Google eingebunden. Bei Zustimmung wird
            Ihre IP-Adresse und der Standort der Karte an Google übermittelt. Rechtsgrundlage
            ist Art. 6 Abs. 1 lit. a DSGVO (Einwilligung).<br><br>
            Sie können Ihre Einwilligung jederzeit widerrufen, indem Sie den
            <code>maps_consent</code>-Eintrag in Ihrem Browser-Speicher (localStorage)
            löschen oder den Browser-Cache leeren.<br><br>
            Informationen zur Datennutzung durch Google:
            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">policies.google.com/privacy</a>
        </p>

        <h2>6. Ihre Rechte</h2>
        <p style="margin-bottom:0.5rem">Sie haben das Recht auf:</p>
        <ul style="margin-bottom:2rem;padding-left:1.5rem;list-style:disc;color:var(--color-text-light)">
            <li>Auskunft über Ihre gespeicherten Daten (Art. 15 DSGVO)</li>
            <li>Berichtigung unrichtiger Daten (Art. 16 DSGVO)</li>
            <li>Löschung Ihrer Daten (Art. 17 DSGVO)</li>
            <li>Einschränkung der Verarbeitung (Art. 18 DSGVO)</li>
            <li>Widerspruch gegen die Verarbeitung (Art. 21 DSGVO)</li>
            <li>Datenübertragbarkeit (Art. 20 DSGVO)</li>
            <li>Beschwerde bei der zuständigen Aufsichtsbehörde</li>
        </ul>
        <p style="margin-bottom:2rem">
            Zuständige Aufsichtsbehörde:<br>
            Bayerisches Landesamt für Datenschutzaufsicht (BayLDA)<br>
            Promenade 27, 91522 Ansbach<br>
            <a href="https://www.lda.bayern.de" target="_blank" rel="noopener">www.lda.bayern.de</a>
        </p>

        <h2>7. Keine weiteren Drittdienste</h2>
        <p>
            Diese Website verwendet kein Google Analytics, kein Facebook Pixel, keine
            Social-Media-Plugins, keine externen Schriftarten oder sonstige Dienste,
            die personenbezogene Daten übermitteln würden.
        </p>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
```

- [ ] **Schritt 12.2: Commit**

```bash
git add datenschutz.php
git commit -m "feat: DSGVO-konforme datenschutzerklaerung"
```

---

## Task 13: SEO-Dateien

**Files:**
- Create: `sitemap.xml`
- Create: `robots.txt`
- Create: `.htaccess`

- [ ] **Schritt 13.1: sitemap.xml erstellen**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://glas-povenz.de/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://glas-povenz.de/leistungen.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>https://glas-povenz.de/kontakt.php</loc>
        <changefreq>yearly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://glas-povenz.de/impressum.php</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>https://glas-povenz.de/datenschutz.php</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
</urlset>
```

- [ ] **Schritt 13.2: robots.txt erstellen**

```
User-agent: *
Allow: /

Disallow: /contact-handler.php
Disallow: /includes/
Disallow: /vendor/

Sitemap: https://glas-povenz.de/sitemap.xml
```

- [ ] **Schritt 13.3: .htaccess erstellen**

```apache
# PHP-Dateien als .php ausführen
Options -Indexes

# HTTPS erzwingen (nach Domain-Umzug aktivieren)
# RewriteEngine On
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# www → non-www (oder umgekehrt, je nach IONOS-Einstellung)
# RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
# RewriteRule ^ https://%1%{REQUEST_URI} [R=301,L]

# Sicherheitsheader
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Includes und vendor nicht direkt aufrufbar
<FilesMatch "^(contact-handler\.php)$">
    # Nur POST von der eigenen Domain erlauben — wird per CSRF gesichert
</FilesMatch>
```

- [ ] **Schritt 13.4: Commit**

```bash
git add sitemap.xml robots.txt .htaccess
git commit -m "feat: SEO-dateien sitemap, robots.txt und .htaccess sicherheitsheader"
```

---

## Task 14: Abschluss-Tests & Deployment-Vorbereitung

- [ ] **Schritt 14.1: Vollständiger lokaler Test**

```bash
php -S localhost:8000
```

Alle Seiten öffnen und prüfen:
- [ ] `localhost:8000/index.php` — alle Sektionen, Cookie-Banner
- [ ] `localhost:8000/leistungen.php` — alle Leistungen, FAQ
- [ ] `localhost:8000/kontakt.php` — Formular, Karte
- [ ] `localhost:8000/impressum.php`
- [ ] `localhost:8000/datenschutz.php`
- [ ] Formular absenden → Weiterleitung zu `danke.php`
- [ ] Formular mit fehlenden Feldern → Fehlermeldung auf `kontakt.php`
- [ ] Cookie-Banner akzeptieren → Karte lädt
- [ ] Cookie-Banner ablehnen → Karte bleibt als Platzhalter
- [ ] Mobile-Ansicht (Chrome DevTools, iPhone SE) — alle Seiten

- [ ] **Schritt 14.2: Google Maps Embed-Link aktualisieren**

1. maps.google.com → "Glaserei Povenz Selb" suchen
2. Teilen → Karte einbetten → Link kopieren
3. In `index.php` und `kontakt.php` den `data-maps-src`-Attributwert ersetzen

- [ ] **Schritt 14.3: Echte Google-Rezensionen eintragen**

In `index.php`: Die 3 Beispiel-Rezensionen durch echte Google-Bewertungen ersetzen
(Name und Text aus dem Google-Unternehmensprofil kopieren).

- [ ] **Schritt 14.4: Inhabername im Impressum eintragen**

In `impressum.php` und `datenschutz.php`: `[Vollständiger Name des Inhabers]` ersetzen.

- [ ] **Schritt 14.5: Öffnungszeiten prüfen**

In `kontakt.php`: Öffnungszeiten mit dem Kunden abstimmen und eintragen.

- [ ] **Schritt 14.6: Finaler Commit**

```bash
git add -A
git commit -m "feat: website vollstaendig, bereit fuer deployment"
```

---

## Task 15: IONOS Deployment (nach Hosting-Kauf)

- [ ] **Schritt 15.1: IONOS Webhosting einrichten**

1. IONOS Webhosting-Paket buchen (Starter oder Essential reicht)
2. Domain `glas-povenz.de` von Telekom zu IONOS transferieren (Auth-Code anfordern)
3. FTP-Zugangsdaten aus dem IONOS Control Panel notieren

- [ ] **Schritt 15.2: Dateien hochladen**

```bash
# Mit FTP-Client (z.B. FileZilla) oder per SFTP:
# Host: Ihr FTP-Host aus IONOS Control Panel
# Alle Dateien in das Verzeichnis /htdocs/ oder /public_html/ hochladen
# vendor/ Ordner mitübertragen
```

- [ ] **Schritt 15.3: SMTP für PHPMailer konfigurieren**

In `contact-handler.php` den auskommentierten SMTP-Block aktivieren und
IONOS SMTP-Zugangsdaten eintragen:
- Host: `smtp.ionos.de`
- Port: `587` (STARTTLS) oder `465` (SSL)
- Benutzername + Passwort: aus IONOS E-Mail-Einstellungen

- [ ] **Schritt 15.4: HTTPS-Weiterleitung in .htaccess aktivieren**

Die auskommentierten `RewriteEngine`-Zeilen in `.htaccess` aktivieren.

- [ ] **Schritt 15.5: Live-Test**

- Alle Seiten auf glas-povenz.de öffnen
- Kontaktformular absenden → E-Mail in `povenz@t-online.de` prüfen
- Google Maps laden (nach Cookie-Zustimmung)
- Mobile-Ansicht auf echtem Smartphone prüfen
- Google Search Console: Domain anmelden und sitemap.xml einreichen

---

## Foto-Vorschläge für den Fotoshoot

Folgende Aufnahmen würden die Website deutlich aufwerten:

| Foto | Verwendung | Hinweis |
|---|---|---|
| Glasermeister bei Arbeit (Schneiden/Montieren) | Hero-Hintergrundbild | Natürliches Licht, Werkstatt-Atmosphäre |
| Fertige Duschkabine | Leistungs-Karte Duschkabinen | Sauber, hell, modernes Bad |
| Neue Fensterscheibe eingebaut | Leistungs-Karte Fenster | Vor/nach Effekt möglich |
| Spiegel (fertig montiert) | Leistungs-Karte Spiegel | |
| Außenansicht der Werkstatt | Über-uns oder Kontakt | Zeigt lokale Präsenz |
| Portrait des Inhabers | Über-uns Sektion | Schafft Vertrauen, persönlicher Eindruck |
| Bleiverglasungs-Detail | Leistungs-Karte Bleiverglasung | Handwerkliche Qualität sichtbar machen |

---

## Selbstüberprüfung (Spec-Abdeckung)

| Anforderung aus Spec | Abgedeckt in Task |
|---|---|
| Multi-Page mit PHP-Includes | Task 3, 4 |
| Kontaktformular → E-Mail | Task 8, 9 |
| PHPMailer + CSRF + Honeypot | Task 9 |
| Google Maps mit Cookie-Zustimmung | Task 5, 6, 8 |
| Cookie-Banner (Vanilla JS) | Task 5 |
| Statische Google-Rezensionen | Task 6 |
| Impressum §5 TMG | Task 11 |
| DSGVO Datenschutzerklärung | Task 12 |
| LocalBusiness JSON-LD | Task 6 |
| Meta-Tags pro Seite | Task 3 (header.php) |
| sitemap.xml + robots.txt | Task 13 |
| Responsive / Mobile-first | Task 2 (CSS) |
| SEO Einzugsgebiet-Text | Task 6 (index.php) |
| FAQ-Bereich (LLM SEO) | Task 7 |
| IONOS Deployment | Task 15 |

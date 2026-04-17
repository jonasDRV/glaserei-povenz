<?php
// Session-Management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF-Token initialisieren
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Standardwerte für Seitentitel und Beschreibung
if (!isset($page_title)) {
    $page_title = 'Glaserei Povenz – Ihr Glasermeister in Selb';
}
if (!isset($page_description)) {
    $page_description = 'Professionelle Glasarbeiten in Selb und Umgebung. Fensterglas, Duschkabinen, Spiegel, Wintergärten und mehr.';
}

// Aktuelle Seite ermitteln
$current_page = basename($_SERVER['PHP_SELF']);

// Canonical URL zusammensetzen
$canonical_url = 'https://glas-povenz.de/' . ($current_page === 'index.php' ? '' : $current_page);

// XSS-sichere Ausgabe der Variablen
$safe_title       = htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8');
$safe_description = htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8');
$safe_canonical   = htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2a6032">

    <title><?= $safe_title ?></title>
    <meta name="description" content="<?= $safe_description ?>">

    <!-- SEO: Robots-Direktiven -->
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">

    <!-- SEO: Keywords (niedriges Gewicht, aber für Bing/Yandex relevant) -->
    <meta name="keywords" content="Glaserei Selb, Glaser Selb, Glasermeister Selb, Fensterglas Selb, Glasreparatur Selb, Glasbruch Selb, Duschkabine Selb, Spiegel Selb, Bleiverglasung, Isolierglas, Wintergarten, Schaufensterverglasung, Glaserei Fichtelgebirge, Glaser Hof, Glaser Rehau, Glaser Marktredwitz, Glasnotdienst Selb, Glasermeister Povenz">

    <!-- SEO: Autor & Geo -->
    <meta name="author" content="Glaserei Povenz – Andreas Povenz">
    <meta name="geo.region" content="DE-BY">
    <meta name="geo.placename" content="Selb, Bayern">
    <meta name="geo.position" content="50.1667;12.1333">
    <meta name="ICBM" content="50.1667, 12.1333">

    <link rel="canonical" href="<?= $safe_canonical ?>">

    <!-- Open Graph -->
    <meta property="og:site_name"   content="Glaserei Povenz">
    <meta property="og:title"       content="<?= $safe_title ?>">
    <meta property="og:description" content="<?= $safe_description ?>">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="<?= $safe_canonical ?>">
    <meta property="og:locale"      content="de_DE">
    <meta property="og:image"       content="https://glas-povenz.de/images/Glaserei-Schaufenster.webp">
    <meta property="og:image:alt"   content="Glaserei Povenz – Schaufenster in der Talstraße 41, Selb">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= $safe_title ?>">
    <meta name="twitter:description" content="<?= $safe_description ?>">
    <meta name="twitter:image"       content="https://glas-povenz.de/images/Glaserei-Schaufenster.webp">

    <!-- Fonts: Preconnect + Link (ersetzt @import — deutlich bessere Performance) -->
    <!-- GDPR: Self-host before production deployment -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;600;700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Favicon (Platzhalter) -->
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body>

<!-- Skip-Link für Tastatur-Navigation & Screenreader -->
<a href="#main-content" class="skip-link">Zum Hauptinhalt springen</a>

<!-- Cookie-Banner -->
<div id="cookieBanner"
     class="cookie-banner"
     role="dialog"
     aria-label="Cookie-Hinweis"
     aria-modal="true">
    <div class="cookie-banner__inner">
        <p class="cookie-banner__text">
            Diese Website verwendet Google Maps zur Anzeige unseres Standorts.
            Dabei werden Daten an Google LLC übertragen. Mehr Informationen in unserer
            <a href="/datenschutz.php">Datenschutzerklärung</a>.
        </p>
        <div class="cookie-banner__buttons">
            <button class="btn btn--primary" onclick="acceptCookies()">Akzeptieren</button>
            <button class="btn btn--outline btn--light" onclick="declineCookies()">Ablehnen</button>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="nav" aria-label="Hauptnavigation">
    <div class="nav__inner">

        <!-- Logo -->
        <a href="/index.php" class="nav__logo" aria-label="Glaserei Povenz – Startseite">
            <?php if (file_exists(__DIR__ . '/../images/logo.png')): ?>
                <img src="/images/logo.png" alt="Glaserei Povenz Logo" height="45" style="object-fit: contain;">
            <?php else: ?>
                <span class="nav__logo-text">Glaserei Povenz</span>
            <?php endif; ?>
        </a>

        <!-- Hamburger-Button -->
        <button id="navToggle"
                class="nav__toggle"
                aria-expanded="false"
                aria-controls="navLinks"
                aria-label="Navigation öffnen">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Navigationslinks -->
        <ul id="navLinks" class="nav__links" role="list">
            <li>
                <a href="/index.php"<?= ($current_page === 'index.php') ? ' class="active"' : '' ?>>Startseite</a>
            </li>
            <li>
                <a href="/leistungen.php"<?= ($current_page === 'leistungen.php') ? ' class="active"' : '' ?>>Leistungen</a>
            </li>
            <li>
                <a href="/kontakt.php"<?= ($current_page === 'kontakt.php') ? ' class="active"' : '' ?>>Kontakt</a>
            </li>
            <li>
                <a href="/kontakt.php" class="btn btn--primary" style="padding: 0.45rem 1.1rem; font-size: 0.875rem;">Jetzt anfragen</a>
            </li>
        </ul>

    </div>
</nav>

<main id="main-content" role="main" tabindex="-1">

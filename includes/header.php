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

    <title><?= $safe_title ?></title>
    <meta name="description" content="<?= $safe_description ?>">
    <meta name="robots" content="index, follow">

    <link rel="canonical" href="<?= $safe_canonical ?>">

    <!-- Open Graph -->
    <meta property="og:title"       content="<?= $safe_title ?>">
    <meta property="og:description" content="<?= $safe_description ?>">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="<?= $safe_canonical ?>">
    <meta property="og:locale"      content="de_DE">
    <meta property="og:image"       content="https://glas-povenz.de/images/og-image.jpg">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Favicon (Platzhalter) -->
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body>

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

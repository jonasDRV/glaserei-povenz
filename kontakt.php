<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Session-Flash für Formularfelder nach Fehler
$form_flash = $_SESSION['form_flash'] ?? [];
unset($_SESSION['form_flash']); // einmalig lesen

$page_title = 'Kontakt – Glaserei Povenz Selb';
$page_description = 'Kontaktieren Sie die Glaserei Povenz in Selb. Kontaktformular, Telefon, E-Mail und Anfahrt zur Werkstatt.';
include 'includes/header.php';

$form_error = isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : null;
?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Startseite", "item": "https://glas-povenz.de/"},
    {"@type": "ListItem", "position": 2, "name": "Kontakt", "item": "https://glas-povenz.de/kontakt.php"}
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Kontakt – Glaserei Povenz Selb",
  "url": "https://glas-povenz.de/kontakt.php",
  "mainEntity": {"@id": "https://glas-povenz.de/#business"}
}
</script>

<div class="page-hero">
    <div class="container">
        <span class="label">Kontakt</span>
        <h1>Sprechen Sie uns an</h1>
        <p>Wir freuen uns auf Ihre Anfrage</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="kontakt-grid">

            <!-- Column 1: Contact Form -->
            <div>
                <h2>Anfrage senden</h2>
                <p style="color: var(--color-text-light); margin-bottom: 1.5rem;">
                    Füllen Sie das Formular aus – wir melden uns so schnell wie möglich.
                </p>

                <?php if ($form_error): ?>
                <div class="alert alert--error" role="alert">
                    <?php
                    $error_messages = [
                        'missing_fields' => 'Bitte füllen Sie alle Pflichtfelder aus.',
                        'invalid_email'  => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
                        'send_failed'    => 'Beim Senden ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut oder rufen Sie uns an.',
                        'csrf'           => 'Ungültige Anfrage. Bitte laden Sie die Seite neu.',
                        'spam'           => 'Ihre Nachricht konnte nicht gesendet werden.',
                    ];
                    echo htmlspecialchars($error_messages[$form_error] ?? 'Ein unbekannter Fehler ist aufgetreten.', ENT_QUOTES, 'UTF-8');
                    ?>
                </div>
                <?php endif; ?>

                <form class="contact-form" method="POST" action="/contact-handler.php" novalidate>
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                    <!-- Honeypot (hidden from real users) -->
                    <div class="hp-field" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="name">Name <span aria-hidden="true" style="color: var(--color-error-text);">*</span></label>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               placeholder="Ihr vollständiger Name" value="<?= htmlspecialchars($form_flash['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">E-Mail-Adresse <span aria-hidden="true" style="color: var(--color-error-text);">*</span></label>
                        <input type="email" id="email" name="email" required autocomplete="email"
                               placeholder="ihre@email.de" value="<?= htmlspecialchars($form_flash['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                        <label for="telefon">Telefon <span style="color: var(--color-text-light); font-weight: 400;">(optional)</span></label>
                        <input type="tel" id="telefon" name="telefon" autocomplete="tel"
                               placeholder="z. B. 09287 / 1234" value="<?= htmlspecialchars($form_flash['telefon'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                        <label for="betreff">Betreff <span aria-hidden="true" style="color: var(--color-error-text);">*</span></label>
                        <input type="text" id="betreff" name="betreff" required
                               placeholder="Worum geht es?" value="<?= htmlspecialchars($form_flash['betreff'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                        <label for="nachricht">Nachricht <span aria-hidden="true" style="color: var(--color-error-text);">*</span></label>
                        <textarea id="nachricht" name="nachricht" required
                                  placeholder="Beschreiben Sie Ihr Anliegen..."><?= htmlspecialchars($form_flash['nachricht'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="form-group form-group--checkbox">
                        <input type="checkbox" id="datenschutz" name="datenschutz" required>
                        <label for="datenschutz">
                            Ich habe die <a href="/datenschutz.php" target="_blank" rel="noopener">Datenschutzerklärung</a>
                            gelesen und stimme der Verarbeitung meiner Daten zur Bearbeitung meiner Anfrage zu.
                            <span aria-hidden="true" style="color: var(--color-error-text);">*</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn--primary">Anfrage absenden</button>
                </form>
            </div>

            <!-- Column 2: Contact Info + Maps -->
            <div>
                <h2>So erreichen Sie uns</h2>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon" aria-hidden="true">📍</span>
                    <div>
                        <strong>Adresse</strong>
                        <address style="font-style: normal; color: var(--color-text-light); margin-top: 0.25rem;">
                            Glaserei Povenz<br>
                            Talstr. 41<br>
                            95100 Selb
                        </address>
                    </div>
                </div>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon" aria-hidden="true">📞</span>
                    <div>
                        <strong>Telefon</strong>
                        <p style="color: var(--color-text-light); margin-top: 0.25rem;">
                            <a href="tel:+4992874428">09287 / 4428</a><br>
                            <a href="tel:+4917016954937">0170 / 169 54 37</a> (Mobil)
                        </p>
                    </div>
                </div>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon" aria-hidden="true">✉️</span>
                    <div>
                        <strong>E-Mail</strong>
                        <p style="color: var(--color-text-light); margin-top: 0.25rem;">
                            <a href="mailto:povenz@t-online.de">povenz@t-online.de</a>
                        </p>
                    </div>
                </div>

                <div class="kontakt-info__item">
                    <span class="kontakt-info__icon" aria-hidden="true">🕐</span>
                    <div>
                        <strong>Öffnungszeiten</strong>
                        <p style="color: var(--color-text-light); margin-top: 0.25rem;">
                            Mo – Fr: 08:00 – 17:00 Uhr<br>
                            Sa: 09:00 – 12:00 Uhr
                        </p>
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="maps-wrapper" style="margin-top: 1.5rem;"
                     data-maps-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2555.6061897495424!2d12.125557777004007!3d50.16850137153979!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a0fda9e679ceb1%3A0xd3c1af87fdff600a!2sGlaserei%20Povenz%20UG!5e0!3m2!1sde!2ses!4v1776445812562!5m2!1sde!2ses">
                    <div class="maps-placeholder">
                        <p style="font-size: 2rem;" aria-hidden="true">🗺️</p>
                        <p>Um die Karte zu laden, stimmen Sie bitte der Verwendung von Google Maps zu.</p>
                        <button class="btn btn--primary" onclick="acceptCookies()" type="button">Karte laden</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

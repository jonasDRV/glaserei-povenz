<?php
// contact-handler.php — Verarbeitet das Kontaktformular

session_start();

// Nur POST erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /kontakt.php');
    exit;
}

// Hilfsfunktion: Redirect mit Fehlermeldung
function fail(string $reason, array $fields = []): void {
    if (!empty($fields)) {
        $_SESSION['form_flash'] = $fields;
    }
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
    // Bot erkannt — stillschweigend zur Danke-Seite (kein Hinweis)
    header('Location: /danke.php');
    exit;
}

// Eingaben lesen und bereinigen
$name      = trim(strip_tags($_POST['name'] ?? ''));
$email     = trim(strip_tags($_POST['email'] ?? ''));
$telefon   = trim(strip_tags($_POST['telefon'] ?? ''));
$betreff   = trim(strip_tags($_POST['betreff'] ?? ''));
$nachricht = trim(strip_tags($_POST['nachricht'] ?? ''));
$datenschutz = $_POST['datenschutz'] ?? '';

// Header-Injection-Schutz: Zeilenumbrüche aus Name entfernen
$name = str_replace(["\r", "\n"], '', $name);

// Pflichtfelder prüfen
if (empty($name) || empty($email) || empty($betreff) || empty($nachricht) || empty($datenschutz)) {
    fail('missing_fields', [
        'name'      => $name,
        'email'     => $email,
        'telefon'   => $telefon,
        'betreff'   => $betreff,
        'nachricht' => $nachricht,
    ]);
}

// E-Mail-Format prüfen
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail('invalid_email', [
        'name'    => $name,
        'email'   => $email,
        'telefon' => $telefon,
        'betreff' => $betreff,
        'nachricht' => $nachricht,
    ]);
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
    // IONOS SMTP — nach Hosting-Kauf aktivieren und Zugangsdaten eintragen:
    // $mail->isSMTP();
    // $mail->Host       = 'smtp.ionos.de';
    // $mail->SMTPAuth   = true;
    // $mail->Username   = 'noreply@glas-povenz.de';
    // $mail->Password   = 'IHR_SMTP_PASSWORT';
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 587;

    // Fallback: PHP mail() für lokale Tests
    $mail->isMail();

    $mail->CharSet  = 'UTF-8';
    $mail->Encoding = 'base64';

    // Absender (muss auf IONOS-Server autorisiert sein)
    $mail->setFrom('noreply@glas-povenz.de', 'Website Glaserei Povenz');
    $mail->addReplyTo($email, $name);

    // Empfänger
    $mail->addAddress('povenz@t-online.de', 'Glaserei Povenz');

    // Betreff
    $mail->Subject = '[Website-Anfrage] ' . $betreff;

    // E-Mail-Text (plain text)
    $divider = str_repeat('=', 50);
    $body  = "Neue Anfrage über das Kontaktformular auf glas-povenz.de\n";
    $body .= $divider . "\n\n";
    $body .= "Name:      " . $name     . "\n";
    $body .= "E-Mail:    " . $email    . "\n";
    $body .= "Telefon:   " . ($telefon ?: 'nicht angegeben') . "\n";
    $body .= "Betreff:   " . $betreff  . "\n\n";
    $body .= "Nachricht:\n" . $nachricht . "\n\n";
    $body .= $divider . "\n";
    $body .= "Gesendet am: " . date('d.m.Y H:i') . " Uhr\n";

    $mail->Body = $body;

    $mail->send();

    // CSRF-Token nach Verwendung erneuern (verhindert Replay-Angriffe)
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    header('Location: /danke.php');
    exit;

} catch (Exception $e) {
    // Fehler loggen — NICHT dem Nutzer anzeigen
    error_log('PHPMailer Fehler: ' . $mail->ErrorInfo);
    fail('send_failed', [
        'name'      => $name,
        'email'     => $email,
        'telefon'   => $telefon,
        'betreff'   => $betreff,
        'nachricht' => $nachricht,
    ]);
}

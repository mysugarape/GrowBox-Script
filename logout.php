<?php
include __DIR__ . '/config/auth.php'; // Benutzer muss eingeloggt sein
session_start(); // Sitzung starten

// Sitzung und alle Benutzerdaten löschen
session_unset(); // Löscht alle Session-Variablen
session_destroy(); // Zerstört die Session

// Benutzer auf die Login-Seite oder Startseite weiterleiten
header("Location: login.php"); 
exit;
?>

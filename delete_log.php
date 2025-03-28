<?php
include __DIR__ . '/config/auth.php'; // Login-Schutz (falls du einen Login hast)
include __DIR__ . '/config/db.php';   // Verbindung zur Datenbank

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Holen der Datensatz-ID aus dem Formular
    $log_id = $_POST['id'];
    $plant_id = $_GET['id']; // Holen der Pflanzen-ID aus der URL

    // Sicherstellen, dass die ID nicht leer ist
    if ($log_id && $plant_id) {
        // Vorbereiten des SQL-Statements zum Löschen des Eintrags
        $stmt = $pdo->prepare("DELETE FROM plant_logs WHERE id = ?");
        $stmt->execute([$log_id]);

        // Weiterleitung zurück zur Pflanzenübersicht mit der Pflanzen-ID
        header("Location: view_plant.php?id=" . $plant_id);
        exit;
    } else {
        // Fehler, wenn keine ID angegeben wurde
        echo "Keine gültige ID angegeben!";
        exit;
    }
} else {
    // Wenn keine POST-Anfrage, Weiterleitung zur Pflanzenübersicht
    header("Location: view_plant.php?id=" . $_GET['id']);
    exit;
}
?>

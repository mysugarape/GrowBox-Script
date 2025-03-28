<?php
include __DIR__ . '/config/auth.php'; // Benutzer muss eingeloggt sein
include __DIR__ . '/config/db.php';

if (!isset($_GET['id'])) {
    die("Fehler: Keine Pflanzen-ID angegeben.");
}

$plant_id = $_GET['id'];

// Pflanze abrufen, um Name anzuzeigen
$stmt = $pdo->prepare("SELECT name FROM plants WHERE id = ?");
$stmt->execute([$plant_id]);
$plant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plant) {
    die("Fehler: Pflanze nicht gefunden.");
}

// Wenn das Formular abgeschickt wurde, löschen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo->prepare("DELETE FROM plant_logs WHERE plant_id = ?")->execute([$plant_id]); // Messwerte löschen
    $pdo->prepare("DELETE FROM plants WHERE id = ?")->execute([$plant_id]); // Pflanze löschen

    header("Location: index.php?message=Pflanze erfolgreich gelöscht");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Pflanze löschen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-danger">⚠️ Pflanze löschen</h2>
        <p>Bist du sicher, dass du die Pflanze <strong><?= htmlspecialchars($plant['name']) ?></strong> und alle zugehörigen Messwerte löschen möchtest?</p>

        <form method="post">
            <button type="submit" class="btn btn-danger">Ja, löschen</button>
            <a href="index.php" class="btn btn-secondary">Abbrechen</a>
        </form>
    </div>
</body>

</html>

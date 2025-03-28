<?php
include __DIR__ . '/config/auth.php'; // Schutz hinzufügen
include __DIR__ . '/config/db.php';

// Holen der Pflanze aus der Datenbank
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM plants WHERE id = ?");
    $stmt->execute([$id]);
    $plant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plant) {
        die("Pflanze nicht gefunden.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eingabewerte aus dem Formular
    $name = $_POST['name'];
    $species = $_POST['species'];
    $source = $_POST['source'];  // Quelle

    // Die Eingabewerte in der Datenbank aktualisieren
    $stmt = $pdo->prepare("UPDATE plants SET name = ?, species = ?, source = ? WHERE id = ?");
    $stmt->execute([$name, $species, $source, $id]);

    // Nach dem Bearbeiten weiterleiten
    header("Location: index.php?message=Pflanze erfolgreich bearbeitet!");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Pflanze bearbeiten</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2>Pflanze bearbeiten</h2>

        <!-- Formular zur Bearbeitung der Pflanze -->
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Pflanzenname</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($plant['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="species" class="form-label">Art</label>
                <input type="text" class="form-control" id="species" name="species" value="<?= htmlspecialchars($plant['species']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_added" class="form-label">Datum hinzugefügt</label>
                <input type="date" class="form-control" id="date_added" name="date_added" value="<?= substr($plant['date_added'], 0, 10) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="source" class="form-label">Quelle</label>
                <input type="text" class="form-control" id="source" name="source" value="<?= htmlspecialchars($plant['source']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success btn-sm">Änderungen speichern</button>
            <a href="index.php" class="btn btn-secondary btn-sm">Abbrechen</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

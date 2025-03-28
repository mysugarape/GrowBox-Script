<?php
include __DIR__ . '/config/auth.php'; // Benutzer muss eingeloggt sein
include __DIR__ . '/config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eingabewerte aus dem Formular
    $name = $_POST['name'];
    $species = $_POST['species'];
    $date_added = $_POST['date_added'];
    $source = $_POST['source'];  // Neue Quelle hinzufügen

    // Die Eingabewerte in der Datenbank speichern
    $stmt = $pdo->prepare("INSERT INTO plants (name, species, date_added, source) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $species, $date_added, $source]);

    header("Location: index.php?message=Pflanze erfolgreich hinzugefügt!");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Pflanze hinzufügen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2>Neue Pflanze hinzufügen</h2>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Pflanzenname</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="species" class="form-label">Art</label>
                <input type="text" class="form-control" id="species" name="species" required>
            </div>
            <div class="mb-3">
                <label for="date_added" class="form-label">Start</label>
                <input type="date" class="form-control" id="date_added" name="date_added" required>
            </div>
            <div class="mb-3">
                <label for="source" class="form-label">Quelle</label>
                <input type="text" class="form-control" id="source" name="source" placeholder="z.B. Gärtnerei, Online-Shop, etc." required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Pflanze hinzufügen</button>
            <a href="index.php" class="btn btn-success btn-sm">Abbrechen</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

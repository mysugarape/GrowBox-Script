<?php
include __DIR__ . '/config/auth.php'; // Login-Schutz
include __DIR__ . '/config/db.php';

$log_id = $_GET['id'];

// Den Datensatz für das Bearbeiten abrufen
$stmt = $pdo->prepare("SELECT * FROM plant_logs WHERE id = ?");
$stmt->execute([$log_id]);
$log = $stmt->fetch(PDO::FETCH_ASSOC);

// Falls der Datensatz nicht existiert, Fehler anzeigen
if (!$log) {
    die("Eintrag nicht gefunden.");
}

// Speichern der Änderungen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $height = $_POST['height'];
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $root_temperature = $_POST['root_temperature'];
    $ph_value = $_POST['ph_value'];
    $ec_value = $_POST['ec_value'];
    $air_humidity = $_POST['air_humidity'];
    $light_hours = $_POST['light_hours'];
    $plant_status = $_POST['plant_status'];
    $notes = $_POST['notes'];
    $water = isset($_POST['water']) ? $_POST['water'] : null;
    $fertilizer = isset($_POST['fertilizer']) ? $_POST['fertilizer'] : null;

    // SQL-Update-Statement
    $stmt = $pdo->prepare("UPDATE plant_logs SET 
        height = ?, 
        temperature = ?, 
        humidity = ?, 
        root_temperature = ?, 
        ph_value = ?, 
        ec_value = ?, 
        air_humidity = ?, 
        light_hours = ?, 
        plant_status = ?, 
        notes = ?, 
        water = ?, 
        fertilizer = ? 
        WHERE id = ?");
    $stmt->execute([$height, $temperature, $humidity, $root_temperature, $ph_value, $ec_value, $air_humidity, $light_hours, $plant_status, $notes, $water, $fertilizer, $log_id]);

    // Nach dem Bearbeiten zurück zur Pflanzenansicht
    header("Location: view_plant.php?id=" . $_GET['id']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Bearbeiten - Pflanzeneintrag</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h1 class="text-center">Pflanzenlog bearbeiten</h1>
        <form method="POST">
            <div class="row">
                <div class="col-md-4">
                    <label>Pflanzenhöhe (cm):</label>
                    <input type="number" step="0.1" name="height" class="form-control" value="<?= $log['height'] ?>">
                </div>
                <div class="col-md-4">
                    <label>Lufttemperatur (°C):</label>
                    <input type="number" step="0.1" name="temperature" class="form-control" value="<?= $log['temperature'] ?>">
                </div>
                <div class="col-md-4">
                    <label>Soil (%):</label>
                    <input type="number" step="0.1" name="humidity" class="form-control" value="<?= $log['humidity'] ?>">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label>Wurzeltemp (°C):</label>
                    <input type="number" step="0.1" name="root_temperature" class="form-control" value="<?= $log['root_temperature'] ?>">
                </div>
                <div class="col-md-4">
                    <label>pH-Wert:</label>
                    <input type="number" step="0.1" name="ph_value" class="form-control" value="<?= $log['ph_value'] ?>">
                </div>
                <div class="col-md-4">
                    <label>EC-Wert (µS/cm):</label>
                    <input type="number" step="0.1" name="ec_value" class="form-control" value="<?= $log['ec_value'] ?>">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label>Luftfeuchtigkeit (%):</label>
                    <input type="number" step="0.1" name="air_humidity" class="form-control" value="<?= $log['air_humidity'] ?>">
                </div>
                <div class="col-md-4">
                    <label>Lichtstunden/Tag:</label>
                    <input type="number" step="0.1" name="light_hours" class="form-control" value="<?= $log['light_hours'] ?>">
                </div>
                <div class="col-md-4">
                    <label>Pflanzenstatus:</label>
                    <select name="plant_status" class="form-control">
                        <option <?= ($log['plant_status'] == 'Sämling') ? 'selected' : '' ?>>Sämling</option>
                        <option <?= ($log['plant_status'] == 'Keimling') ? 'selected' : '' ?>>Keimling</option>
                        <option <?= ($log['plant_status'] == 'Vegetativ') ? 'selected' : '' ?>>Vegetativ</option>
                        <option <?= ($log['plant_status'] == 'Blüte') ? 'selected' : '' ?>>Blüte</option>
                        <option <?= ($log['plant_status'] == 'Ernte') ? 'selected' : '' ?>>Ernte</option>
                        <option <?= ($log['plant_status'] == 'Aushärten') ? 'selected' : '' ?>>Aushärten</option>
                    </select>
                </div>
            </div>

            <div class="mt-2">
                <label>Notizen:</label>
                <textarea name="notes" class="form-control"><?= $log['notes'] ?></textarea>
            </div><br>

            <button type="submit" class="btn btn-success btn-sm">Speichern</button>
            <a href="view_plant.php?id=<?= $_GET['id'] ?>" class="btn btn-secondary btn-sm">Zurück</a>
        </form>
    </div>
</body>

</html>

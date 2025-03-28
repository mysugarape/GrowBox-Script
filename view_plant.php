<?php
include __DIR__ . '/config/auth.php'; // Login-Schutz
include __DIR__ . '/config/db.php';

$plant_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM plants WHERE id = ?");
$stmt->execute([$plant_id]);
$plant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plant) {
    die("Pflanze nicht gefunden.");
}

// Einträge abrufen
$logs = $pdo->prepare("SELECT * FROM plant_logs WHERE plant_id = ? ORDER BY date DESC");
$logs->execute([$plant_id]);
$logs = $logs->fetchAll(PDO::FETCH_ASSOC);

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
    $water = $_POST['water']; // Neues Feld für Wasser
    $fertilizer = $_POST['fertilizer']; // Neues Feld für Dünger

    $stmt = $pdo->prepare("INSERT INTO plant_logs 
        (plant_id, height, temperature, humidity, root_temperature, ph_value, ec_value, air_humidity, light_hours, plant_status, notes, water, fertilizer) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$plant_id, $height, $temperature, $humidity, $root_temperature, $ph_value, $ec_value, $air_humidity, $light_hours, $plant_status, $notes, $water, $fertilizer]);
    header("Location: view_plant.php?id=" . $plant_id);
    exit;
    
}
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datensatz-ID aus dem Formular holen
    $log_id = $_POST['id'];

    // Sicherstellen, dass die ID existiert
    if ($log_id) {
        // SQL-Statement zum Löschen des Eintrags
        $stmt = $pdo->prepare("DELETE FROM plant_logs WHERE id = ?");
        $stmt->execute([$log_id]);
    }

    // Nach dem Löschen zurück zur Pflanzenübersicht
    header("Location: view_plant.php?id=" . $_GET['id']);
    exit;
}


// Setze die Zeitzone (Optional: auf die gewünschte Zeitzone, z. B. 'Europe/Berlin')
date_default_timezone_set('Europe/Berlin');

// Berechnung der vergangenen Tage für jede Pflanze individuell
$date_added = new DateTime($plant['date_added'], new DateTimeZone('Europe/Berlin')); // Erstelle DateTime-Objekt aus dem Datum und setze Zeitzone
$current_date = new DateTime('now', new DateTimeZone('Europe/Berlin')); // Aktuelles Datum mit Zeitzone
$diff = $current_date->diff($date_added); // Differenz berechnen
$days_diff = $diff->days; // Anzahl der Tage
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($plant['name']) ?> - Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .table-responsive {
            max-height: 300px;
            overflow-y: auto;
        }

        .table th,
        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 14px;
        }

        /* Zusätzliche Anpassungen für mobile Endgeräte */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.5rem;
            }

            .card-title {
                font-size: 1.2rem;
            }

            .form-control {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                font-size: 12px;
            }

            .mb-4.row p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h1 class="text-center text-wrap"><?= htmlspecialchars($plant['name']) ?> - <?= htmlspecialchars($plant['species']) ?></h1>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title text-wrap">Messwerte erfassen</h5>
                <form method="post">
                    <div class="row g-2">
                        <div class="col-md-4 col-6">
                            <label>Temperatur (°C):</label>
                            <input type="number" step="0.1" name="temperature" class="form-control">
                        </div>
                        <div class="col-md-4 col-6">
                            <label>Luftfeuchtigkeit (%):</label>
                            <input type="number" step="0.1" name="air_humidity" class="form-control">
                        </div>
                        <div class="col-md-4 col-6">
                            <label>Soil (%):</label>
                            <input type="number" step="0.1" name="humidity" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-4 col-6">
                            <label>Wurzeltemp (°C):</label>
                            <input type="number" step="0.1" name="root_temperature" class="form-control">
                        </div>
                        <div class="col-md-4 col-6">
                            <label>pH-Wert:</label>
                            <input type="number" step="0.1" name="ph_value" class="form-control">
                        </div>
                        <div class="col-md-4 col-6">
                            <label>EC-Wert (µS/cm):</label>
                            <input type="number" step="0.1" name="ec_value" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-4 col-6">
                            <label>Höhe (cm):</label>
                            <input type="number" step="0.1" name="height" class="form-control">
                        </div>
                        <div class="col-md-4 col-6">
                            <label>Lichtstunden/Tag:</label>
                            <select name="light_hours" class="form-control">
                                <option></option>
                                <option>18h</option>
                                <option>12h</option>
                                <option>Aus</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                            <label>Pflanzenstatus:</label>
                            <select name="plant_status" class="form-control">
                                <option>Sämling</option>
                                <option>Keimling</option>
                                <option>Vegetativ</option>
                                <option>Blüte</option>
                                <option>Ernte</option>
                                <option>Aushärten</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-4 col-6">
                            <label>Wasser:</label>
                            <select name="water" class="form-control">
                                <option></option>
                                <option>Ja</option>
                                <option>Nein</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                            <label>Dünger:</label>
                            <input type="text" name="fertilizer" class="form-control">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label>Notizen:</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div><br>
                    <button type="submit" class="btn btn-success btn-sm">Speichern</button>
                    <a href="index.php" class="btn btn-secondary btn-sm">Zurück</a>
                </form>
            </div>
        </div>
        <div class="mb-4 row text-center" style="margin-top: 10px; font-size: 0.7em;">
            <div class="col-md-2 col-6">
                <p><strong>Art:</strong> <?= htmlspecialchars($plant['species']) ?></p>
            </div>
            <div class="col-md-2 col-6">
                <p><strong>Datum hinzugefügt:</strong> <?= date("d.m.y", strtotime($plant['date_added'])) ?></p>
            </div>
            <div class="col-md-2 col-6">
                <p><strong>Dauer:</strong> <?= $days_diff ?> Tage</p>
            </div>
            <div class="col-md-2 col-6">
                <p><strong>Quelle:</strong> <?= htmlspecialchars($plant['source']) ?></p>
            </div>
        </div>
        <h2 class="mt-4 text-center">Letzte Messwerte</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Datum</th>
                        <th>Pflanzenhöhe (cm)</th>
                        <th>Lufttemperatur (°C)</th>
                        <th>Soil (%)</th>
                        <th>Wurzeltemperatur (°C)</th>
                        <th>pH-Wert</th>
                        <th>EC-Wert (µS/cm)</th>
                        <th>Luftfeuchtigkeit (%)</th>
                        <th>Lichtstunden pro Tag</th>
                        <th>Wasser benötigt</th>
                        <th>Dünger</th>
                        <th>Pflanzenstatus</th>
                        <th>Notizen</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= date("d.m.y", strtotime($log['date'])) ?></td>
                        <td><?= $log['height'] ?> cm</td>
                        <td><?= $log['temperature'] ?>°C</td>
                        <td><?= $log['humidity'] ?>%</td>
                        <td><?= $log['root_temperature'] ?>°C</td>
                        <td><?= $log['ph_value'] ?></td>
                        <td><?= $log['ec_value'] ?> µS/cm</td>
                        <td><?= $log['air_humidity'] ?>%</td>
                        <td><?= $log['light_hours'] ?>/h</td>
                        <td><?= htmlspecialchars($log['water'] ?? 'Nicht angegeben') ?></td>
                        <td><?= htmlspecialchars($log['fertilizer'] ?? 'Nicht angegeben') ?></td>
                        <td><?= htmlspecialchars($log['plant_status']) ?></td>
                        <td><?= htmlspecialchars($log['notes']) ?></td>
                        <td>
                            <a href="edit_log.php?id=<?= $log['id'] ?>" class="btn btn-success btn-sm" style="font-size: 10px;">Bearbeiten</a>
                            <form action="delete_log.php?id=<?= $plant_id ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $log['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" style="font-size: 10px;" onclick="return confirm('Möchten Sie diesen Eintrag wirklich löschen?');">Löschen</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

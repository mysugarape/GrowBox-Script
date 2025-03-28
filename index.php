<?php

include __DIR__ . '/config/auth.php'; // Schutz hinzufügen
include __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT id, name, species, source, date_added FROM plants");
$plants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Setze die Zeitzone (Optional: auf die gewünschte Zeitzone, z. B. 'Europe/Berlin')
date_default_timezone_set('Europe/Berlin');
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Pflanzentagebuch</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsives Design aktivieren -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .table-container {
            max-height: 400px;
            overflow-x: auto;
            overflow-y: auto;
        }

        .table {
            font-size: 0.9em;
        }

        th,
        td {
            white-space: nowrap;
            padding: 8px;
        }

        /* Medienabfrage für kleinere Bildschirme */
        @media (max-width: 768px) {
            .table-container {
                max-height: none; /* Begrenzung der Höhe aufheben */
            }

            .table {
                font-size: 0.8em; /* Schriftgröße leicht reduzieren */
            }

            th,
            td {
                padding: 6px; /* Weniger Padding für kompaktere Darstellung */
            }

            h2 {
                font-size: 1.5rem; /* Überschrift anpassen */
            }

            .btn {
                font-size: 0.8rem; /* Button-Größe anpassen */
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2>Pflanzenübersicht</h2><br>

        <!-- Erfolgsnachricht -->
        <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>

        <!-- Container für die Tabelle -->
        <div class="table-container" style="border: 1px solid #dedede;">
            <table class="table table-striped table-responsive"> <!-- Responsiv-Klasse hinzugefügt -->
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Art</th>
                        <th>Quelle</th>
                        <th>Start</th>
                        <th>Vergangene Tage</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plants as $plant): ?>
                    <?php
                        $date_added = new DateTime($plant['date_added'], new DateTimeZone('Europe/Berlin'));
                        $current_date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
                        $diff = $current_date->diff($date_added);
                        $days_diff = $diff->days;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($plant['name']) ?></td>
                        <td><?= htmlspecialchars($plant['species']) ?></td>
                        <td><?= htmlspecialchars($plant['source']) ?></td>
                        <td><?= date("d.m.y", strtotime($plant['date_added'])) ?></td>
                        <td><?= $days_diff ?> Tage</td>
                        <td>
                            <a href="view_plant.php?id=<?= $plant['id'] ?>" class="btn btn-primary btn-sm">Details</a>
                            <a href="edit_plant.php?id=<?= $plant['id'] ?>" class="btn btn-secondary btn-sm">Bearbeiten</a>
                            <a href="delete_plant.php?id=<?= $plant['id'] ?>" class="btn btn-danger btn-sm">Löschen</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div><br>
        <a href="add_plant.php" class="btn btn-success btn-sm">Pflanze hinzufügen</a>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>

</html>

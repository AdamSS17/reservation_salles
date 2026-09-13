<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/database.php';

$files = glob(__DIR__ . '/migrations/*.php');

sort($files);

if (empty($files)) {
    echo "Aucune migration trouvée.\n";
    exit(0);
}

foreach ($files as $file) {
    require_once $file;

    $filename = basename($file);

    if ($filename === '001_create_salles_table.php') {
        $migration = new CreateSalleTable();
    } elseif ($filename === '002_create_reservations_table.php') {
        $migration = new CreateReservationTable();
    } else {
        echo "Migration inconnue : {$filename}\n";
        continue;
    }

    $migration->up();

    echo "Migration {$filename} exécutée.\n";
}

echo "Migrations terminées.\n";
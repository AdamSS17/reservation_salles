<?php

declare(strict_types=1);

/**
 * Exécute toutes les migrations de database/migrations/, dans l'ordre
 * alphabétique (d'où le préfixe numérique 001_, 002_...).
 *
 * Usage : php database/migrate.php
 */

require dirname(__DIR__) . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$migrationsDir = __DIR__ . '/migrations';
$files = glob($migrationsDir . '/*.php');
sort($files);

if (empty($files)) {
    echo "Aucune migration trouvée dans $migrationsDir\n";
    exit(0);
}

foreach ($files as $file) {
    $migration = require $file;
    $table = $migration['table'];

    if (Capsule::schema()->hasTable($table)) {
        echo "Table '$table' déjà existante, ignorée.\n";
        continue;
    }

    ($migration['up'])();
    echo "Table '$table' créée.\n";
}

echo "Migrations terminées.\n";
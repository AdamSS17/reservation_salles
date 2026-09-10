<?php
declare(strict_types=1);

require dirname(__DIR__) . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$migrationsDir = __DIR__ . '/migrations';
$files = glob($migrationsDir . '/*.php');
sort($files);

if (empty($files)) {
    echo "Aucune migration trouvée\n";
    exit(0);
}

foreach ($files as $file) {
    // On charge le fichier qui déclare la classe
    require_once $file;

    // On récupère toutes les classes déclarées après le require
    $classes = get_declared_classes();
    $lastClass = end($classes);

    // Si le fichier ne déclare pas de classe, on skip
    if (!class_exists($lastClass)) {
        echo "Pas de classe dans $file, ignoré\n";
        continue;
    }

    $instance = new $lastClass();

    if (!method_exists($instance, 'up')) {
        echo "Classe $lastClass sans méthode up(), ignorée\n";
        continue;
    }

    // On vérifie si la table existe déjà pour ne pas recréer
    // On déduit le nom de la table depuis la classe
    $tableName = str_contains(strtolower($lastClass), 'salle') ? 'salles' : 'reservations';
    // Pour être sûr, on check les 2 conventions
    if (Capsule::schema()->hasTable('salle') || Capsule::schema()->hasTable('salles')) {
        if (str_contains(strtolower($lastClass), 'salle')) {
            echo "Table salles/salle déjà existante, ignorée.\n";
            continue;
        }
    }
    if (Capsule::schema()->hasTable('reservation') || Capsule::schema()->hasTable('reservations')) {
        if (str_contains(strtolower($lastClass), 'reservation')) {
            echo "Table reservations/reservation déjà existante, ignorée.\n";
            continue;
        }
    }

    $instance->up();
    echo "Migration $lastClass exécutée depuis $file\n";
}

echo "Migrations terminées.\n";
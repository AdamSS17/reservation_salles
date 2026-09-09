<?php

declare(strict_types=1);
use App\Application;

$app = new Application();
echo $app->app();



/**
 * Point d'entrée UNIQUE de l'application (contrainte du brief, section 6).
 *
 * Pour l'instant : un stub qui vérifie juste que Nginx -> PHP-FPM -> Composer
 * -> Eloquent -> MySQL fonctionnent ensemble. À l'Étape 11, ce fichier
 * sera remplacé par la vraie résolution FastRoute + PHP-DI décrite dans
 * le brief.
 */

require dirname(__DIR__) . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$version = Capsule::connection()->select('select version() as v')[0]->v;

echo "OK — Nginx + PHP-FPM + MySQL (Docker) fonctionnent ensemble.<br>";
echo "Version MySQL : " . htmlspecialchars($version);
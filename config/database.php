<?php

declare(strict_types=1);

/**
 * Point UNIQUE de configuration de la base de données.
 *
 * Contrainte du brief (Étape 2) : aucune classe métier ne doit appeler
 * getenv() directement. C'est ce fichier, et lui seul, qui lit les
 * variables d'environnement et configure Eloquent. Le reste de
 * l'application reçoit ensuite Capsule\Manager (ou les modèles Eloquent
 * déjà connectés) via le conteneur d'injection (Étape 11).
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

// 1. Charger les variables d'environnement (une seule fois)
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad(); // safeLoad: ne plante pas si .env absent (utile si les
                      // variables sont déjà injectées par l'environnement,
                      // par exemple via Docker)

// 2. Configurer Capsule\Manager
$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => $_ENV['DB_DRIVER']   ?? getenv('DB_DRIVER'),
    'host'      => $_ENV['DB_HOST']     ?? getenv('DB_HOST'),
    'port'      => $_ENV['DB_PORT']     ?? getenv('DB_PORT'),
    'database'  => $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE'),
    'username'  => $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME'),
    'password'  => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD'),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// 3. Démarrer Eloquent
$capsule->setAsGlobal();
$capsule->bootEloquent();

// 4. Vérifier la connexion
try {
    $capsule->getConnection()->getPdo();
} catch (\PDOException $e) {
    http_response_code(500);
    die(
        "Erreur de connexion à la base de données : " . $e->getMessage() . "\n" .
        "Vérifie que le conteneur MySQL tourne (docker compose up -d) " .
        "et que les valeurs de .env sont correctes.\n"
    );
}

return $capsule;



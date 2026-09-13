<?php

declare(strict_types=1);

session_start();

use App\Application;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;

require dirname(__DIR__) . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(
    dirname(__DIR__) . '/config/container.php'
);
$container = $builder->build();

// Force la résolution de Capsule maintenant, ce qui exécute la factory
// (config/database.php) et démarre réellement Eloquent — sinon rien ne
// la déclenche automatiquement tant qu'aucune classe ne la demande
// explicitement dans son constructeur.
$container->get(Capsule::class);

$application = $container->get(Application::class);
$application->run();


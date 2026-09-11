<?php

declare(strict_types=1);

use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\factory;

return [
    // Capsule\Manager : nécessite une factory, car sa construction implique
    // plusieurs étapes (addConnection, setAsGlobal, bootEloquent) — pas
    // juste un "new Capsule()" simple que l'autowiring pourrait deviner.
    Capsule::class => factory(function (): Capsule {
        // Réutilise exactement la même logique que config/database.php,
        // pour ne jamais dupliquer la configuration de connexion
        // (contrainte du brief : "la connexion doit être configurée
        // une seule fois").
        return require dirname(__DIR__) . '/config/database.php';
    }),

    // Interfaces -> implémentations concrètes : PHP-DI ne peut pas deviner
    // tout seul QUELLE implémentation utiliser pour une interface, il faut
    // le lui dire explicitement.
    SalleRepositoryInterface::class =>
        autowire(EloquentSalleRepository::class),

    ReservationRepositoryInterface::class =>
        autowire(EloquentReservationRepository::class),

    // Tout le reste (SalleValidator, ReservationValidator,
    // CreerReservationService, AnnulerReservationService,
    // SalleController, ReservationController) n'a PAS besoin d'être
    // listé ici : PHP-DI utilise l'autowiring automatique pour les
    // classes concrètes — il lit leur constructeur et résout chaque
    // dépendance tout seul, récursivement.
];
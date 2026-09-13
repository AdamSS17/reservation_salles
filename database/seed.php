<?php

declare(strict_types=1);

/**
 * Insère les données initiales (au moins 5 salles).
 * Peut être exécuté plusieurs fois sans créer de doublons
 * (utilise firstOrCreate, basé sur le nom de la salle).
 *
 * Usage : docker compose exec php php database/seed.php
 */

require dirname(__DIR__) . '/config/database.php';

use App\Model\Salle;

$salles = [
    [
        'nom'      => 'Amphithéâtre A',
        'batiment' => 'Bâtiment principal',
        'capacite' => 250,
        'type'     => 'amphitheatre',
        'active'   => true,
    ],
    [
        'nom'      => 'Salle B12',
        'batiment' => 'Bâtiment B',
        'capacite' => 40,
        'type'     => 'cours',
        'active'   => true,
    ],
    [
        'nom'      => 'Laboratoire Chimie',
        'batiment' => 'Bâtiment Sciences',
        'capacite' => 24,
        'type'     => 'laboratoire',
        'active'   => true,
    ],
    [
        'nom'      => 'Salle Informatique 1',
        'batiment' => 'Bâtiment C',
        'capacite' => 30,
        'type'     => 'informatique',
        'active'   => true,
    ],
    [
        'nom'      => 'Salle de réunion',
        'batiment' => 'Administration',
        'capacite' => 12,
        'type'     => 'reunion',
        'active'   => true,
    ],
];

$creees = 0;
$existantes = 0;

foreach ($salles as $donnees) {
    // firstOrCreate : cherche d'abord une salle avec ce "nom" ; si elle
    // existe déjà, ne fait rien (retourne l'existante) ; sinon, la crée
    // avec les données fournies. C'est ce qui rend le script rejouable
    // sans dupliquer les lignes.
    $salle = Salle::firstOrCreate(
        ['nom' => $donnees['nom']],
        $donnees
    );

    if ($salle->wasRecentlyCreated) {
        echo "Créée : {$salle->nom}\n";
        $creees++;
    } else {
        echo "Déjà existante, ignorée : {$salle->nom}\n";
        $existantes++;
    }
}

echo "\n$creees salle(s) créée(s), $existantes déjà présente(s).\n";
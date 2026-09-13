<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\View;

final class SalleController
{
    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        private readonly SalleValidator $validator,
    ) {
    }

    public function index(): string
    {
        return View::renderAvecLayout('salle/index', [
            'salles' => $this->salles->lister(),
        ], 'Liste des salles');
    }

    public function show(int $id): string
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            http_response_code(404);
            return View::renderAvecLayout('error/404', [], 'Salle introuvable');
        }

        return View::renderAvecLayout('salle/show', [
            'salle' => $salle,
        ], $salle->nom);
    }

    public function create(): string
    {
        return View::renderAvecLayout('salle/form', [
            'salle'  => null,
            'errors' => [],
            'old'    => [],
        ], 'Ajouter une salle');
    }

    public function store(): void
    {
        // 1. Lire les données HTTP
        $data = [
            'nom'      => $_POST['nom'] ?? '',
            'batiment' => $_POST['batiment'] ?? '',
            'capacite' => (int) ($_POST['capacite'] ?? 0),
            'type'     => $_POST['type'] ?? '',
            'active'   => isset($_POST['active']),
        ];

        // 2. Valider
        $resultat = $this->validator->validate($data);

        // 3. Réafficher le formulaire en cas d'erreur, en conservant
        //    les valeurs saisies (contrainte du brief, scénario 6)
        if (!$resultat->isValid()) {
            echo View::renderAvecLayout('salle/form', [
                'salle'  => null,
                'errors' => $resultat->errors(),
                'old'    => $data,
            ], 'Ajouter une salle');
            return;
        }

        // 4. Construire le DTO
        $dto = CreerSalleDTO::depuisTableau($resultat->data());

        // 5. Persister via le repository (pas d'appel ORM direct ici)
        $salle = new \App\Model\Salle([
            'nom'      => $dto->nom,
            'batiment' => $dto->batiment,
            'capacite' => $dto->capacite,
            'type'     => $dto->type,
            'active'   => $dto->active,
        ]);
        $this->salles->enregistrer($salle);

        // 6. Rediriger après succès
        $_SESSION['succes'] = 'Salle créée avec succès.';
        header('Location: /salles/' . $salle->id);
        exit;
    }

    public function edit(int $id): string
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            http_response_code(404);
            return View::renderAvecLayout('error/404', [], 'Salle introuvable');
        }

        return View::renderAvecLayout('salle/form', [
            'salle'  => $salle,
            'errors' => [],
            'old'    => [],
        ], 'Modifier ' . $salle->nom);
    }

    public function update(int $id): void
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            http_response_code(404);
            echo View::renderAvecLayout('error/404', [], 'Salle introuvable');
            return;
        }

        $data = [
            'nom'      => $_POST['nom'] ?? '',
            'batiment' => $_POST['batiment'] ?? '',
            'capacite' => (int) ($_POST['capacite'] ?? 0),
            'type'     => $_POST['type'] ?? '',
            'active'   => isset($_POST['active']),
        ];

        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            echo View::renderAvecLayout('salle/form', [
                'salle'  => $salle,
                'errors' => $resultat->errors(),
                'old'    => $data,
            ], 'Modifier ' . $salle->nom);
            return;
        }

        $salle->fill($resultat->data());
        $this->salles->enregistrer($salle);

        $_SESSION['succes'] = 'Salle modifiée avec succès.';
        header('Location: /salles/' . $salle->id);
        exit;
    }
}
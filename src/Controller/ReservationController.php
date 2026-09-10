<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Exception\ReservationInvalideException;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\View\View;

final class ReservationController
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly SalleRepositoryInterface $salles,
        private readonly ReservationValidator $validator,
        private readonly CreerReservationService $creerService,
        private readonly AnnulerReservationService $annulerService,
    ) {
    }

    public function index(): string
    {
        $salleId = isset($_GET['salle_id']) ? (int) $_GET['salle_id'] : null;

        $reservations = $salleId !== null
            ? $this->reservations->listerParSalle($salleId)
            : $this->reservations->lister();

        return View::renderAvecLayout('reservation/index', [
            'reservations' => $reservations,
        ], 'Réservations');
    }

    public function show(int $id): string
    {
        $reservation = $this->reservations->trouver($id);

        if ($reservation === null) {
            http_response_code(404);
            return View::renderAvecLayout('error/404', [], 'Réservation introuvable');
        }

        return View::renderAvecLayout('reservation/show', [
            'reservation' => $reservation,
        ], 'Réservation #' . $reservation->id);
    }

    public function create(): string
    {
        return View::renderAvecLayout('reservation/form', [
            'salles' => $this->salles->lister(),
            'errors' => [],
            'old'    => [],
        ], 'Nouvelle réservation');
    }

    public function store(): void
    {
        // 1. Lire les données HTTP
        $data = [
            'salle_id'    => $_POST['salle_id'] ?? '',
            'responsable' => $_POST['responsable'] ?? '',
            'email'       => $_POST['email'] ?? '',
            'motif'       => $_POST['motif'] ?? '',
            'date_debut'  => $_POST['date_debut'] ?? '',
            'date_fin'    => $_POST['date_fin'] ?? '',
        ];

        // 2. Valider (syntaxe uniquement — pas les règles métier)
        $resultat = $this->validator->validate($data);

        // 3. Réafficher le formulaire en cas d'erreur de validation
        if (!$resultat->isValid()) {
            echo View::renderAvecLayout('reservation/form', [
                'salles' => $this->salles->lister(),
                'errors' => $resultat->errors(),
                'old'    => $data,
            ], 'Nouvelle réservation');
            return;
        }

        // 4. Construire le DTO
        $dto = CreerReservationDTO::depuisTableau($resultat->data());

        // 5. Appeler le service (règles métier : chevauchement, durée, etc.)
        try {
            $reservation = $this->creerService->creer($dto);
        } catch (SalleIndisponibleException|ReservationInvalideException $e) {
            echo View::renderAvecLayout('reservation/form', [
                'salles' => $this->salles->lister(),
                'errors' => ['general' => [$e->getMessage()]],
                'old'    => $data,
            ], 'Nouvelle réservation');
            return;
        }

        // 6. Rediriger après succès
        $_SESSION['succes'] = 'Réservation confirmée.';
        header('Location: /reservations/' . $reservation->id);
        exit;
    }

    public function cancel(int $id): void
    {
        try {
            $this->annulerService->annuler($id);
            $_SESSION['succes'] = 'Réservation annulée.';
        } catch (ReservationIntrouvableException $e) {
            http_response_code(404);
            echo View::renderAvecLayout('error/404', [], 'Réservation introuvable');
            return;
        }

        header('Location: /reservations');
        exit;
    }
}

// fichier qui gere ecoute les exception cette classe tete les type dexeption pour les gererles try catch
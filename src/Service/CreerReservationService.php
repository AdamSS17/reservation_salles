<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\Regle\DateFuturRegle;
use App\Service\Regle\DureeMaxRegle;
use App\Service\Regle\OrdreDatesRegle;
use App\Service\Regle\PasDeChevauchementRegle;
use App\Service\Regle\RegleReservationInterface;
use App\Service\Regle\SalleActiveRegle;
use App\Service\Regle\SalleExisteRegle;

final class CreerReservationService
{
    /** @var RegleReservationInterface[] */
    private readonly array $regles;

    /**
     * @param RegleReservationInterface[]|null $regles Permet d'injecter
     *   un jeu de règles personnalisé (utile pour les tests, ou pour
     *   PHP-DI à l'Étape 11). Si non fourni, utilise le jeu par défaut,
     *   dans un ordre précis et volontaire :
     *   - SalleExisteRegle et SalleActiveRegle doivent passer AVANT
     *     PasDeChevauchementRegle (pas la peine de chercher un conflit
     *     sur une salle qui n'existe même pas).
     */
    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        private readonly ReservationRepositoryInterface $reservations,
        ?array $regles = null,
    ) {
        $this->regles = $regles ?? [
            new SalleExisteRegle(),
            new SalleActiveRegle(),
            new OrdreDatesRegle(),
            new DureeMaxRegle(),
            new DateFuturRegle(),
            new PasDeChevauchementRegle(),
        ];
    }

    public function creer(CreerReservationDTO $dto): Reservation
    {
        $salle = $this->salles->trouver($dto->salleId);

        // Chaque règle est appliquée dans l'ordre. La première qui
        // échoue lève son exception, ce qui interrompt naturellement
        // la boucle (aucune règle suivante ne s'exécute).
        foreach ($this->regles as $regle) {
            $regle->verifier($dto, $salle, $this->reservations);
        }

        $reservation = new Reservation([
            'salle_id'    => $dto->salleId,
            'responsable' => $dto->responsable,
            'email'       => $dto->email,
            'motif'       => $dto->motif,
            'date_debut'  => $dto->dateDebut,
            'date_fin'    => $dto->dateFin,
            'statut'      => 'confirmée',
        ]);

        return $this->reservations->enregistrer($reservation);
    }
}
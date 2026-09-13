<?php

declare(strict_types=1);

namespace App\Service\Regle;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

final class PasDeChevauchementRegle implements RegleReservationInterface
{
    public function verifier(CreerReservationDTO $dto, ?Salle $salle, ReservationRepositoryInterface $reservations): void
    {
        $conflit = $reservations->trouverConflit($dto->salleId, $dto->dateDebut, $dto->dateFin);

        if ($conflit !== null) {
            throw new SalleIndisponibleException("La salle est indisponible pendant cette période.");
        }
    }
}
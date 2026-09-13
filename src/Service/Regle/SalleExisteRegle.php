<?php

declare(strict_types=1);

namespace App\Service\Regle;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

final class SalleExisteRegle implements RegleReservationInterface
{
    public function verifier(CreerReservationDTO $dto, ?Salle $salle, ReservationRepositoryInterface $reservations): void
    {
        if ($salle === null) {
            throw new SalleIndisponibleException("La salle #{$dto->salleId} n'existe pas.");
        }
    }
}
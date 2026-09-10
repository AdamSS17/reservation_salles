<?php

declare(strict_types=1);

namespace App\Service\Regle;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationInvalideException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

final class OrdreDatesRegle implements RegleReservationInterface
{
    public function verifier(CreerReservationDTO $dto, ?Salle $salle, ReservationRepositoryInterface $reservations): void
    {
        if ($dto->dateDebut >= $dto->dateFin) {
            throw new ReservationInvalideException("La date de début doit précéder la date de fin.");
        }
    }
}
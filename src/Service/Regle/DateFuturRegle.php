<?php

declare(strict_types=1);

namespace App\Service\Regle;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationInvalideException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use DateTimeImmutable;

final class DateFuturRegle implements RegleReservationInterface
{
    public function verifier(CreerReservationDTO $dto, ?Salle $salle, ReservationRepositoryInterface $reservations): void
    {
        if ($dto->dateDebut <= new DateTimeImmutable()) {
            throw new ReservationInvalideException("La réservation doit commencer dans le futur.");
        }
    }
}
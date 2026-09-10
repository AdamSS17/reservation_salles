<?php

declare(strict_types=1);

namespace App\Service\Regle;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationInvalideException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

final class DureeMaxRegle implements RegleReservationInterface
{
    private const DUREE_MAX_HEURES = 4;

    public function verifier(CreerReservationDTO $dto, ?Salle $salle, ReservationRepositoryInterface $reservations): void
    {
        $dureeEnHeures = ($dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp()) / 3600;

        if ($dureeEnHeures > self::DUREE_MAX_HEURES) {
            throw new ReservationInvalideException("Une réservation ne peut pas dépasser quatre heures.");
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Service\Regle;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

final class SalleActiveRegle implements RegleReservationInterface
{
    public function verifier(CreerReservationDTO $dto, ?Salle $salle, ReservationRepositoryInterface $reservations): void
    {
        // $salle est garanti non-null ici : SalleExisteRegle s'exécute
        // avant et interrompt la chaîne (via exception) si null.
        if (!$salle->active) {
            throw new SalleIndisponibleException("Cette salle ne peut pas être réservée.");
        }
    }
}
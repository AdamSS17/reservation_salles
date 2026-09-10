<?php

declare(strict_types=1);

namespace App\Service\Regle;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

/**
 * Une "stratégie" = une règle métier unique, isolée, qui sait
 * seulement dire "cette réservation est-elle acceptable de MON point
 * de vue à moi ?". Si non, elle lève l'exception appropriée.
 * Le service qui les orchestre n'a besoin de connaître aucune d'entre
 * elles individuellement — juste cette interface commune.
 */
interface RegleReservationInterface
{
    /**
     * @throws \App\Exception\SalleIndisponibleException
     * @throws \App\Exception\ReservationInvalideException
     */
    public function verifier(
        CreerReservationDTO $dto,
        ?Salle $salle,
        ReservationRepositoryInterface $reservations
    ): void;
}
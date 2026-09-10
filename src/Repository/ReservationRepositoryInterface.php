<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use DateTimeImmutable;

interface ReservationRepositoryInterface
{
    /**
     * @return Reservation[]
     */
    public function lister(): array;

    /**
     * @return Reservation[]
     */
    public function listerParSalle(int $salleId): array;

    public function trouver(int $id): ?Reservation;

    /**
     * Cherche une réservation CONFIRMÉE existante qui chevauche la
     * période donnée, pour une salle précise. Retourne null si aucun
     * conflit (donc la période est libre).
     */
    public function trouverConflit(
        int $salleId,
        DateTimeImmutable $debut,
        DateTimeImmutable $fin
    ): ?Reservation;

    public function enregistrer(Reservation $reservation): Reservation;

    public function annuler(Reservation $reservation): Reservation;
}
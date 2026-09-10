<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use DateTimeImmutable;

final class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function lister(): array
    {
        return Reservation::all()->all();
    }

    public function listerParSalle(int $salleId): array
    {
        return Reservation::where('salle_id', $salleId)->get()->all();
    }

    public function trouver(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function trouverConflit(
        int $salleId,
        DateTimeImmutable $debut,
        DateTimeImmutable $fin
    ): ?Reservation {
        // Rappel de la règle de chevauchement du brief :
        //   nouveauDebut < reservationExistante.dateFin
        //   ET
        //   nouvelleFin > reservationExistante.dateDebut
        return Reservation::where('salle_id', $salleId)
            ->where('statut', 'confirmée')
            ->where('date_debut', '<', $fin->format('Y-m-d H:i:s'))
            ->where('date_fin', '>', $debut->format('Y-m-d H:i:s'))
            ->first();
    }

    public function enregistrer(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulée';
        $reservation->save();

        return $reservation;
    }
}
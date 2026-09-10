<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

final class CreerReservationDTO
{
    public function __construct(
        public readonly int $salleId,
        public readonly string $responsable,
        public readonly string $email,
        public readonly string $motif,
        public readonly DateTimeImmutable $dateDebut,
        public readonly DateTimeImmutable $dateFin,
    ) {
    }

    public static function depuisTableau(array $data): self
    {
        return new self(
            salleId: (int) $data['salle_id'],
            responsable: $data['responsable'],
            email: $data['email'],
            motif: $data['motif'],
            // C'est ICI que les chaînes deviennent de vraies dates —
            // à partir de maintenant, plus de manipulation de chaînes
            // pour les comparaisons temporelles, uniquement des objets.
            dateDebut: new DateTimeImmutable($data['date_debut']),
            dateFin: new DateTimeImmutable($data['date_fin']),
        );
    }
}
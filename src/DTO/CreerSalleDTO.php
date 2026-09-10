<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Objet de transport pour la création d'une salle.
 * Contient des données déjà typées et validées — jamais $_POST brut.
 */
final class CreerSalleDTO
{
    public function __construct(
        public readonly string $nom,
        public readonly string $batiment,
        public readonly int $capacite,
        public readonly string $type,
        public readonly bool $active,
    ) {
    }

    /**
     * Construit le DTO à partir des données déjà validées
     * (ValidationResult::data(), jamais $_POST directement).
     */
    public static function depuisTableau(array $data): self
    {
        return new self(
            nom: $data['nom'],
            batiment: $data['batiment'],
            capacite: (int) $data['capacite'],
            type: $data['type'],
            active: (bool) $data['active'],
        );
    }
}
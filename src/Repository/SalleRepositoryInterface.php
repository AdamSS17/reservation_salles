<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Salle;

interface SalleRepositoryInterface
{
    /**
     * @return Salle[]
     */
    public function lister(): array;

    public function trouver(int $id): ?Salle;

    public function enregistrer(Salle $salle): Salle;
}
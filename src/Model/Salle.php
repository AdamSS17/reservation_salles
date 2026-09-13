<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    /**
     * Nom de la table. Explicite plutôt que de laisser Eloquent le
     * déduire automatiquement (il aurait deviné "salles" correctement
     * ici, mais mieux vaut ne pas dépendre d'une déduction implicite
     * sur un mot français).
     */
    protected $table = 'salle';

    /**
     * Propriétés qu'on autorise à être remplies en masse
     * (ex: Salle::create([...]), Salle::fill([...])).
     * Sans ça, Eloquent refuse par sécurité (protection contre
     * l'assignation de masse non voulue, ex: un champ "id" ou
     * "active" injecté depuis un formulaire malveillant).
     */
    protected $fillable = [
        'nom',
        'batiment',
        'capacite',
        'type',
        'active',
    ];

    /**
     * Conversions automatiques de types : la base stocke des chaînes/
     * entiers bruts, mais on veut manipuler de vrais types PHP.
     */
    protected $casts = [
        'capacite' => 'integer',
        'active'   => 'boolean',
    ];

    /**
     * Une salle possède plusieurs réservations.
     * Clé étrangère : salle_id (sur la table reservations).
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'salle_id');
    }
}


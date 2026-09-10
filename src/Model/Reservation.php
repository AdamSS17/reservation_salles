<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $table = 'reservation';

    protected $fillable = [
        'salle_id',
        'responsable',
        'email',
        'motif',
        'date_debut',
        'date_fin',
        'statut',
    ];

    /**
     * date_debut et date_fin sont converties en objets DateTime PHP
     * (Illuminate\Support\Carbon exactement) au lieu de rester de
     * simples chaînes de caractères. Ça permet d'écrire par exemple
     * $reservation->date_debut->format('d/m/Y H:i') ou de comparer
     * deux dates avec ->lt()/->gt() plutôt qu'avec strtotime().
     *
     * created_at et updated_at sont déjà castées en dates
     * automatiquement par Eloquent, pas besoin de les répéter ici.
     * test dintegratiion eloquent(connexion base de donnee)
     */
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
        'salle_id'   => 'integer',
    ];

    /**
     * Une réservation appartient à une seule salle.
     * Clé étrangère : salle_id (sur la table reservations elle-même).
     */
    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }
}

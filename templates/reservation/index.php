<?php
/** @var \App\Model\Reservation[] $reservations */
use App\View\View;
?>
<h1>Réservations</h1>
<a href="/reservations/create">Nouvelle réservation</a>
<ul>
    <?php foreach ($reservations as $reservation): ?>
        <li>
            <a href="/reservations/<?= View::h($reservation->id) ?>">
                <?= View::h($reservation->responsable) ?> —
                <?= View::h($reservation->salle->nom) ?> —
                <?= View::h($reservation->date_debut->format('d/m/Y H:i')) ?>
            </a>
            [<?= View::h($reservation->statut) ?>]
        </li>
    <?php endforeach; ?>
</ul>
<?php
/** @var \App\Model\Reservation $reservation */
use App\View\View;
?>
<h1>Réservation #<?= View::h($reservation->id) ?></h1>
<p>Salle : <?= View::h($reservation->salle->nom) ?></p>
<p>Responsable : <?= View::h($reservation->responsable) ?></p>
<p>Email : <?= View::h($reservation->email) ?></p>
<p>Motif : <?= View::h($reservation->motif) ?></p>
<p>Du <?= View::h($reservation->date_debut->format('d/m/Y H:i')) ?>
   au <?= View::h($reservation->date_fin->format('d/m/Y H:i')) ?></p>
<p>Statut : <?= View::h($reservation->statut) ?></p>

<?php if ($reservation->statut === 'confirmée'): ?>
    <form method="post" action="/reservations/<?= View::h($reservation->id) ?>/cancel">
        <button type="submit">Annuler cette réservation</button>
    </form>
<?php endif; ?>
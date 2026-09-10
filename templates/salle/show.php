<?php
/** @var \App\Model\Salle $salle */
use App\View\View;
?>
<h1><?= View::h($salle->nom) ?></h1>
<p>Bâtiment : <?= View::h($salle->batiment) ?></p>
<p>Capacité : <?= View::h($salle->capacite) ?> places</p>
<p>Type : <?= View::h($salle->type) ?></p>
<p>Statut : <?= $salle->active ? 'Active' : 'Inactive' ?></p>
<a href="/salles/<?= View::h($salle->id) ?>/edit">Modifier</a>
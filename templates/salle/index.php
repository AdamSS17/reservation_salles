<?php
/** @var \App\Model\Salle[] $salles */
use App\View\View;
?>
<h1>Salles</h1>
<a href="/salles/create">Ajouter une salle</a>
<ul>
    <?php foreach ($salles as $salle): ?>
        <li>
            <a href="/salles/<?= View::h($salle->id) ?>"><?= View::h($salle->nom) ?></a>
            — <?= View::h($salle->batiment) ?> (<?= View::h($salle->capacite) ?> places)
            <?php if (!$salle->active): ?><em>[inactive]</em><?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
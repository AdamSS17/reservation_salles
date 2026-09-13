<?php
/** @var string[] $methodesAutorisees */
use App\View\View;
?>
<h1>405 — Méthode non autorisée</h1>
<p>Cette action ne peut pas être appelée avec cette méthode HTTP.</p>
<?php if (!empty($methodesAutorisees)): ?>
    <p>Méthodes autorisées : <?= View::h(implode(', ', $methodesAutorisees)) ?></p>
<?php endif; ?>
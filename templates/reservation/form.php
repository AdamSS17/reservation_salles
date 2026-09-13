<?php
/** @var \App\Model\Salle[] $salles */
/** @var array<string, string[]> $errors */
/** @var array $old */
use App\View\View;
?>
<h1>Nouvelle réservation</h1>

<?php if (!empty($errors['general'])): ?>
<p class="erreur"><?= View::h($errors['general'][0]) ?></p>
<?php endif; ?>

<form method="post" action="/reservations">
<label>
        Salle
<select name="salle_id">
<?php foreach ($salles as $salle): ?>
<option value="<?= View::h($salle->id) ?>" <?= ($old['salle_id'] ?? '') == $salle->id ? 'selected' : '' ?>>
<?= View::h($salle->nom) ?>
</option>
<?php endforeach; ?>
</select>
</label>
<?php if (!empty($errors['salle_id'])): ?>
<p class="erreur"><?= View::h($errors['salle_id'][0]) ?></p>
<?php endif; ?>

<label>
        Responsable
<input type="text" name="responsable" value="<?= View::h($old['responsable'] ?? '') ?>">
</label>
<?php if (!empty($errors['responsable'])): ?>
<p class="erreur"><?= View::h($errors['responsable'][0]) ?></p>
<?php endif; ?>

<label>
        Email
<input type="email" name="email" value="<?= View::h($old['email'] ?? '') ?>">
</label>
<?php if (!empty($errors['email'])): ?>
<p class="erreur"><?= View::h($errors['email'][0]) ?></p>
<?php endif; ?>

<label>
        Motif
<textarea name="motif"><?= View::h($old['motif'] ?? '') ?></textarea>
</label>
<?php if (!empty($errors['motif'])): ?>
<p class="erreur"><?= View::h($errors['motif'][0]) ?></p>
<?php endif; ?>

<label>
        Début
<input type="datetime-local" name="date_debut" value="<?= View::h($old['date_debut'] ?? '') ?>">
</label>
<?php if (!empty($errors['date_debut'])): ?>
<p class="erreur"><?= View::h($errors['date_debut'][0]) ?></p>
<?php endif; ?>

<label>
        Fin
<input type="datetime-local" name="date_fin" value="<?= View::h($old['date_fin'] ?? '') ?>">
</label>
<?php if (!empty($errors['date_fin'])): ?>
<p class="erreur"><?= View::h($errors['date_fin'][0]) ?></p>
<?php endif; ?>

<button type="submit">Réserver</button>
</form>
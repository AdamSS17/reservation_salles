<?php
/** @var \App\Model\Salle|null $salle */
/** @var array<string, string[]> $errors */
/** @var array $old */
use App\View\View;

$valeur = fn(string $champ, mixed $defaut = '') =>
    $old[$champ] ?? ($salle?->$champ ?? $defaut);
?>
<h1><?= $salle ? 'Modifier la salle' : 'Ajouter une salle' ?></h1>

<form method="post" action="<?= $salle ? '/salles/' . View::h($salle->id) . '/edit' : '/salles' ?>">
    <label>
        Nom
        <input type="text" name="nom" value="<?= View::h($valeur('nom')) ?>">
    </label>
    <?php if (!empty($errors['nom'])): ?>
        <p class="erreur"><?= View::h($errors['nom'][0]) ?></p>
    <?php endif; ?>

    <label>
        Bâtiment
        <input type="text" name="batiment" value="<?= View::h($valeur('batiment')) ?>">
    </label>
    <?php if (!empty($errors['batiment'])): ?>
        <p class="erreur"><?= View::h($errors['batiment'][0]) ?></p>
    <?php endif; ?>

    <label>
        Capacité
        <input type="number" name="capacite" value="<?= View::h($valeur('capacite')) ?>">
    </label>
    <?php if (!empty($errors['capacite'])): ?>
        <p class="erreur"><?= View::h($errors['capacite'][0]) ?></p>
    <?php endif; ?>

    <label>
        Type
        <select name="type">
            <?php foreach (['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'] as $type): ?>
                <option value="<?= $type ?>" <?= $valeur('type') === $type ? 'selected' : '' ?>>
                    <?= View::h($type) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (!empty($errors['type'])): ?>
        <p class="erreur"><?= View::h($errors['type'][0]) ?></p>
    <?php endif; ?>

    <label>
        <input type="checkbox" name="active" <?= $valeur('active', true) ? 'checked' : '' ?>>
        Active
    </label>

    <button type="submit">Enregistrer</button>
</form>
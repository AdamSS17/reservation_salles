<?php
/** @var string $titre */
/** @var string $contenu */
use App\View\View;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= View::h($titre ?: 'Réservation de salles') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/">Accueil</a>
            <a href="/salles">Salles</a>
            <a href="/reservations">Réservations</a>
        </nav>
    </header>

    <?php if (!empty($_SESSION['succes'])): ?>
        <p class="message message--succes"><?= View::h($_SESSION['succes']) ?></p>
        <?php unset($_SESSION['succes']); ?>
    <?php endif; ?>

    <main>
        <?= $contenu ?>
    </main>
</body>
</html>
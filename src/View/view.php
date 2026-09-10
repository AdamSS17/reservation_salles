<?php

declare(strict_types=1);

namespace App\View;

/**
 * Moteur de rendu minimal, sans dépendance externe.
 * Ne connaît ni Eloquent ni le conteneur — respecte la contrainte
 * du brief : "les vues ne doivent appeler ni Eloquent ni le conteneur".
 */
final class View
{
    private const DOSSIER_TEMPLATES = __DIR__ . '/../../templates/';

    /**
     * Rend un template et retourne le HTML sous forme de chaîne
     * (ne fait jamais echo directement, pour rester composable).
     */
    public static function render(string $template, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require self::DOSSIER_TEMPLATES . $template . '.php';
        return ob_get_clean();
    }

    /**
     * Rend un template de contenu, puis l'insère dans le layout commun.
     */
    public static function renderAvecLayout(string $template, array $data = [], string $titre = ''): string
    {
        $contenu = self::render($template, $data);

        return self::render('layout/base', [
            'titre'   => $titre,
            'contenu' => $contenu,
        ]);
    }

    /**
     * Échappe une valeur pour affichage HTML sûr. À utiliser pour
     * TOUTE sortie dynamique dans les templates (contrainte du brief).
     */
    public static function h(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
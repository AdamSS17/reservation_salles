<?php

declare(strict_types=1);

namespace App;

use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\View\View;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use function FastRoute\simpleDispatcher;

final class Application
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    public function run(): void
    {
        $dispatcher = simpleDispatcher(require dirname(__DIR__) . '/routes/web.php');

        $uri = $_SERVER['REQUEST_URI'];
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $resultat = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $uri);

        match ($resultat[0]) {
            Dispatcher::NOT_FOUND          => $this->repondre404(),
            Dispatcher::METHOD_NOT_ALLOWED => $this->repondre405($resultat[1]),
            Dispatcher::FOUND              => $this->repondreFound($resultat[1], $resultat[2]),
        };
    }

    private function repondre404(): void
    {
        http_response_code(404);
        echo View::renderAvecLayout('error/404', [], '404');
    }

    private function repondre405(array $methodesAutorisees): void
    {
        http_response_code(405);
        header('Allow: ' . implode(', ', $methodesAutorisees));
        echo View::renderAvecLayout('error/405', [
            'methodesAutorisees' => $methodesAutorisees,
        ], '405');
    }

    private function repondreFound(array $handler, array $params): void
    {
        [$controleurClasse, $methode] = $handler;

        // Seul point du projet qui interroge directement le conteneur
        // (contrainte du brief : "seul le point d'entrée récupère
        // directement un objet dans le conteneur").
        $controleur = $this->container->get($controleurClasse);

        $arguments = array_map(
            static fn(string $valeur) => ctype_digit($valeur) ? (int) $valeur : $valeur,
            array_values($params)
        );

        $resultat = $controleur->$methode(...$arguments);

        if (is_string($resultat)) {
            echo $resultat;
        }
    }
}
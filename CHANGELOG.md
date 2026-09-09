Etape1
LE COMPOSER
-permet nos classes via l'autoload
-c'est aussi un gestionnaire de dependance il permet de definir les librairies qu'un projet a besoin pour founctionner

require les paquets que le projet a besoin pour fonctionner en production

require-dev ce que le developpeur a besoin pour ces tests

composer.lock est versionné afin de garantir que le projet utilise les mêmes versions exactes de dépendances sur toutes les machines

On ne versionne pas le dossier vendor/ car il est généré automatiquement par Composer à partir de composer.json et composer.lock.

Etape2 

Quel rôle joue Capsule\Manager ?
C'est le point de configuration central d'Eloquent quand on l'utilise hors Laravel. Il remplace ce que Laravel ferait automatiquement (lire config/database.php de Laravel, connecter, exposer les modèles) — ici, c'est toi qui le fais explicitement, une seule fois.

Pourquoi Eloquent peut-il fonctionner sans Laravel ?
Parce que illuminate/database est un paquet découplé du reste de Laravel — Laravel lui-même l'utilise comme dépendance, mais rien n'empêche de l'installer seul via Composer et de le configurer manuellement, exactement comme on vient de le faire.

Où doit se trouver le démarrage de l'ORM ?
Dans un seul fichier centralisé (config/database.php), jamais dupliqué ailleurs — c'est la contrainte "la connexion doit être configurée une seule fois". Tous les autres fichiers (migrate.php, index.php...) viennent le charger, ils ne reconfigurent jamais Eloquent eux-mêmes.

Quelle différence entre ORM et SQL écrit à la main ?
Avec du SQL brut, tu écris toi-même INSERT INTO salles (...) VALUES (...). Avec Eloquent, tu écris Salle::create([...]) — Eloquent génère le SQL pour toi, gère les échappements (sécurité contre l'injection SQL), et te retourne des objets PHP au lieu de tableaux bruts.
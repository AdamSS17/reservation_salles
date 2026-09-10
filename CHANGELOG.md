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

Etape3

Quel type de relation Eloquent avez-vous utilisé ?
Une relation un-à-plusieurs (hasMany côté Salle, belongsTo côté Reservation) — une salle peut avoir plusieurs réservations, mais chaque réservation appartient à une seule salle.

Pourquoi déclarer $fillable ?
Par sécurité : sans ça, Salle::create($_POST) accepterait n'importe quel champ envoyé par un formulaire, y compris des champs qu'on ne veut pas laisser modifier de l'extérieur (par exemple, id ou created_at). $fillable définit une liste blanche explicite.

Pourquoi convertir active en booléen ?
MySQL stocke ce champ comme TINYINT(1) (0 ou 1) en interne. Sans $casts, $salle->active renverrait 1 ou 0 (un entier), et if ($salle->active) fonctionnerait presque par accident. Avec le cast, tu obtiens un vrai true/false PHP, plus lisible et plus sûr pour les comparaisons strictes (===).

Pourquoi convertir les dates en objets ?
Parce que manipuler des chaînes de caractères pour des comparaisons de dates est source d'erreurs ("2026-09-10 10:00:00" < "2026-09-10 09:00:00" compare du texte, pas des dates). Avec datetime en cast, date_debut devient un objet Carbon qui offre des méthodes fiables comme ->lt(), ->diffInHours() — exactement ce dont tu auras besoin à l'Étape 8 pour vérifier "la réservation dure au maximum quatre heures".

Etape4


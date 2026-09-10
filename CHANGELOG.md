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

Quelle différence existe entre migration et seeder ?
Une migration définit la structure de la base (créer une table, ses colonnes, ses types) — c'est le "contenant". Un seeder insère des données dans cette structure déjà existante — c'est le "contenu". Une migration s'exécute une fois par changement de schéma ; un seeder peut être relancé pour repeupler ou compléter des données.

Pourquoi les données initiales doivent-elles être reproductibles ?
Parce que n'importe qui (toi sur un nouvel ordi, ton prof en clonant le dépôt, un futur collègue) doit pouvoir reconstruire exactement le même état de départ juste en lançant le script — sans dépendre d'un export/import manuel de base de données, qui serait fragile et non versionnable proprement dans git.

Comment empêcher les doublons ?
En cherchant d'abord si la donnée existe (via un critère unique — ici le nom de la salle) avant de l'insérer, plutôt que d'insérer aveuglément à chaque exécution. firstOrCreate() (ou updateOrCreate() si tu veux aussi mettre à jour les champs existants) encapsule exactement cette logique.

Etape5

Pourquoi séparer la validation syntaxique des règles métier ?
La validation syntaxique vérifie la forme des données (un email a bien un @, une capacité est un entier positif) indépendamment du contexte. Les règles métier dépendent de l'état de l'application (est-ce que cette salle précise est active ? y a-t-il un chevauchement avec une réservation existante ?) — ça nécessite d'interroger la base, ce qu'un validateur ne devrait jamais faire. Mélanger les deux rendrait le validateur dépendant de la base de données, et donc impossible à tester isolément.

Pourquoi créer une interface de validation ?
Pour permettre d'injecter n'importe quelle implémentation (SalleValidator, ReservationValidator, ou même un faux validateur en mémoire pour les tests) partout où ValidatorInterface est attendu, sans que le code appelant ait besoin de connaître la classe concrète. C'est ce qui permettra à PHP-DI (Étape 11) de résoudre automatiquement la bonne implémentation.

Pourquoi le validateur ne doit-il pas enregistrer les données ?
Séparation des responsabilités (principe SRP de SOLID) : le rôle du validateur est de dire "est-ce correct ou pas", pas d'agir dessus. S'il enregistrait aussi les données, on ne pourrait pas valider sans effet de bord (impossible de tester "est-ce que ce formulaire est valide" sans polluer la base à chaque test), et on casserait le principe qu'une classe ne devrait avoir qu'une seule raison de changer.

Comment retourner plusieurs erreurs en une seule fois ?
En parcourant tous les champs et en accumulant les erreurs dans un tableau ($errors[$champ] = [...]) au lieu de s'arrêter au premier throw rencontré (ce que ferait un simple try/catch unique autour de tout). C'est exactement ce que fait la boucle foreach du code ci-dessus : chaque champ est testé indépendamment, donc un formulaire avec 3 erreurs les affiche toutes les 3 d'un coup, plutôt que de forcer l'utilisateur à corriger un champ à la fois.

Etape6 

Quelle différence existe entre DTO et modèle Eloquent ?
Le modèle Eloquent (Salle, Reservation) est connecté à la base — il sait faire save(), find(), a des relations, hérite de tout le comportement Active Record. Le DTO est une classe inerte, sans aucun lien avec la base de données : juste des propriétés typées, rien d'autre. Le DTO transporte l'intention ("voici ce que l'utilisateur veut créer"), le modèle représente l'état réel en base.

Pourquoi le DTO ne doit-il pas appeler save() ?
Parce que ce n'est pas son rôle (principe de responsabilité unique) : un DTO transporte des données, il ne décide pas quand ni comment les persister. C'est le Service (Étape 8) qui orchestre ça — il reçoit le DTO, applique les règles métier, puis appelle le Repository pour sauvegarder. Si le DTO savait "save", il faudrait qu'il connaisse Eloquent, cassant le découplage voulu par cette architecture en couches.

À quel moment transforme-t-on les chaînes en dates ?
Dans la méthode depuisTableau() du DTO lui-même — c'est exactement le rôle de cette étape de transformation : convertir des données brutes déjà validées (mais encore sous forme de chaînes, car c'est le format de $_POST) en types PHP forts et utilisables (DateTimeImmutable). Après cette conversion, plus aucune couche suivante (Service, Repository) n'a besoin de reparser des chaînes de date.

Le DTO doit-il contenir la règle de chevauchement ?
Non. Le chevauchement entre deux réservations est une règle métier qui nécessite d'interroger la base de données (comparer avec les réservations existantes) — ce n'est pas une donnée statique qu'on peut vérifier en isolation au moment de construire le DTO. Cette règle appartient au Service (Étape 8), qui a accès au Repository pour faire cette recherche.

Etape7

Eloquent constitue-t-il déjà un accès aux données ?
Oui, techniquement — Eloquent implémente déjà le pattern Active Record, qui est une forme d'accès aux données. Salle::find(1) fait déjà tout le travail de requête.

Pourquoi ajouter un Repository au-dessus d'Eloquent ?
Pour découpler le reste de l'application (Services, Contrôleurs) de la mécanique précise d'Eloquent. Le Service dépend de SalleRepositoryInterface (une abstraction), jamais de Salle:: directement — s'il fallait changer d'ORM un jour, ou ajouter du cache, ou basculer certaines requêtes vers une API externe, seul le Repository changerait, pas le Service qui l'utilise.

Cette abstraction est-elle toujours nécessaire ?
Pas toujours — pour un petit script isolé, ou un prototype jetable, l'ajouter serait de la sur-ingénierie. Mais dès qu'on veut des tests unitaires sans base de données (contrainte explicite de l'Étape 12 : "les tests unitaires des services ne doivent pas nécessiter MySQL"), l'interface devient indispensable : on peut fournir une implémentation en mémoire (InMemorySalleRepository) à la place de la vraie, dans les tests.

Quel avantage apporte-t-elle ?
Testabilité (via des doublures), remplaçabilité (changer d'implémentation sans toucher au code appelant), et lisibilité (le Service exprime ses besoins via des noms métier — trouverConflit() — plutôt que des détails techniques Eloquent).


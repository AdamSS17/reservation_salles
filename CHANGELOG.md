Etape1
LE COMPOSER
-permet nos classes via l'autoload
-c'est aussi un gestionnaire de dependance il permet de definir les librairies qu'un projet a besoin pour founctionner

require les paquets que le projet a besoin pour fonctionner en production

require-dev ce que le developpeur a besoin pour ces tests

composer.lock est versionné afin de garantir que le projet utilise les mêmes versions exactes de dépendances sur toutes les machines

On ne versionne pas le dossier vendor/ car il est généré automatiquement par Composer à partir de composer.json et composer.lock.


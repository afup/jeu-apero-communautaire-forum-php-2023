# AFUP - Jeu soirée communautaire Forum PHP 2023

Jeu sous forme de site web Symfony permettant de briser la glace entre les participants du Forum PHP 2023 : plusieurs équipes, chaque particpant devant retrouver une partie des membres de son équipe en flashant le QR Code présent sur le badge des visiteurs.

## Installation

### Avec docker

```shell
docker compose up -d
```

Ensuite, se connecter au container et exécuter quelques installations :

```shell
# Connexion au container
docker compose exec -u 1000 php bash

# Dans le container
composer install

# Dans le container - Bdd sqlite par défaut
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
```

Site accessible ensuite sur http://localhost.

#### Port alternatif 

Il est possible d'utiliser un autre port :

```shell
EXTERNAL_PORT=8080 docker compose up -d

# autres commandes d'installation
```
Puis accès par http://localhost:8080

## Codes joueurs

Pour générer les codes des joueurs, utiliser la commande suivante:

```shell
# Générer les codes des joueurs
bin/console app:generate-codes <id-visiteur-début> <id-visiteur-fin>

# Exemple
bin/console app:generate-codes 12000 12800
```

- `<id-visiteur-début>`: correspond à l'ID dans le BO du ou de la **première** participante du forum 2023
- `<id-visiteur-fin>`: correspond à l'ID dans le BO du ou de la **dernière** participante du forum 2023
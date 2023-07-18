# AFUP - Jeu apéro communautaire Forum PHP 2023

Jeu sous forme de site web Symfony permettant de briser la glace entre les participants du Forum PHP 2023 : plusieurs équipes, chaque particpant devant retrouver une partie des membres de son équipe en flashant le QR Code présent sur le badge des visiteurs.

## Installation

### Avec docker

```shell
docker compose up
```

Site accessible ensuite sur http://localhost.

#### Port alternatif 

Il est possible d'utiliser un autre port :

```shell
EXTERNAL_PORT=8080 docker compose up
```
Puis accès par http://localhost:8080
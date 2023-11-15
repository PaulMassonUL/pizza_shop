# pizza_shop

## Membres du groupe :
Paul MASSON, Mathis SEILER

## Dépot git :
https://github.com/PaulMassonUL/pizza_shop/

## Installation :

#### Docker
- Se déplacer dans le dossier `pizza.shop.components` où se situe le fichier `docker-compose.yml`.
- Installer les conteneurs docker grâce à la commande `docker compose up -d`.

#### Composer
- Installer composer pour le service shop grâce à la commande `docker compose exec -it api.pizza-shop composer install`.
- Installer composer pour le service authentification grâce à la commande `docker compose exec -it api.pizza-auth composer install`.

## Réalisations :
Tous les exercices du TD 1 à 3 ont été réalisés entièrement : Service commande et API commandes.
Le TD 4 a également été implémenté, à l'exception du dernier exercice : Provider, Manager et Service JWT.

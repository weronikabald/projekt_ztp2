# Opis projektu

Internetowa wypożyczalnia (książek, płyt, etc.)

* użytkownicy: administrator mający możliwość tworzenia, edycji i usuwania treści na stronie oraz użytkownik niezalogowany, mający możliwość przeglądania treści na stronie i wypożyczania zasobów,
* CRUD dla elementów katalogu (książki, muzyka, etc.),
* CRUD dla kategorii, łączenie kategorii z elementami,
* wyświetlanie listy elementów dla danej kategorii,
* lista rekordów od najnowszego do najstarszego z paginacją po 10 rekordów na stronie,
* administrator (logowanie, zmiana hasła, zmiana danych administratora),
* niezalogowani użytkownicy mają możliwość zarezerwowania zasobu (formularz zawiera adres e-mail użytkownika, nick oraz treść komentarza),
* administrator ma możliwość odrzucenia rezerwacji, zatwierdzenia, czyli wypożyczenia (wtedy stan zasobu zmniejsza się lub staje się niedostępny), przyjęcia zwrotu.

# Projekt bazy danych
![rental](https://github.com/weronikabald/projekt_ztp2/assets/134999332/3cd3ca80-9c4f-48a4-8011-511405bc1291)

# Docker Symfony Starter Kit

Starter kit is based on [The perfect kit starter for a Symfony 4 project with Docker and PHP 7.2](https://medium.com/@romaricp/the-perfect-kit-starter-for-a-symfony-4-project-with-docker-and-php-7-2-fda447b6bca1).

## What is inside?

* Apache 2.4.25 (Debian)
* PHP 8.1 FPM
* MySQL 8.0.x (5.7)
* NodeJS LTS (latest)
* Composer
* Symfony CLI 
* xdebug
* djfarrelly/maildev

## Requirements

* Install [Docker](https://www.docker.com/products/docker-desktop) and [Docker Compose](https://docs.docker.com/compose/install) on your machine 

## Installation

* (optional) Add 

```bash
127.0.0.1   symfony.local
```
in your `host` file.

* Run `build-env.sh` (or `build-env.ps1` on Windows box)

* Enter the PHP container:

```bash
docker-compose exec php bash
```

* To install Symfony LTS inside container execute:

```bash
cd app
rm .gitkeep
git config --global user.email "you@example.com"
symfony new ../app --full --version=lts
chown -R dev.dev *
```

## Container URLs and ports

* Project URL

```bash
http://localhost:8000
```

or 

```bash
http://symfony.local:8000
```

* MySQL

    * inside container: host is `mysql`, port: `3306`
    * outside container: host is `localhost`, port: `3307`
    * passwords, db name are in `docker-compose.yml`
    
* djfarrelly/maildev i available from the browser on port `8001`

* xdebug i available remotely on port `9000`

* Database connection in Symfony `.env` file:
```yaml
DATABASE_URL=mysql://symfony:symfony@mysql:3306/symfony?serverVersion=5.7
```

## Useful commands

* `docker-compose up -d` - start containers
* `docker-compose down` - stop contaniners
* `docker-compose exec php bash` - enter into PHP container
* `docker-compose exec mysql bash` - enter into MySQL container
* `docker-compose exec apache bash` - enter into Apache2 container

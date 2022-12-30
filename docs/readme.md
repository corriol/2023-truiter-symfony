# Sessió 1: Posada a punt del projecte

## Creació del projecte bàsic 
```
composer create-project symfony/skeleton:"^5.4" 2023-truiter-symfony
```

## Addició de components bàsics

`symfony/webapp-pack` és una **recepta** que permet l'addició dels components bàsics de
Symfony per a crear una aplicació web tradicional.

```
composer requrie webapp
```

## Entorn de desenvolupament

L'entorn de desenvolupament és semblant al que vam usar en unitats anteriors, el 
directori `public` serà el document arrel. Disposem d'uns contenidors ja preparats
Per a generar el fitxer `.htaccess` usarem la recepta:

```
composer require symfony/apache-pack
```

## Controladors i rutes

`maker-bundle` permet, entre altres coses, crear controladors.

```
php bin/console make:controller Default
```


```php
    #[Route("/", name: "home")]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
``` 

La definició de la ruta es pot fer de diverses formes, en aquest cas 
les hem fet mitjançant atributs.

```php
#[Route("/{id}", 
    name: "tweets_user_id", 
    requirements: ["id" => "\d+"], 
    methods: ["GET"], 
    priority: 2)
]
```

## Vistes

Per a la definició de vistes usem *Twig*, un motor de plantilles basat en
un `ruby`. El que fa que canvie un pèl la sintaxi emprada.

El mètode `AbstractControler::render` permet generar la vista i tornar-la 
com un objecte `Response`.

### Plantilla base o _layout_

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %}Welcome!{% endblock %}</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 128 128%22><text y=%221.2em%22 font-size=%2296%22>⚫️</text></svg>">
        {# Run `composer require symfony/webpack-encore-bundle` to start using Symfony UX #}
        {% block stylesheets %}
            {{ encore_entry_link_tags('app') }}
        {% endblock %}

        {% block javascripts %}
            {{ encore_entry_script_tags('app') }}
        {% endblock %}
    </head>
    <body>
        {% block body %}{% endblock %}
    </body>
</html>
```

Vista d'exemple `sample.html.twig` 

```html
{% extends 'base.html.twig' %}
{% block body %}
    {{ message }}
{% endblock %}
```

# Sessió 2: Model de dades

## Terminologia

- Un ORM (_Object Relational Mapping_) és un _framework_ encarregat de tractar amb una base de 
dades relacional (connectar amb ella, realitzar operacions de consulta, inserció, etc.), 
de manera que, de cara a l'aplicació, es converteixen a objectes tots els elements 
que s'extraguen de la base de dades i viceversa (els objectes de l'aplicació es transformen 
en registres de la base de dades, arribat el cas).
- Una **entitat** és un objecte que representa un registre d'una taula de base de dades de manera que es puga
treballar amb ell de manera més còmoda.
- Un **repositori** és un objecte que s'utilitza per a interactuar amb les dades emmagatzemades en una base de dades. 
És a dir, és un objecte que et permet llegir, escriure i actualitzar les dades d'una taula de base de dades de 
manera senzilla.

## Creació d'entitats

```
php bin/console make:entity User
php bin/console make:entity Tweet
```

- Una **migració** en Symfony és un _script_ que es fa servir per a modificar l'estructura de la base de dades 
de la teua aplicació de manera consistent i eficient. Una migració descriu els canvis que es volen fer a la base
de dades.

- Els **entorns** són conjunts de configuracions que es fan servir per a controlar el comportament de la teua 
aplicació en diferents escenaris, com ara desenvolupament, producció o test. Fitxer .env

- **Bundle**, integració d'un paquet en Symfony.

```
docker exec -it 2023-truiter-symfony_web-server_1 /bin/bash
```

```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
``` 

**Error**
> The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue.  

**Solution**
```
ServerVersion=mariadb-10.4.14
```

## Loading demo (fake) data

```
composer require --dev orm-fixtures
```

https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html

```
php bin/console doctrine:fixtures:load
```

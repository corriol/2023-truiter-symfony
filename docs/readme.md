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

fakerphp/php

https://fakerphp.github.io/

# Sessió 3: Validació i Formularis

https://symfony.com/doc/5.4/validation.html

A nivell d'entitat

```
ValidatorInterface $validator
```

https://symfony.com/doc/5.4/validation.html#supported-constraints

## Forms
https://symfony.com/doc/5.4/forms.html

Object of class App\Entity\User could not be converted to string

# Sessió 4: Millorant el disseny: plantilles base i partials
# Sessió 5: Sistema d'autenticació i autorització

## Adaptant l'entitat `User`
## Implementamt el sistema d'autenticació
## Implementant el sistema d'autorització

# Sessió 6: Pujada de fitxers 

## Perfil d'usuari
### Addició de la propietat `profile` 
profile, string, 255, yes

### Creació del formulari de registre

Mitjançant `make:registration-form` creem el formulari de registre i indiquem que no volem que s'envie missatge de 
confirmació i que sí volem que l'entitat `User` siga única.

Aquesta última opció afig la següent restricció:
```
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
```

### Instal·lació i configuració de VichUploader
```
composer require vich/uploader-bundle
```

1) Cal definir el _mapping_ en `config/packages/vich_uploader.yaml`:

```
    profile:
        uri_prefix: /images/profiles
        upload_destination: '%kernel.project_dir%/public/images/photos'
        namer: Vich\UploaderBundle\Naming\UniqidNamer
```

2) Després modifiquem l'entitat afegint en la classe el següent atribut:

```
use Vich\UploaderBundle\Mapping\Annotation as Vich;
...
#[Vich\Uploadable]
...
```
3) Definim la propietat `imageProfile`
```
    #[Vich\UploadableField(mapping: 'profile',fileNameProperty: 'profile')]
    private ?File $imageProfile = null;
```

4) Adaptem el formulari de registre afegint la propietat `imageProfile`:

```
            ->add('imageProfile', VichFileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/gif', 'image/png', 'image/jpeg'],
                        'mimeTypesMessage' => 'El fitxer ha de ser una imatge'
                        ]
                    )
```
5) Si en processar el formulari tenim problemes en la validació de `User` caldrà assignar manualment les propietats: 
   ```
   $user->setCreatedAt(new DateTime());
   $user->setVerified(false);
   ```
Hi ha alternatives com assignar valors per defecte en la propietat mitjançant el constructor.

Recursos: 
- https://github.com/dustin10/VichUploaderBundle
- https://symfony.com/doc/5.4/reference/constraints/File.html



### Serialització

Respecte a l'error: File cannot be serialized

Cal saber que:

> La serialització és el procés de convertir dades en un format que pot ser guardat o transmès per la xarxa. 
> Això permet que les dades es puguin desar en disc o enviar a través d'una connexió de xarxa, i després 
> deserialitzar-les per recuperar les dades originals.

En aquest cas en tractar de serialitzar l'objecte File per guardar-lo en la sessió (i en disc dur) mostra l'error.

Ho solucionarem implementant la interfície `serializable` d'aquesta forma

```

    /**
     * String representation of object.
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string|null The string representation of the object or null
     * @throws Exception Returning other type than string or null
     */
    public function serialize(): ?string
    {
        return serialize([
            $this->getId(),
            $this->getUsername(),
            $this->getPassword()
        ]);
    }

    /**
     * Constructs the object.
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        list( $this->id, $this->username, $this->password) = 
            unserialize($serialized, ['allowed_classes' => false]);
    }
```
TODO: Veure el nou requeriment d'usar els mètodes màgics __serialize i __unserialize 

### Paràmetres

Podem afegir paràmetres de l'aplicació en `config/services.yaml` sota la clau `parameters`, per a Twig en 
la clau `globals` dintre de la clau `twig`.


## Fotos en els tweets.

(pendent)

Macros to change the prototype
https://www.youtube.com/watch?v=FAfaiglT5_I
https://github.com/dustin10/VichUploaderBundle/blob/master/docs/form/vich_file_type.md
https://symfony.com/doc/5.4/form/form_collections.html

## Instal·lació i configuració de LiipImagine

(pendent)

# Sessió 7: Relacions molts a molts
 
## 1. Implementació del model de dades
## 2. Creació del controlador
## 3. Creació de les rutes 

## Recursos
- https://corriol.github.io/2023-dwes/08-symfony/11-relacions-molts-a-molts/
- https://symfony.com/doc/current/doctrine/associations.html
- https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/association-mapping.html
- https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/association-mapping.html#many-to-many-unidirectional



# Sessió 8: Gestionant CSS i Javascript

**Webpack** és una eina de construcció per a aplicacions web. Ajuda a agrupar i optimitzar els arxius JavaScript, CSS, 
imatges i altres recursos per a la seva distribució als navegadors web. A més, proporciona característiques com la 
*gestió de dependències* i el muntatge de mòduls, així com la capacitat de crear diversos paquets per a diferents entorns.

- https://webpack.js.org/

**Encore** és una eina de configuració de Webpack i un empaquetador d'aplicacions JavaScript per a Symfony. Proporciona 
una forma fàcil i convenient d'utilitzar **Webpack** i gestionar recursos com fulls d'estil, JavaScript i imatges 
en els projectes Symfony. Encore també permet la integració d'eines com Babel, Sass i TypeScript, i inclou funcions 
com la divisió de codi i importacions dinàmiques.

- https://symfony.com/doc/5.4/frontend/encore/installation.html

## 1. Instal·lació de Node.js 18 LTS

https://nodejs.org/en/download/
https://github.com/nodesource/distributions/blob/master/README.md#debinstall

## 2. Webpack Encore

composer require symfony/webpack-encore-bundle
npm install
npm run dev

https://symfony.com/doc/5.4/frontend/encore/simple-example.html

## 3. Instal·lació de Bootsrap

npm install bootstrap --save-dev
npm install bootstrap-icons --save-dev

```
// app.js
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';

import 'bootstrap/dist/js/bootstrap';

// start the Stimulus application
import './bootstrap';
```

### `import` vs `require`
En JavaScript, la declaració "import" s'utilitza per importar bindings que s'exporten des d'un altre mòdul. 
És part del sistema de mòduls ECMAScript (ES), que és una manera d'incloure mòduls en JavaScript. D'altra banda, 
la funció "require" s'utilitza per incloure un mòdul que ha estat exportat per un altre fitxer en CommonJS, 
que és un sistema de mòduls de JavaScript utilitzat en Node.js. En resum, "import" s'utilitza en JavaScript modern 
i "require" s'utilitza en versions antigues de JavaScript, especialment en Node.js.

## Desplegament

Configurar la versió correct de PHP
https://support.plesk.com/hc/en-us/articles/115003766853-How-to-configure-a-specific-command-line-PHP-version-for-an-SSH-user-on-a-Plesk-for-Linux-server-

[core:alert] /var/www/vhosts/example.com/httpdocs/.htaccess: Option FollowSymlinks not allowed here
https://support.plesk.com/hc/en-us/articles/115001125289-A-website-hosted-in-Plesk-shows-500-Internal-Server-Error-Option-FollowSymLinks-not-allowed-here
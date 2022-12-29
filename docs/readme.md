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
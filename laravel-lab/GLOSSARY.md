# Glossario Laravel

Glossario minimo dei termini incontrati nel laboratorio.

## Artisan

Il comando da terminale di Laravel.

Esempio:

    php artisan serve

Serve per avviare server locali, creare file, eseguire migration, lanciare test e molto altro.

## Composer

Gestore delle dipendenze PHP.

Serve per installare Laravel e le librerie PHP richieste dal progetto.

Comando tipico:

    composer install

## Node.js

Runtime JavaScript usato dagli strumenti frontend.

Nel contesto Laravel serve soprattutto per Vite, JavaScript e CSS.

## npm

Gestore pacchetti JavaScript incluso con Node.js.

Serve per installare dipendenze frontend.

## Laravel Installer

Comando globale che permette di creare nuovi progetti Laravel.

Esempio:

    laravel new first-project

## Project skeleton

Lo scheletro iniziale di un progetto Laravel.

È la struttura della nostra applicazione, non il framework Laravel vero e proprio.

## Framework

Il codice Laravel vero e proprio, installato da Composer.

Nel progetto si trova dentro:

    vendor/laravel/framework

## vendor/

Cartella generata da Composer.

Contiene Laravel e le dipendenze PHP.

Non va modificata a mano e non va committata nel repository.

## .env

File locale con configurazioni specifiche dell’ambiente.

Può contenere dati sensibili come chiavi, password e credenziali.

Non va committato.

## .env.example

Esempio pubblico del file `.env`.

Serve come modello per creare il proprio `.env` locale.

## APP_KEY

Chiave applicativa Laravel.

Viene generata con:

    php artisan key:generate

## SQLite

Database basato su file.

È comodo per iniziare perché non richiede un server database separato.

Nel progetto Laravel locale può essere:

    database/database.sqlite

## Migration

File PHP che descrive modifiche alla struttura del database.

Esempio: creare una tabella, aggiungere una colonna, creare un indice.

## Factory

Classe usata per generare dati finti.

Utile per test, sviluppo e dati demo.

## Seeder

Classe usata per inserire dati iniziali nel database.

Utile per preparare un ambiente locale o demo.

## Model

Classe PHP che rappresenta un dato o una tabella.

Esempi futuri:

- User
- Post
- Product

## Controller

Classe PHP che gestisce richieste HTTP in modo organizzato.

Serve a evitare di mettere troppa logica direttamente nelle route.

## Route

Regola che collega un URL a una risposta dell’applicazione.

Esempio:

    Route::get('/', function () {
        return view('welcome');
    });

## Blade

Sistema di template HTML di Laravel.

I file Blade hanno estensione:

    .blade.php

## Vite

Strumento frontend usato da Laravel per JavaScript e CSS moderni.

Viene usato più avanti quando serve lavorare sulla parte frontend.

## Service provider

Classe che registra o configura servizi Laravel prima che l’applicazione gestisca le richieste.

Esempio nel progetto:

    app/Providers/AppServiceProvider.php

## Middleware

Strato intermedio tra richiesta e risposta.

Può servire per autenticazione, sicurezza, sessioni, controlli e trasformazioni della richiesta.

## Debug

Modalità che mostra errori dettagliati agli sviluppatori.

In locale può essere attiva.

In produzione deve essere disattivata.

Variabile tipica:

    APP_DEBUG=false

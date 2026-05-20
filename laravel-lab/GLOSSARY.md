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

## HTTP verb

Metodo HTTP usato da una richiesta.

Esempi comuni:

- `GET` — leggere/visualizzare una risorsa
- `POST` — inviare dati
- `PUT` — sostituire o caricare una risorsa
- `PATCH` — modificare parzialmente una risorsa
- `DELETE` — eliminare una risorsa

In Laravel li incontriamo con metodi come:

    Route::get(...)
    Route::post(...)
    Route::put(...)
    Route::patch(...)
    Route::delete(...)

## GET

Verbo HTTP usato normalmente dal browser quando apriamo una pagina.

Esempio Laravel:

    Route::get('/about', function () {
        return response('about');
    });

## Status code

Codice numerico della risposta HTTP.

Esempio:

    200

Significa OK, richiesta riuscita.

In Laravel possiamo restituirlo così:

    return response('home', 200);

## Facade

Classe Laravel che offre una sintassi comoda per accedere a un servizio gestito dal framework.

Esempio:

    Route::get('/', function () {
        return response('home');
    });

`Route` sembra una classe usata staticamente, ma dietro le quinte Laravel inoltra la chiamata al router reale registrato nel container.

## Service Container

Sistema con cui Laravel costruisce e fornisce oggetti e servizi all’applicazione.

È una specie di fabbrica intelligente.

Le facade e molti helper usano servizi che Laravel sa recuperare dal container.

## Closure

Funzione anonima definita direttamente nel punto in cui serve.

Esempio:

    function () {
        return response('home');
    }

Nelle prime rotte Laravel la closure è il codice eseguito quando l’URL viene raggiunto.

Mentalmente assomiglia a una lambda/callback anonima.

## Helper function

Funzione globale messa a disposizione da Laravel per operazioni comuni.

Esempi:

    response(...)
    view(...)
    route(...)

Sembrano funzioni semplici, ma spesso usano servizi Laravel già configurati.

## response()

Helper Laravel che crea una risposta HTTP.

Esempio:

    return response('home', 200);

Restituisce contenuto, status code e, se necessario, header.

## dump()

Funzione di debug che stampa un valore senza fermare l’esecuzione.

Esempio:

    dump('debug');

## dd()

Abbreviazione pratica di “dump and die”.

Stampa un valore e ferma l’esecuzione.

Esempio:

    dd('first route');

Utile durante lo sviluppo, ma non deve restare nel codice finale.

## Named route

Rotta con un nome assegnato tramite `->name(...)`.

Esempio:

    Route::get('/', function () {
        return view('pages.home');
    })->name('home');

Dare nomi alle rotte permette di riferirsi a esse senza scrivere URL a mano.

## php artisan route:list

Comando Artisan che mostra tutte le rotte registrate nell’applicazione.

Comando:

    php artisan route:list

Aiuta a capire:

- quali URL esistono
- quali metodi HTTP usano
- quale nome hanno
- quale closure/controller/azione eseguono
- quali rotte sono nostre e quali sono interne al framework

## Rotta interna Laravel

Rotta registrata automaticamente dal framework o da un service provider.

Esempi incontrati:

    storage/{path}
    up

Se in `php artisan route:list` la colonna Action punta a `vendor/laravel/framework/...`, probabilmente è una rotta interna o di supporto.

## storage/{path}

Rotta interna vista in `php artisan route:list`.

Serve a supportare l’accesso o il caricamento di file nello storage locale.

`{path}` indica un parametro dinamico.

## up

Rotta interna Laravel usata come controllo minimale dello stato dell’applicazione.

Serve a indicare che l’applicazione è raggiungibile/viva.

È simile a un endpoint di health check.

## Blade

Sistema di template HTML di Laravel.

I file Blade hanno estensione:

    .blade.php

Un file Blade può essere pensato, all’inizio, come un file HTML con superpoteri Laravel.

## View

File che contiene l’HTML da restituire al browser.

Le view Laravel stanno in:

    resources/views

Esempio:

    resources/views/pages/home.blade.php

## view()

Helper Laravel che renderizza una view.

Esempio:

    return view('pages.home');

Laravel cerca la view corrispondente dentro `resources/views`.

## resources/views

Cartella dove stanno le view Laravel.

Esempio:

    resources/views/pages/home.blade.php

## Dot notation

Convenzione Laravel per indicare elementi annidati usando il punto.

Per le view:

    view('pages.home')

corrisponde a:

    resources/views/pages/home.blade.php

## make:view

Comando Artisan per creare una view.

Esempio:

    php artisan make:view home

Crea:

    resources/views/home.blade.php

Esempio con sottocartella:

    php artisan make:view pages.home

Crea:

    resources/views/pages/home.blade.php

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

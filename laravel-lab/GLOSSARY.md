# Glossario Laravel

Glossario minimo dei termini incontrati nel laboratorio.

## Artisan

Il comando da terminale di Laravel.

Esempio:

    php artisan serve

Serve per avviare server locali, creare file, eseguire migration, lanciare test, creare controller, creare view, esplorare comandi e molto altro.

## CLI

Abbreviazione di Command Line Interface.

Indica un programma usato da terminale.

Artisan è la CLI di Laravel.

## php artisan

Comando base per usare Artisan.

Va eseguito dalla root del progetto Laravel, cioè dalla cartella che contiene il file `artisan`.

Nel nostro laboratorio:

    cd ~/Progetti/web/laravel-lab/first-project
    php artisan

## php artisan list

Comando che mostra l’elenco dei comandi Artisan disponibili.

È utile quando non ricordiamo il nome esatto di un comando.

## php artisan help

Comando per leggere l’aiuto di un comando specifico.

Esempio:

    php artisan help make:controller

Alternativa equivalente:

    php artisan make:controller --help

## php artisan about

Comando che mostra informazioni generali sull’applicazione Laravel.

Può mostrare versione Laravel, versione PHP, ambiente, debug mode, database, cache, route, view e driver configurati.

## php artisan env

Comando che mostra l’ambiente corrente dell’applicazione.

Esempio:

    local

È collegato alla variabile `.env`:

    APP_ENV=local

## php artisan serve

Comando che avvia il server locale di sviluppo.

Di solito espone l’applicazione su:

    http://127.0.0.1:8000

## php artisan test

Comando che esegue i test dell’applicazione.

Più avanti servirà per verificare che il codice continui a funzionare.

## php artisan tinker

Comando che apre una sessione PHP interattiva dentro il contesto dell’app Laravel.

Permette di provare codice usando classi, model e configurazioni dell’applicazione.

## php artisan view:clear

Comando che cancella le view Blade compilate.

È utile quando una view o un componente sembrano non aggiornarsi.

## php artisan view:cache

Comando che compila tutte le view Blade.

È più utile in contesti di ottimizzazione/deploy che durante le prime lezioni.

## php artisan optimize

Comando che crea cache di bootstrap, configurazione e metadati per aumentare le prestazioni.

Da usare con criterio, soprattutto in ambienti di produzione.

## php artisan optimize:clear

Comando che rimuove vari file cache generati dal framework.

Utile quando qualcosa sembra “incastrato” a livello di cache.

## Comandi make:*

Famiglia di comandi Artisan che crea file/classi Laravel.

Esempi:

    php artisan make:view pages.home
    php artisan make:controller HomeController
    php artisan make:model Post
    php artisan make:migration create_posts_table
    php artisan make:command SendDailyReport

Regola pratica:

> quando devi creare qualcosa in Laravel, prima chiediti se esiste un comando `make:*`.

## make:command

Comando Artisan per creare un comando Artisan personalizzato.

Esempio:

    php artisan make:command SendDailyReport

Non è ancora usato nel laboratorio, ma mostra che Artisan può essere esteso con comandi propri.

## make:component

Comando Artisan per creare un componente Blade.

Esempio:

    php artisan make:component Alert

I componenti Blade verranno trattati più avanti.

## make:config

Comando Artisan per creare un file di configurazione.

In alcune versioni può apparire anche come alias `config:make`.

## make:migration

Comando Artisan per creare una migration.

Esempio futuro:

    php artisan make:migration create_posts_table

## migrate

Comando Artisan che esegue le migration.

Esempio:

    php artisan migrate

## config:cache

Comando Artisan che crea una cache della configurazione.

È utile in produzione, ma durante lo sviluppo bisogna ricordarsi che la configurazione può essere cacheata.

## config:clear

Comando Artisan che rimuove la cache della configurazione.

Utile se config o `.env` sembrano non aggiornarsi.

## route:cache

Comando Artisan che crea una cache delle route.

È più utile in produzione che nelle prime fasi di sviluppo.

## route:clear

Comando Artisan che rimuove la cache delle route.

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

## APP_NAME

Nome dell’applicazione.

Di solito viene letto tramite:

    config('app.name')

e deriva da una variabile nel file `.env`.

## APP_DEBUG

Variabile che controlla se Laravel mostra errori dettagliati.

In locale può essere:

    APP_DEBUG=true

In produzione deve essere:

    APP_DEBUG=false

## APP_ENV

Variabile che indica l’ambiente in cui gira l’applicazione.

Esempi:

    local
    staging
    production

## APP_URL

URL base dell’applicazione.

In locale può essere `http://localhost`, mentre in produzione sarà il dominio reale.

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

I controller stanno in:

    app/Http/Controllers

## Base Controller

Classe base da cui i controller applicativi possono estendere.

Nel progetto si trova in:

    app/Http/Controllers/Controller.php

Per ora non serve modificarla.

## HomeController

Controller creato nel laboratorio per gestire la homepage.

Esempio:

    app/Http/Controllers/HomeController.php

## Invocable controller

Controller con un solo metodo speciale:

    __invoke()

Può essere usato nella route così:

    Route::get('/', HomeController::class)->name('home');

È comodo quando il controller ha un solo compito.

## __invoke()

Magic method PHP.

Se una classe definisce `__invoke()`, un suo oggetto può essere chiamato come una funzione.

Laravel usa questa possibilità per i controller invocabili.

## Metodo nominato del controller

Metodo esplicito dentro un controller, per esempio:

    index()

Una route può puntare a un metodo nominato così:

    Route::get('/', [HomeController::class, 'index'])->name('home');

## index()

Nome molto comune per il metodo che mostra una pagina principale o una lista di risorse.

Nei controller RESTful, `index()` di solito mostra l’elenco delle risorse.

## Controller RESTful

Controller organizzato intorno alle azioni tipiche su una risorsa.

Metodi comuni:

- `index()` — lista risorse
- `show()` — mostra singola risorsa
- `store()` — salva nuova risorsa
- `update()` — aggiorna risorsa
- `destroy()` — elimina risorsa

## Invalid route action

Errore Laravel che può comparire quando una route punta a un controller ma Laravel non trova un metodo invocabile valido.

Esempio tipico:

- route scritta come `HomeController::class`
- controller senza metodo `__invoke()`

Soluzione:

- aggiungere `__invoke()`
- oppure indicare il metodo nella route con `[HomeController::class, 'index']`

## Route

Regola che collega un URL a una risposta dell’applicazione.

Esempio:

    Route::get('/', HomeController::class)->name('home');

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
    config(...)

Sembrano funzioni semplici, ma spesso usano servizi Laravel già configurati.

## response()

Helper Laravel che crea una risposta HTTP.

Esempio:

    return response('home', 200);

Restituisce contenuto, status code e, se necessario, header.

## config()

Helper Laravel che legge valori dai file in `config/`.

Esempio:

    config('app.name')

Legge la chiave `name` dal file:

    config/app.php

È preferibile usare `config()` nel codice applicativo invece di leggere direttamente `env()`.

## env()

Helper che legge valori dall’ambiente o dal file `.env`.

Nei normali file applicativi è meglio evitarlo e usare `config()`.

Uso tipico dentro un file config:

    'name' => env('APP_NAME', 'Laravel'),

## Fallback

Valore di riserva usato quando una configurazione non è definita.

Esempio:

    env('APP_NAME', 'Laravel')

Se `APP_NAME` non esiste, Laravel usa `Laravel`.

## Config cache

Meccanismo Laravel che può ottimizzare il caricamento della configurazione.

È uno dei motivi per cui conviene leggere configurazioni tramite `config()` invece di usare `env()` direttamente nel codice applicativo.

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

    Route::get('/', HomeController::class)->name('home');

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

## Dati della view

Dati passati dalla route o dal controller alla view tramite il secondo argomento di `view()`.

Esempio:

    return view('pages.home', [
        'greeting' => 'Hello',
    ]);

La chiave `greeting` diventa variabile `$greeting` nella view.

## Direttiva Blade

Istruzione Blade che inizia con `@`.

Esempio:

    @if ($showGreeting)
        {{ $greeting }}
    @endif

## @if

Direttiva Blade per mostrare contenuto solo se una condizione è vera.

## @endif

Direttiva Blade che chiude un blocco `@if`.

## {{ ... }}

Sintassi Blade per stampare un valore nella pagina.

Esempio:

    {{ $greeting }}

## Dot notation

Convenzione Laravel per indicare elementi annidati usando il punto.

Per le view:

    view('pages.home')

corrisponde a:

    resources/views/pages/home.blade.php

Per la configurazione:

    config('app.name')

corrisponde a:

    config/app.php → name

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

## make:controller

Comando Artisan per creare un controller.

Esempio:

    php artisan make:controller HomeController

Crea:

    app/Http/Controllers/HomeController.php

Per controller in sottocartelle si usa lo slash:

    php artisan make:controller Admin/DashboardController

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

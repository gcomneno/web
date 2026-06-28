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

    cd ~/Progetti/labs/web/laravel-lab/first-project
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

Esempio:

    php artisan make:migration create_projects_table

## make:model

Comando Artisan per creare un model.

Esempio:

    php artisan make:model Project

Crea:

    app/Models/Project.php

## make:model -m

Comando Artisan che crea insieme model e migration.

Esempio:

    php artisan make:model Project -m

Crea il model `Project` e una migration per la tabella `projects`.

## migrate

Comando Artisan che esegue le migration non ancora applicate.

Esempio:

    php artisan migrate

## migrate:status

Comando Artisan che mostra quali migration sono state eseguite.

Esempio:

    php artisan migrate:status

## migrate:rollback

Comando Artisan che annulla l’ultimo batch di migration.

Esempio:

    php artisan migrate:rollback

È comodo in locale, ma va usato con prudenza perché può cancellare strutture e dati.

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

Le migration stanno in:

    database/migrations

## Tabella `migrations`

Tabella speciale usata da Laravel per ricordare quali migration sono già state eseguite.

Serve a evitare che `php artisan migrate` rilanci ogni volta tutte le migration.

## Schema

Facade Laravel usata nelle migration per creare o modificare tabelle.

Esempi:

    Schema::create(...)
    Schema::table(...)

## Schema::create

Metodo usato per creare una nuova tabella.

Esempio:

    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

## Schema::table

Metodo usato per modificare una tabella esistente.

Esempio:

    Schema::table('projects', function (Blueprint $table) {
        $table->string('slug')->unique();
    });

## Blueprint

Oggetto usato nelle migration per descrivere colonne, indici e vincoli di una tabella.

Esempio:

    function (Blueprint $table) {
        $table->string('name');
    }

## up()

Metodo di una migration che applica la modifica.

Viene eseguito da:

    php artisan migrate

## down()

Metodo di una migration che descrive l’operazione inversa.

Viene usato da:

    php artisan migrate:rollback

Può essere pericoloso se elimina tabelle o colonne con dati reali.

## Rollback

Operazione che annulla l’ultimo batch di migration.

Comando:

    php artisan migrate:rollback

In locale è utile per correggere una migration appena creata.

In produzione va trattato con cautela.

## Batch migration

Gruppo di migration eseguite insieme da Laravel.

`migrate:rollback` annulla l’ultimo batch.

## $table->id()

Metodo Blueprint che crea una colonna `id` primaria e auto-incrementale.

## $table->string()

Metodo Blueprint che crea una colonna testuale.

Esempio:

    $table->string('name');

## $table->timestamps()

Metodo Blueprint che crea due colonne:

    created_at
    updated_at

## unique()

Vincolo che rende una colonna unica.

Esempio:

    $table->string('slug')->unique();

## created_at

Colonna timestamp che indica quando una riga è stata creata.

## updated_at

Colonna timestamp che indica quando una riga è stata aggiornata.

## Factory

Classe usata per generare dati finti.

Utile per test, sviluppo e dati demo.

## Seeder

Classe usata per inserire dati iniziali nel database.

Utile per preparare un ambiente locale o demo.

## Model

Classe PHP che rappresenta una tabella del database.

Esempio:

    app/Models/Project.php

Per convenzione Laravel usa model al singolare e tabella al plurale:

    Project → projects
    User    → users

## Eloquent

ORM di Laravel.

Permette di leggere e scrivere dati nel database usando model PHP.

Esempio:

    Project::all()

## ORM

Abbreviazione di Object-Relational Mapper.

È uno strumento che collega tabelle relazionali del database e oggetti del linguaggio di programmazione.

Eloquent è l’ORM di Laravel.

## Project

Model creato nel laboratorio per rappresentare la tabella `projects`.

File:

    app/Models/Project.php

## Project::all()

Metodo Eloquent che restituisce tutti i record della tabella collegata al model `Project`.

Esempio didattico:

    Project::all()

Restituisce una collection.

## Project::create()

Metodo Eloquent che crea un nuovo record nella tabella associata al model.

Esempio:

    Project::create([
        'name' => 'A second project',
    ]);

Per usare `create()` con assegnazione massiva serve configurare `$fillable` oppure `$guarded`.

## Project::find()

Metodo Eloquent che cerca un record tramite primary key.

Esempio:

    Project::find(1)

Se il record esiste restituisce un model `Project`.

Se non esiste restituisce `null`.

## Project::findOrFail()

Metodo Eloquent che cerca un record tramite primary key.

Esempio:

    Project::findOrFail(1)

Se il record esiste restituisce un model.

Se non esiste genera automaticamente una risposta 404.

È molto utile per pagine dettaglio come:

    /projects/1

## where()

Metodo Eloquent per costruire una query con una condizione.

Esempio:

    Project::where('name', 'A second project')

Da solo costruisce la query, ma non restituisce ancora i risultati finali.

## Query builder

Oggetto che rappresenta una query in costruzione.

Esempio:

    Project::where('name', 'A second project')

La query viene davvero eseguita quando chiamiamo metodi finali come:

    get()
    first()
    firstOrFail()

## get()

Metodo che esegue una query Eloquent e restituisce una collection.

Esempio:

    Project::where('name', 'A second project')->get()

## first()

Metodo che esegue una query Eloquent e restituisce il primo record trovato.

Esempio:

    Project::where('name', 'A second project')->first()

Se non trova niente, restituisce `null`.

## firstOrFail()

Metodo che esegue una query Eloquent e restituisce il primo record trovato.

Esempio:

    Project::where('id', 5)->firstOrFail()

Se non trova niente, genera automaticamente una risposta 404.

## update()

Metodo Eloquent per aggiornare un model esistente.

Esempio:

    $project->update([
        'name' => 'A first project',
    ]);

Di solito viene chiamato su una istanza di model già recuperata dal database.

## delete()

Metodo Eloquent per eliminare un model esistente.

Esempio:

    $project->delete();

Di solito viene chiamato su una istanza di model già recuperata dal database.

## CRUD

Acronimo di Create, Read, Update, Delete.

Indica le quattro operazioni base sui dati:

- creare
- leggere
- aggiornare
- eliminare

Nel laboratorio lo incontriamo con Eloquent.

## Mass assignment

Assegnazione massiva di dati a un model tramite array.

Esempio:

    Project::create([
        'name' => 'A second project',
    ]);

È comoda, ma Laravel la protegge per evitare che campi non autorizzati vengano riempiti accidentalmente o in modo pericoloso.

## MassAssignmentException

Eccezione Laravel generata quando proviamo a riempire un model tramite mass assignment senza aver autorizzato i campi.

Esempio tipico:

    Project::create([
        'name' => 'A second project',
    ]);

senza avere nel model:

    protected $fillable = [
        'name',
    ];

## $guarded

Proprietà del model che indica quali campi non possono essere riempiti tramite assegnazione massiva.

Esempio:

    protected $guarded = [];

Con array vuoto, il model viene praticamente “unguarded”: comodo, ma più rischioso se i dati utente non sono validati con cura.

## Model instance

Istanza concreta di un model Eloquent.

Esempio:

    $project = Project::find(1);

In questo caso `$project` rappresenta una singola riga della tabella `projects`.

## Collection

Contenitore Laravel di elementi.

Nel caso di `Project::all()`, contiene zero, uno o più oggetti `Project`.

## User

Model Laravel già presente nel progetto.

Rappresenta la tabella `users`.

È più complesso di un model base perché integra funzionalità di autenticazione.

## Authenticatable

Classe base usata dal model `User` per partecipare al sistema di autenticazione Laravel.

## Trait

Meccanismo PHP per riusare metodi dentro classi diverse.

Laravel usa trait nei model per aggiungere funzionalità.

Esempi:

    HasFactory
    Notifiable

## HasFactory

Trait che permette a un model di usare factory.

Utile per generare dati finti in test o seeding.

## Notifiable

Trait che permette a un model di ricevere notifiche Laravel.

## $fillable

Proprietà del model che indica quali campi possono essere riempiti tramite assegnazione massiva.

Esempio:

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

## $hidden

Proprietà del model che indica quali campi nascondere quando il model viene convertito in array o JSON.

Esempi tipici:

    password
    remember_token

## Cast

Regola del model che trasforma valori tra database e PHP.

Esempi:

- timestamp del database → oggetto data/Carbon
- password → hash

## Carbon

Libreria PHP usata per lavorare con date e orari.

Laravel la usa spesso per gestire campi come `created_at`, `updated_at` o `email_verified_at`.

## Cast implicito

Cast applicato automaticamente da Laravel senza doverlo dichiarare esplicitamente nel model.

Esempio importante:

    created_at
    updated_at

Questi campi vengono trattati automaticamente come oggetti Carbon.

## toDateTimeString()

Metodo Carbon che restituisce data e ora in formato leggibile.

Esempio:

    $project->created_at->toDateTimeString()

Output tipico:

    2026-06-18 16:47:08

## toTimeString()

Metodo Carbon che restituisce solo l’orario.

Esempio:

    $project->created_at->toTimeString()

Output tipico:

    16:47:08

## diffForHumans()

Metodo Carbon che restituisce una data in formato relativo e leggibile per esseri umani.

Esempio:

    $project->created_at->diffForHumans()

Output tipico:

    22 minutes ago

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

## Route parameter

Parametro dinamico dentro una route Laravel.

Esempio:

    Route::get('/projects/{id}', function (int $id) {
        //
    });

La parte `{id}` viene estratta dall’URL.

Esempi:

    /projects/1  →  $id = 1
    /projects/42 →  $id = 42

## Parametro URL

Valore dinamico presente nell’indirizzo della richiesta.

Esempio:

    /projects/1

In questo caso `1` è il parametro usato per cercare il progetto.

## Request data

Dati che arrivano dalla richiesta HTTP.

Possono arrivare da:

- URL
- query string
- form
- body della richiesta
- header

Nella lezione 12 il primo dato di request usato è l’ID dentro l’URL.

## Cast del parametro

Conversione di un valore in un tipo specifico.

Esempio:

    function (int $id) {
        //
    }

In questo caso il parametro `$id` viene trattato come intero.

## Pagina dettaglio

Pagina che mostra una singola risorsa.

Esempio:

    /projects/1

Nel laboratorio questa pagina mostra un singolo `Project`.

## 404

Codice HTTP che indica “Not Found”.

Laravel può generarlo automaticamente con metodi come:

    findOrFail()
    firstOrFail()

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

## Passare un model alla view

Una route o un controller può passare un model Eloquent intero a una view.

Esempio:

    return view('projects.show', [
        'project' => $project,
    ]);

Dentro la view sarà disponibile la variabile:

    $project

## projects.show

Nome dot notation della view dettaglio progetto.

Corrisponde al file:

    resources/views/projects/show.blade.php

## resources/views/projects/show.blade.php

View Blade usata nel laboratorio per mostrare un singolo progetto.

Esempio:

    <h1>{{ $project->name }}</h1>

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

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

## php artisan route:list

Comando Artisan che mostra tutte le rotte registrate nell’applicazione.

Aiuta a capire:

- quali URL esistono
- quali metodi HTTP usano
- quale nome hanno
- quale closure/controller/azione eseguono
- quali rotte sono nostre e quali sono interne al framework

## php artisan view:clear

Comando che cancella le view Blade compilate.

È utile quando una view o un componente sembrano non aggiornarsi.

## Comandi make:*

Famiglia di comandi Artisan che crea file/classi Laravel.

Esempi:

    php artisan make:view pages.home
    php artisan make:controller HomeController
    php artisan make:model Project
    php artisan make:migration create_projects_table

Regola pratica:

> quando devi creare qualcosa in Laravel, prima chiediti se esiste un comando `make:*`.

## make:view

Comando Artisan per creare una view.

Esempio:

    php artisan make:view projects.create

Crea:

    resources/views/projects/create.blade.php

## make:controller

Comando Artisan per creare un controller.

Esempio:

    php artisan make:controller ProjectController

Crea:

    app/Http/Controllers/ProjectController.php

## make:migration

Comando Artisan per creare una migration.

Esempio:

    php artisan make:migration add_slug_to_projects_table

## make:model

Comando Artisan per creare un model.

Esempio:

    php artisan make:model Project

Crea:

    app/Models/Project.php

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

## up()

Metodo di una migration che applica la modifica.

Viene eseguito da:

    php artisan migrate

## down()

Metodo di una migration che descrive l’operazione inversa.

Viene usato da:

    php artisan migrate:rollback

Se `up()` aggiunge una colonna, `down()` dovrebbe rimuoverla.

## Rollback

Operazione che annulla l’ultimo batch di migration.

Comando:

    php artisan migrate:rollback

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

## after()

Metodo usato in una migration per indicare dopo quale colonna posizionare una nuova colonna.

Esempio:

    $table->string('slug')->after('name');

Nota: il supporto può dipendere dal database usato.

## created_at

Colonna timestamp che indica quando una riga è stata creata.

## updated_at

Colonna timestamp che indica quando una riga è stata aggiornata.

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

Restituisce una collection.

## Project::create()

Metodo Eloquent che crea un nuovo record nella tabella associata al model.

Esempio:

    Project::create([
        'name' => 'A second project',
        'slug' => 'a-second-project',
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

Se il record non esiste genera automaticamente una risposta 404.

## where()

Metodo Eloquent per costruire una query con una condizione.

Esempio:

    Project::where('slug', 'a-first-project')

## Query builder

Oggetto che rappresenta una query in costruzione.

La query viene davvero eseguita quando chiamiamo metodi finali come:

    get()
    first()
    firstOrFail()

## get()

Metodo che esegue una query Eloquent e restituisce una collection.

## first()

Metodo che esegue una query Eloquent e restituisce il primo record trovato.

Se non trova niente, restituisce `null`.

## firstOrFail()

Metodo che esegue una query Eloquent e restituisce il primo record trovato.

Se non trova niente, genera automaticamente una risposta 404.

## update()

Metodo Eloquent per aggiornare un model esistente.

Esempio:

    $project->update([
        'name' => 'A first project',
    ]);

## delete()

Metodo Eloquent per eliminare un model esistente.

Esempio:

    $project->delete();

## CRUD

Acronimo di Create, Read, Update, Delete.

Indica le quattro operazioni base sui dati:

- creare
- leggere
- aggiornare
- eliminare

## Mass assignment

Assegnazione massiva di dati a un model tramite array.

Esempio:

    Project::create([
        'name' => 'A second project',
    ]);

È comoda, ma Laravel la protegge per evitare che campi non autorizzati vengano riempiti accidentalmente o in modo pericoloso.

## MassAssignmentException

Eccezione Laravel generata quando proviamo a riempire un model tramite mass assignment senza aver autorizzato i campi.

## $fillable

Proprietà del model che indica quali campi possono essere riempiti tramite assegnazione massiva.

Esempio:

    protected $fillable = [
        'name',
        'slug',
    ];

## $guarded

Proprietà del model che indica quali campi non possono essere riempiti tramite assegnazione massiva.

Esempio:

    protected $guarded = [];

Con array vuoto, il model viene praticamente “unguarded”: comodo, ma più rischioso se i dati utente non sono validati con cura.

## $hidden

Proprietà del model che indica quali campi nascondere quando il model viene convertito in array o JSON.

## Cast

Regola del model che trasforma valori tra database e PHP.

## Cast implicito

Cast applicato automaticamente da Laravel senza doverlo dichiarare esplicitamente nel model.

Esempio importante:

    created_at
    updated_at

Questi campi vengono trattati automaticamente come oggetti Carbon.

## Carbon

Libreria PHP usata per lavorare con date e orari.

Laravel la usa spesso per gestire campi come `created_at`, `updated_at` o `email_verified_at`.

## toDateTimeString()

Metodo Carbon che restituisce data e ora in formato leggibile.

## toTimeString()

Metodo Carbon che restituisce solo l’orario.

## diffForHumans()

Metodo Carbon che restituisce una data in formato relativo e leggibile per esseri umani.

Esempio:

    $project->created_at->diffForHumans()

## Collection

Contenitore Laravel di elementi.

Nel caso di `Project::all()`, contiene zero, uno o più oggetti `Project`.

## Model instance

Istanza concreta di un model Eloquent.

Esempio:

    $project = Project::find(1);

## Slug

Stringa leggibile e adatta agli URL.

Esempio:

    a-first-project

È utile per avere URL più descrittivi:

    /projects/a-first-project

## str()

Helper Laravel per lavorare con stringhe in modo fluente.

Esempio:

    str('A New Project')->slug()

## Str

Classe Laravel per lavorare con stringhe.

Esempio:

    Illuminate\Support\Str::slug('A New Project')

Nel laboratorio viene usato soprattutto l’helper `str()`.

## slug()

Metodo/helper che trasforma una stringa in uno slug.

Esempio:

    str('A New Project')->slug()

Risultato:

    a-new-project

## Controller

Classe PHP che gestisce richieste HTTP in modo organizzato.

Serve a evitare di mettere troppa logica direttamente nelle route.

I controller stanno in:

    app/Http/Controllers

## Base Controller

Classe base da cui i controller applicativi possono estendere.

Nel progetto si trova in:

    app/Http/Controllers/Controller.php

## HomeController

Controller creato nel laboratorio per gestire la homepage.

## ProjectController

Controller creato nel laboratorio per gestire le azioni sui progetti.

File:

    app/Http/Controllers/ProjectController.php

Nel laboratorio contiene:

    create()
    store()
    show()

## Invocable controller

Controller con un solo metodo speciale:

    __invoke()

Può essere usato nella route così:

    Route::get('/', HomeController::class)->name('home');

## create()

Metodo convenzionale di un controller RESTful.

Mostra il form per creare una nuova risorsa.

Nel laboratorio:

    ProjectController::create

mostra:

    resources/views/projects/create.blade.php

## store()

Metodo convenzionale di un controller RESTful.

Riceve dati da una richiesta POST e salva una nuova risorsa.

Nel laboratorio:

    ProjectController::store

crea un nuovo `Project`.

## show()

Metodo convenzionale di un controller RESTful.

Mostra una singola risorsa.

Nel laboratorio:

    ProjectController::show

mostra un progetto tramite route model binding.

## Resource controller

Controller organizzato intorno alle azioni standard su una risorsa.

Metodi comuni:

- index
- create
- store
- show
- edit
- update
- destroy

## Route::resource

Metodo Laravel per registrare automaticamente le route RESTful di un controller.

Esempio futuro:

    Route::resource('projects', ProjectController::class);

Nel laboratorio viene solo citato, non ancora usato.

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

## Route model binding

Funzione Laravel che converte automaticamente un parametro della route in un model Eloquent.

Esempio:

    Route::get('/projects/{project}', function (Project $project) {
        //
    });

Laravel cerca automaticamente il record corrispondente.

## Binding implicito

Route model binding configurato tramite nome del parametro e type hint del model.

Esempio:

    Route::get('/projects/{project}', function (Project $project) {
        //
    });

## Binding su colonna custom

Route model binding che usa una colonna diversa dall’ID.

Esempio:

    Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);

Qui Laravel cerca il `Project` usando la colonna `slug`.

## Parametro URL

Valore dinamico presente nell’indirizzo della richiesta.

Esempio:

    /projects/a-first-project

In questo caso `a-first-project` è il parametro usato per cercare il progetto.

## Request data

Dati che arrivano dalla richiesta HTTP.

Possono arrivare da:

- URL
- query string
- form
- body della richiesta
- header

## Request

Oggetto Laravel che rappresenta la richiesta HTTP.

Classe:

    Illuminate\Http\Request

Può contenere dati del form, header, cookie, file caricati e altre informazioni.

## $request->get()

Metodo per leggere un valore dalla request.

Esempio:

    $request->get('name')

## $request->name

Accesso pratico a un campo della request.

Esempio:

    $request->name

Nel laboratorio viene usato per leggere il campo `name` del form.

## $request->only()

Metodo per estrarre solo alcuni campi dalla request.

Esempio futuro:

    $request->only(['name', 'slug'])

## Form

Elemento HTML usato per inviare dati al server.

Esempio:

    <form action="/projects" method="POST">
        ...
    </form>

## action

Attributo HTML del form che indica verso quale URL inviare i dati.

Esempio:

    action="/projects"

## method

Attributo HTML del form che indica il metodo HTTP da usare.

Esempio:

    method="POST"

## POST

Verbo HTTP usato normalmente per inviare dati che creano una nuova risorsa.

Nel laboratorio:

    POST /projects

crea un nuovo progetto.

## CSRF

Abbreviazione di Cross-Site Request Forgery.

È un tipo di attacco in cui un sito esterno prova a far inviare una richiesta a nome dell’utente.

Laravel protegge i form tramite token CSRF.

## @csrf

Direttiva Blade che inserisce un token CSRF nel form.

Esempio:

    <form method="POST">
        @csrf
    </form>

Senza `@csrf`, Laravel può mostrare “Page expired” sulle richieste POST.

## _token

Campo hidden generato da `@csrf`.

Laravel lo usa per verificare che il form sia legittimo.

## Page expired

Errore che può comparire quando inviamo una richiesta POST senza token CSRF valido.

## back()

Helper Laravel che restituisce un redirect alla pagina precedente.

Esempio:

    return back();

## Redirect

Risposta HTTP che dice al browser di andare verso un’altra pagina.

Nel laboratorio viene introdotto con:

    return back();

## GET

Verbo HTTP usato normalmente dal browser quando apriamo una pagina.

## HTTP verb

Metodo HTTP usato da una richiesta.

Esempi comuni:

- GET
- POST
- PUT
- PATCH
- DELETE

## Status code

Codice numerico della risposta HTTP.

Esempio:

    200

Significa OK, richiesta riuscita.

## 404

Codice HTTP che indica “Not Found”.

Laravel può generarlo automaticamente con metodi come:

    findOrFail()
    firstOrFail()

e con route model binding quando un model non viene trovato.

## Facade

Classe Laravel che offre una sintassi comoda per accedere a un servizio gestito dal framework.

## Service Container

Sistema con cui Laravel costruisce e fornisce oggetti e servizi all’applicazione.

## Closure

Funzione anonima definita direttamente nel punto in cui serve.

Nelle prime rotte Laravel la closure è il codice eseguito quando l’URL viene raggiunto.

## Helper function

Funzione globale messa a disposizione da Laravel per operazioni comuni.

Esempi:

    response(...)
    view(...)
    route(...)
    config(...)
    str(...)
    back(...)

## response()

Helper Laravel che crea una risposta HTTP.

## view()

Helper Laravel che renderizza una view.

Esempio:

    return view('projects.show');

## config()

Helper Laravel che legge valori dai file in `config/`.

## env()

Helper che legge valori dall’ambiente o dal file `.env`.

Nei normali file applicativi è meglio evitarlo e usare `config()`.

## dump()

Funzione di debug che stampa un valore senza fermare l’esecuzione.

## dd()

Abbreviazione pratica di “dump and die”.

Stampa un valore e ferma l’esecuzione.

Utile durante lo sviluppo, ma non deve restare nel codice finale.

## Named route

Rotta con un nome assegnato tramite `->name(...)`.

Esempio:

    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');

## Blade

Sistema di template HTML di Laravel.

I file Blade hanno estensione:

    .blade.php

## View

File che contiene l’HTML da restituire al browser.

Le view Laravel stanno in:

    resources/views

## resources/views

Cartella dove stanno le view Laravel.

## resources/views/projects/create.blade.php

View Blade usata nel laboratorio per mostrare il form di creazione progetto.

## resources/views/projects/show.blade.php

View Blade usata nel laboratorio per mostrare un singolo progetto.

## projects.create

Nome dot notation della view di creazione progetto.

Corrisponde a:

    resources/views/projects/create.blade.php

## projects.show

Nome dot notation della view dettaglio progetto.

Corrisponde a:

    resources/views/projects/show.blade.php

## Dati della view

Dati passati dalla route o dal controller alla view tramite il secondo argomento di `view()`.

Esempio:

    return view('projects.show', [
        'project' => $project,
    ]);

## Passare un model alla view

Una route o un controller può passare un model Eloquent intero a una view.

Esempio:

    return view('projects.show', [
        'project' => $project,
    ]);

## Direttiva Blade

Istruzione Blade che inizia con `@`.

Esempio:

    @csrf

## @if

Direttiva Blade per mostrare contenuto solo se una condizione è vera.

## @endif

Direttiva Blade che chiude un blocco `@if`.

## {{ ... }}

Sintassi Blade per stampare un valore nella pagina.

Esempio:

    {{ $project->name }}

## Dot notation

Convenzione Laravel per indicare elementi annidati usando il punto.

Per le view:

    view('projects.show')

corrisponde a:

    resources/views/projects/show.blade.php

## Vite

Strumento frontend usato da Laravel per JavaScript e CSS moderni.

## Service provider

Classe che registra o configura servizi Laravel prima che l’applicazione gestisca le richieste.

## Middleware

Strato intermedio tra richiesta e risposta.

Può servire per autenticazione, sicurezza, sessioni, controlli e trasformazioni della richiesta.

Il controllo CSRF avviene tramite middleware.

## Debug

Modalità che mostra errori dettagliati agli sviluppatori.

In produzione deve essere disattivata.

Variabile tipica:

    APP_DEBUG=false

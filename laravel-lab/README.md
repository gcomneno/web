# Laravel Lab

Laboratorio didattico per imparare Laravel partendo da zero.

Il percorso segue lezioni video analizzate una alla volta:

1. trascrizione locale della lezione
2. spiegazione ragionata
3. pratica sul progetto locale
4. raccolta delle lesson learned in Markdown

## Struttura

- `first-project/` — primo progetto Laravel creato durante il laboratorio
- `lessons/` — appunti e lesson learned
- `scripts/` — script di supporto, ad esempio trascrizione video
- `GLOSSARY.md` — glossario minimo dei termini Laravel/PHP incontrati

## Sintesi di ripasso

- [Sintesi delle nozioni fondamentali — lezioni 01-15](lessons/summary-lessons-01-15.md)

## Lezioni

| Lezione | Argomento | File |
|---|---|---|
| 01 | Installare Laravel | `lessons/lesson-01-learned.md` |
| 02 | Primo giro nello scheletro del progetto | `lessons/lesson-02-learned.md` |
| 03 | Prime rotte Laravel | `lessons/lesson-03-learned.md` |
| 04 | Prime view Blade | `lessons/lesson-04-learned.md` |
| 05 | Blade e dati passati alla view | `lessons/lesson-05-learned.md` |
| 06 | Configurazione `.env` e `config()` | `lessons/lesson-06-learned.md` |
| 07 | Passaggio dalle closure ai controller | `lessons/lesson-07-learned.md` |
| 08 | Ripasso di Artisan e comandi principali | `lessons/lesson-08-learned.md` |
| 09 | Introduzione alle migration database | `lessons/lesson-09-learned.md` |
| 10 | Creazione dei model Laravel | `lessons/lesson-10-learned.md` |
| 11 | Primo CRUD con Eloquent | `lessons/lesson-11-learned.md` |
| 12 | Parametri route, request data e view dinamiche | `lessons/lesson-12-learned.md` |
| 13 | Route model binding e slug | `lessons/lesson-13-learned.md` |
| 14 | Invio form, CSRF e creazione progetto | `lessons/lesson-14-learned.md` |
| 15 | ProjectController e pulizia delle route | `lessons/lesson-15-learned.md` |

## Stato del progetto esempio

Il progetto `first-project/` segue passo passo le lezioni pratiche.

Al momento contiene:

- progetto Laravel installato e avviabile localmente
- prime rotte definite in `routes/web.php`
- homepage servita tramite view Blade
- view organizzata in `resources/views/pages/home.blade.php`
- dati passati dalla logica applicativa alla view
- titolo della pagina letto tramite `config('app.name')`
- homepage gestita da `HomeController`
- uso operativo di Artisan per esplorare comandi, route, ambiente, cache view e strumenti di generazione
- tabella `projects` definita tramite migration
- colonna `slug` aggiunta alla tabella `projects`
- model `Project` creato in `app/Models/Project.php`
- protezione mass assignment tramite `$fillable`
- route didattica `/eloquent` per osservare `Project::all()`
- esempi CRUD base con Eloquent
- route dinamica `/projects/{project:slug}`
- recupero progetto tramite route model binding
- view `resources/views/projects/show.blade.php`
- pagina dettaglio progetto con dati letti dal database
- uso di `created_at` e Carbon nella view
- view `resources/views/projects/create.blade.php`
- form `POST /projects` con protezione `@csrf`
- creazione progetto da dati della request
- generazione automatica dello slug con `str()->slug()`
- controller `ProjectController` con metodi `create`, `store` e `show`

## Come ripartire da zero

Questa sezione serve per chi clona il repository e vuole avviare il progetto Laravel localmente.

### 1. Clona il repository

    git clone https://github.com/gcomneno/web.git
    cd web/laravel-lab/first-project

### 2. Installa le dipendenze PHP

    composer install

### 3. Crea il file `.env`

    cp .env.example .env

### 4. Genera la chiave Laravel

    php artisan key:generate

### 5. Prepara SQLite

    touch database/database.sqlite

### 6. Esegui le migration

    php artisan migrate

### 7. Avvia il server locale

    php artisan serve

Poi apri nel browser:

    http://127.0.0.1:8000

## Pagine utili del laboratorio

Homepage:

    http://127.0.0.1:8000

Form creazione progetto:

    http://127.0.0.1:8000/projects/create

Dettaglio progetto tramite slug:

    http://127.0.0.1:8000/projects/a-first-project

Route didattica Eloquent:

    http://127.0.0.1:8000/eloquent

## Comandi utili durante il laboratorio

Mostrare tutti i comandi Artisan disponibili:

    php artisan

oppure:

    php artisan list

Mostrare informazioni sull’applicazione:

    php artisan about

Mostrare l’ambiente corrente:

    php artisan env

Chiedere aiuto su un comando:

    php artisan help make:controller

Mostrare le rotte registrate:

    php artisan route:list

Creare una view Blade semplice:

    php artisan make:view home

Creare una view Blade in sottocartella:

    php artisan make:view pages.home

Creare la view dettaglio progetto:

    php artisan make:view projects.show

Creare la view form progetto:

    php artisan make:view projects.create

Creare un controller:

    php artisan make:controller HomeController

Creare il controller dei progetti:

    php artisan make:controller ProjectController

Creare una migration:

    php artisan make:migration create_projects_table

Creare una migration per aggiungere una colonna:

    php artisan make:migration add_slug_to_projects_table

Eseguire le migration:

    php artisan migrate

Vedere lo stato delle migration:

    php artisan migrate:status

Annullare l’ultimo batch di migration in locale:

    php artisan migrate:rollback

Creare un model:

    php artisan make:model Project

Creare model e migration insieme:

    php artisan make:model Project -m

Pulire le view compilate:

    php artisan view:clear

Vedere i file view creati:

    find resources/views -maxdepth 3 -type f | sort

Vedere gli appunti delle lezioni:

    find laravel-lab/lessons -maxdepth 1 -type f | sort

Controllare che `.env` non sia tracciato da Git:

    git ls-files | grep -E '(^|/)\.env$' || true

Controllare che file locali pesanti o sensibili non siano tracciati:

    git ls-files | grep -E '(^|/)\.env$|database/database\.sqlite|vendor/|node_modules/|_work/|transcript\.txt$|\.mp4$|\.mp3$' || true

## Requisiti locali

Per usare il progetto servono:

- PHP 8.3+
- Composer
- Node.js
- npm

Per la prima esecuzione minimale non è necessario avere `vendor/` o `node_modules/` nel repository: vengono generati localmente tramite Composer/npm.

## Nota sui contenuti video e trascrizioni

I file video, audio e le trascrizioni integrali del corso non sono inclusi nel repository pubblico.

Sono presenti solo appunti, lesson learned, codice prodotto nel laboratorio e script di supporto.

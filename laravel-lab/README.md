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

## Lezioni

| Lezione | Argomento | File |
|---|---|---|
| 01 | Installare Laravel | `lessons/lesson-01/lesson-learned.md` |
| 02 | Primo giro nello scheletro del progetto | `lessons/lesson-02/lesson-learned.md` |
| 03 | Prime rotte Laravel | `lessons/lesson-03/lesson-learned.md` |
| 04 | Prime view Blade | `lessons/lesson-04/lesson-learned.md` |
| 05 | Blade e dati passati alla view | `lessons/lesson-05/lesson-learned.md` |
| 06 | Configurazione `.env` e `config()` | `lessons/lesson-06/lesson-learned.md` |
| 07 | Passaggio dalle closure ai controller | `lessons/lesson-07/lesson-learned.md` |
| 08 | Ripasso di Artisan e comandi principali | `lessons/lesson-08/lesson-learned.md` |

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

Creare un controller:

    php artisan make:controller HomeController

Pulire le view compilate:

    php artisan view:clear

Vedere i file view creati:

    find resources/views -maxdepth 3 -type f | sort

Controllare che `.env` non sia tracciato da Git:

    git ls-files | grep -E '(^|/)\.env$' || true

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

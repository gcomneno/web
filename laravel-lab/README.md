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

## Requisiti locali

Per usare il progetto servono:

- PHP 8.3+
- Composer
- Node.js
- npm

Per la prima esecuzione minimale non è necessario avere `vendor/` o `node_modules/` nel repository: vengono generati localmente tramite Composer/npm.

## Nota sui contenuti video e trascrizioni

I file video, audio e le trascrizioni integrali del corso non sono inclusi nel repository pubblico.

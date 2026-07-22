# Web-based Lab

Laboratorio personale per progetti legati al mondo web/internet.

Questo repository raccoglie esperimenti, appunti e piccoli progetti didattici legati allo sviluppo web.

## Sottoprogetti

| Cartella | Descrizione |
|---|---|
| `laravel-lab/` | Laboratorio didattico Laravel per principianti, seguito lezione per lezione. |

## Percorso Laravel

Il primo sottoprogetto attivo è `laravel-lab/`.

Da qui puoi partire se vuoi seguire il laboratorio Laravel da zero:

1. leggi `laravel-lab/README.md`
2. consulta `laravel-lab/GLOSSARY.md` quando incontri termini nuovi
3. segui le lezioni in ordine dentro `laravel-lab/lessons/`
4. avvia il progetto locale in `laravel-lab/first-project/`

## Stato attuale del laboratorio Laravel

Il percorso ha già coperto:

- installazione e setup locale
- primo giro nello scheletro del progetto
- prime route
- prime view Blade
- passaggio dati da route a view
- configurazione con `.env` e `config()`
- passaggio dalle closure ai controller
- ripasso operativo di Artisan e dei comandi principali
- introduzione alle migration database
- creazione del primo model Laravel
- primo assaggio di Eloquent
- operazioni CRUD base con Eloquent
- protezione mass assignment con `$fillable`
- parametri dinamici nelle route
- recupero dati da URL con `findOrFail()`
- passaggio di model Eloquent a view Blade
- uso base di Carbon per formattare date
- route model binding su ID e slug
- aggiunta della colonna `slug` alla tabella `projects`
- primo form POST con `@csrf`
- lettura dati da `Request`
- creazione di record dal form con `Project::create()`
- generazione slug con `str()->slug()`
- spostamento delle route progetto in `ProjectController`
- validazione dei form con `$request->validate()`
- visualizzazione errori di validazione con `@error`
- recupero valori precedenti del form con `old()`
- messaggi flash di successo con `with()` e `@session`

## Nota

I file video, audio e le trascrizioni integrali dei corsi non sono inclusi nel repository pubblico.

Sono presenti solo appunti, lesson learned, codice prodotto nel laboratorio e script di supporto.

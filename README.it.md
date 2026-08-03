# Web-based Lab

[English](README.md) | [Italiano](README.it.md)

Laboratorio personale per progetti legati al mondo web e Internet.

Questo repository raccoglie esperimenti, appunti e piccoli progetti didattici legati allo sviluppo web.

## Sottoprogetti

| Cartella | Descrizione |
|---|---|
| `laravel-lab/` | Laboratorio didattico Laravel per principianti, seguito lezione per lezione. |

## Percorso Laravel

Il primo sottoprogetto attivo è `laravel-lab/`.

Da qui puoi partire per seguire il laboratorio Laravel da zero:

1. leggi `laravel-lab/README.md`
2. consulta `laravel-lab/GLOSSARY.md` quando incontri termini nuovi
3. segui le lezioni in ordine dentro `laravel-lab/lessons/`
4. avvia il progetto locale in `laravel-lab/first-project/`

## Stato attuale del laboratorio Laravel

Il percorso ha già coperto:

- installazione e configurazione locale
- primo giro nello scheletro del progetto
- prime route
- prime view Blade
- passaggio dei dati dalle route alle view
- configurazione tramite `.env` e `config()`
- passaggio dalle closure ai controller
- ripasso operativo di Artisan e dei comandi principali
- introduzione alle migration del database
- creazione del primo model Laravel
- primo utilizzo di Eloquent
- operazioni CRUD di base con Eloquent
- protezione dal mass assignment tramite `$fillable`
- parametri dinamici nelle route
- recupero dei dati dall'URL con `findOrFail()`
- passaggio dei model Eloquent alle view Blade
- uso di base di Carbon per formattare le date
- route model binding tramite ID e slug
- aggiunta della colonna `slug` alla tabella `projects`
- primo form POST con `@csrf`
- lettura dei dati da `Request`
- creazione di record dal form con `Project::create()`
- generazione degli slug con `str()->slug()`
- spostamento delle route dei progetti in `ProjectController`
- validazione dei form con `$request->validate()`
- visualizzazione degli errori di validazione con `@error`
- recupero dei valori precedentemente inseriti con `old()`
- messaggi flash di successo con `with()` e `@session`

## Policy dei contenuti

I file video, audio e le trascrizioni integrali dei corsi non sono inclusi nel repository pubblico.

Sono pubblicati soltanto appunti originali, lesson learned, codice prodotto nel laboratorio e script di supporto.

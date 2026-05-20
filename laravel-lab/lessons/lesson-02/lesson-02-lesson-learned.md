# Getting Started with Laravel — Lezione 02
## A look around

Data laboratorio: 2026-05-08  
Corso: Getting Started with Laravel  
Episodio: 02 — A look around  
Durata video: circa 13 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è:

> fare un primo giro orientativo dentro lo scheletro di un nuovo progetto Laravel.

Questa lezione non serve ancora a padroneggiare ogni cartella o ogni file.

Serve invece a capire:

- dove si trova il codice applicativo
- cosa rappresenta lo scheletro del progetto
- dove stanno le configurazioni
- a cosa serve il file `.env`
- quali sono i primi concetti legati al database

---

## 2. Punto di partenza

Il docente riparte dalla creazione di un nuovo progetto Laravel:

laravel new my-project

Nel video sceglie:

| Domanda | Risposta |
|---|---|
| Starter kit | none |
| Testing framework | Pest o PHPUnit |
| Database | MySQL |
| npm install / npm build automatico | No |

Nota importante:

Nella prima lezione avevamo scelto SQLite per semplicità.  
In questa lezione il docente sceglie MySQL, ma precisa che non è obbligatorio seguirlo su questo punto.

Per il nostro laboratorio non serve ricreare il progetto con MySQL.  
Il progetto `first-project` con SQLite va benissimo per seguire i concetti della lezione.

---

## 3. Il progetto Laravel non è il framework Laravel

Questa è l’idea più importante della lezione:

> la cartella del progetto Laravel non è il framework Laravel.

La cartella:

first-project/

è lo scheletro della nostra applicazione.

Il framework vero e proprio viene installato da Composer e sta dentro:

vendor/laravel/framework

Quindi:

- il progetto è la nostra applicazione
- `vendor/` contiene il framework e le dipendenze
- Composer gestisce il codice esterno
- non bisogna modificare direttamente il framework dentro `vendor/`

Regola pratica:

> non modificare mai a mano `vendor/`.

Se vogliamo cambiare il comportamento dell’applicazione, lavoriamo nei file del progetto, non nei file del framework.

---

## 4. Lo scheletro iniziale può sembrare vuoto

Il docente spiega che un progetto Laravel appena creato sembra abbastanza “scarno”.

Questo è normale.

Molte cartelle o classi verranno create più avanti tramite Artisan, man mano che serviranno.

Esempio:

- controller
- policy
- model
- migration
- altri file applicativi

Quindi non bisogna spaventarsi se all’inizio alcune parti sembrano mancanti.

Laravel crea la struttura minima, poi la struttura cresce insieme all’applicazione.

---

## 5. Cartella `app/`

La cartella `app/` contiene gran parte del codice PHP applicativo.

È una delle cartelle in cui passeremo più tempo.

Struttura iniziale tipica:

app/
├── Http/
├── Models/
└── Providers/

### `app/Models`

Qui stanno i model.

I model rappresentano entità/dati dell’applicazione.

Esempi futuri:

- User
- Post
- Article
- Product

Per ora il docente li nomina soltanto.

### `app/Http/Controllers`

Qui stanno i controller.

I controller servono a gestire richieste HTTP in modo più organizzato.

Più avanti, invece di mettere logica direttamente nelle route, useremo controller dedicati.

### `app/Providers`

Qui stanno i service provider.

I service provider sono classi che registrano o configurano servizi dell’applicazione.

Per ora non serve capirli in profondità.  
Basta sapere che esistono e che fanno parte della fase di preparazione/configurazione dell’app.

---

## 6. Cartella `bootstrap/`

La cartella `bootstrap/` contiene file usati nella fase di avvio dell’applicazione.

Il file più importante citato nella lezione è:

bootstrap/app.php

In Laravel moderno questo file è diventato più rilevante.

Può contenere configurazioni relative a:

- routing
- middleware
- gestione delle eccezioni
- broadcasting / real time
- altre configurazioni iniziali del framework

Non è necessario padroneggiarlo subito.

Lesson learned pratica:

> sapere che `bootstrap/app.php` è un punto di configurazione iniziale dell’applicazione.

---

## 7. Service provider

Il docente cita anche i provider.

In particolare, nel progetto base troviamo:

app/Providers/AppServiceProvider.php

Un service provider può essere usato per registrare o avviare servizi prima che l’applicazione gestisca davvero le richieste.

Per ora non dobbiamo modificarlo.

Basta ricordare:

> se in futuro dovremo eseguire configurazioni globali dell’app, probabilmente passeremo anche dai provider.

---

## 8. Cartella `config/`

La cartella `config/` contiene molti file di configurazione Laravel.

Esempi:

config/app.php
config/database.php
config/cache.php
config/mail.php
config/session.php
config/services.php

Questi file controllano varie parti dell’applicazione.

Però il docente spiega una cosa importante:

> molti valori nei file di configurazione vengono letti dal file `.env`.

Quindi spesso non si modifica direttamente il valore nel file `config/*.php`, ma si modifica la variabile corrispondente nel `.env`.

---

## 9. File `.env`

Il file `.env` contiene valori specifici dell’ambiente in cui l’applicazione gira.

Esempi di ambienti:

| Ambiente | Significato |
|---|---|
| locale | macchina dello sviluppatore |
| produzione | server pubblico reale |
| staging | ambiente intermedio di prova |
| testing | ambiente usato per i test |

Esempi di variabili `.env`:

APP_NAME
APP_ENV
APP_DEBUG
DB_CONNECTION
DB_DATABASE
DB_USERNAME
DB_PASSWORD

### Perché esiste `.env`

Perché alcune configurazioni cambiano da ambiente ad ambiente.

Esempi:

- in locale `APP_DEBUG` può essere `true`
- in produzione `APP_DEBUG` deve essere `false`
- il database locale può essere diverso dal database di produzione
- password e chiavi API non devono stare nel codice

### Regola importantissima

> `.env` non va committato su GitHub.

Motivo:

può contenere dati sensibili come password, chiavi, token, credenziali e configurazioni private.

Nel nostro laboratorio lo abbiamo escluso tramite `.gitignore`.

---

## 10. Configurazione tramite `env()`

Il docente mostra il legame tra `config/` e `.env`.

Esempio tipico:

'name' => env('APP_NAME', 'Laravel'),

Significa:

> usa il valore `APP_NAME` dal file `.env`; se non esiste, usa `Laravel` come valore di fallback.

Quindi, se vogliamo cambiare il nome dell’applicazione, di solito modifichiamo:

APP_NAME=...

nel file `.env`.

Non conviene modificare direttamente il valore dentro `config/app.php` quando quel valore è pensato per cambiare tra ambienti.

---

## 11. Cartella `database/`

Il docente introduce tre concetti importanti:

database/
├── migrations/
├── factories/
└── seeders/

Non li approfondisce ancora, ma li presenta.

### Migrations

Le migrations definiscono lo schema del database.

In pratica descrivono:

- quali tabelle esistono
- quali colonne hanno
- quali indici ci sono
- eventuali vincoli o relazioni

Esempio:

una migration può creare la tabella `users`.

Le migrations permettono di versionare il database insieme al codice.

### Factories

Le factories generano dati finti.

Servono soprattutto per:

- test
- sviluppo
- popolamento rapido di dati realistici
- creare model finti senza scriverli a mano uno per uno

Esempio:

una factory può generare utenti finti con nome, email e password.

### Seeders

I seeders inseriscono dati iniziali nel database.

Servono per popolare il database con dati utili.

Esempi:

- creare utenti demo
- creare categorie iniziali
- preparare dati comuni per un nuovo sviluppatore
- riempire l’ambiente locale con dati prevedibili

---

## 12. Pratica fatta sul nostro progetto

La pratica della lezione è stata fatta sul progetto:

~/Progetti/web/laravel-lab/first-project

Comandi utili per esplorare i file citati dalla lezione:

Entrare nel progetto:

cd ~/Progetti/web/laravel-lab/first-project

Vedere la struttura principale:

tree -L 2 app bootstrap config database routes resources

Controllare dove sta il framework Laravel:

composer show laravel/framework | sed -n '1,40p'

ls -ld vendor/laravel/framework

Guardare `bootstrap/app.php`:

sed -n '1,200p' bootstrap/app.php

Guardare `config/app.php`:

sed -n '1,160p' config/app.php

Guardare alcune variabili `.env`, senza pubblicare il file:

grep -E '^(APP_NAME|APP_ENV|APP_DEBUG|DB_CONNECTION|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=' .env

Guardare le migrations iniziali:

ls -lh database/migrations

sed -n '1,160p' database/migrations/0001_01_01_000000_create_users_table.php

Guardare factory e seeder:

sed -n '1,160p' database/factories/UserFactory.php

sed -n '1,160p' database/seeders/DatabaseSeeder.php

---

## 13. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare davvero a usare:

- controller
- model
- service provider
- middleware
- broadcasting
- migrations in dettaglio
- factories in dettaglio
- seeders in dettaglio
- MySQL in modo operativo

Il docente li nomina per orientarsi nello scheletro del progetto.

Le spiegazioni operative arriveranno più avanti.

---

## 14. Lesson Learned

### 1. La cartella del progetto non è il framework

Il progetto Laravel è lo scheletro dell’applicazione.

Il framework vero sta dentro `vendor/`, installato da Composer.

---

### 2. `vendor/` non si modifica a mano

`vendor/` contiene codice esterno gestito da Composer.

Se modifichiamo file lì dentro, le modifiche possono essere perse al prossimo aggiornamento o reinstallazione.

---

### 3. `app/` contiene il codice principale dell’app

Dentro `app/` finirà gran parte della logica applicativa.

Per ora contiene model, controller di base e provider.

---

### 4. Il progetto cresce usando Artisan

Un progetto Laravel appena creato può sembrare vuoto.

Molte cartelle/classi verranno create più avanti tramite comandi Artisan.

---

### 5. `bootstrap/app.php` è configurazione iniziale

In Laravel moderno `bootstrap/app.php` è un file importante per configurare routing, middleware, eccezioni e altre parti del framework.

Non serve dominarlo subito, ma bisogna sapere che esiste.

---

### 6. `config/` contiene le configurazioni Laravel

La cartella `config/` raccoglie file di configurazione dell’app.

Molti valori però arrivano dal file `.env`.

---

### 7. `.env` contiene valori specifici dell’ambiente

`.env` cambia tra locale, produzione, staging e testing.

Può contenere anche dati sensibili.

Non va committato.

---

### 8. `env()` legge valori dal file `.env`

Nei file `config/*.php` si trovano spesso chiamate tipo:

env('APP_NAME', 'Laravel')

Il secondo valore è il fallback usato se la variabile non esiste.

---

### 9. Le migrations descrivono il database

Le migrations definiscono tabelle, colonne e struttura del database.

Sono il modo Laravel di versionare lo schema del database.

---

### 10. Factories e seeders servono per dati finti o iniziali

Le factories generano dati finti.

I seeders popolano il database con dati iniziali o demo.

---

## 15. Comandi riassuntivi

Entrare nel progetto:

cd ~/Progetti/web/laravel-lab/first-project

Vedere struttura principale:

tree -L 2 app bootstrap config database routes resources

Controllare framework Laravel:

composer show laravel/framework | sed -n '1,40p'

ls -ld vendor/laravel/framework

Leggere bootstrap/app.php:

sed -n '1,200p' bootstrap/app.php

Leggere config/app.php:

sed -n '1,160p' config/app.php

Leggere variabili `.env` selezionate:

grep -E '^(APP_NAME|APP_ENV|APP_DEBUG|DB_CONNECTION|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=' .env

Leggere migrations:

ls -lh database/migrations

sed -n '1,160p' database/migrations/0001_01_01_000000_create_users_table.php

Leggere factory e seeder:

sed -n '1,160p' database/factories/UserFactory.php

sed -n '1,160p' database/seeders/DatabaseSeeder.php

---

## 16. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- la differenza tra progetto Laravel e framework Laravel
- il ruolo di `vendor/`
- perché non modificare il framework direttamente
- a cosa serve la cartella `app/`
- a cosa serve `bootstrap/app.php`
- a cosa serve `config/`
- il ruolo del file `.env`
- perché `.env` non va su GitHub
- cosa sono migrations, factories e seeders a livello introduttivo

Obiettivo raggiunto:

> abbiamo fatto un primo giro orientativo nello scheletro di un progetto Laravel.

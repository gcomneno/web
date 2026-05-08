# Getting Started with Laravel — Lezione 01
## Let’s install Laravel

Data: 2026-05-01  
Corso: Getting Started with Laravel  
Episodio: 01 — Let’s install Laravel  
Durata video: circa 12 minuti  
Framework installato durante la lezione: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo del primo episodio è molto semplice:

> installare Laravel, creare un primo progetto locale e avviarlo nel browser.

Il video non entra ancora in routing avanzato, controller, Blade, database applicativo, modelli o validazione.  
Questa lezione serve a preparare l’ambiente e verificare che Laravel funzioni.

---

## 2. Dipendenze richieste

Laravel richiede alcune dipendenze di base:

- PHP
- Composer
- Node.js
- npm

Nel nostro caso iniziale erano presenti solo Node e npm.

Verifiche usate:

php -v

composer --version

node -v

npm -v

Risultato iniziale:

- PHP mancante
- Composer mancante
- Node presente
- npm presente

---

## 3. Installazione PHP e Composer

Su Ubuntu 24.04 abbiamo installato PHP 8.3, Composer e alcune estensioni utili a Laravel.

Comando usato:

sudo apt update

sudo apt install php8.3-cli php8.3-xml php8.3-mbstring php8.3-sqlite3 php8.3-curl unzip composer

Estensioni importanti controllate:

php -m | grep -E 'mbstring|xml|sqlite3|curl'

Risultato ottenuto:

- PHP 8.3.6
- Composer 2.7.1
- curl presente
- libxml presente
- mbstring presente
- sqlite3 presente
- xml presente
- xmlreader presente
- xmlwriter presente

Nota importante:

Laravel 13 richiede PHP 8.3, quindi installare PHP 8.3 è stata la scelta corretta.

---

## 4. Installazione Laravel Installer

Laravel Installer serve per creare nuovi progetti Laravel da terminale.

Comando usato:

composer global require laravel/installer

Versione installata:

Laravel Installer 5.26.1

Problema incontrato:

laravel: comando non trovato

Causa:

Composer installa i binari globali in una directory che non era ancora presente nel PATH.

Directory rilevata:

/home/baltimora/.config/composer/vendor/bin

Correzione applicata:

echo 'export PATH="$HOME/.config/composer/vendor/bin:$PATH"' >> ~/.bashrc

source ~/.bashrc

Verifica finale:

laravel --version

Risultato:

Laravel Installer 5.26.1

---

## 5. Creazione del progetto Laravel

Abbiamo creato il primo progetto Laravel.

Cartella di lavoro scelta:

~/Progetti/web

Comando:

laravel new first-project

Scelte fatte durante la creazione:

- Starter kit: none
- Testing framework: Pest
- Database: SQLite
- npm install / npm run build automatico: No

Motivo della scelta “none” come starter kit:

Per la prima lezione è meglio evitare Vue, React, Livewire o altri starter kit.  
Lo scopo è capire Laravel puro prima di aggiungere un frontend framework.

Motivo della scelta SQLite:

SQLite permette di iniziare senza installare o configurare MySQL, MariaDB o PostgreSQL.  
Il database è un singolo file locale:

database/database.sqlite

---

## 6. Messaggio finale del Laravel Installer

Dopo la creazione Laravel ha mostrato:

Application ready in [first-project]. You can start your local development using:

cd first-project

npm install --ignore-scripts && npm run build

composer run dev

Nota:

Il video usava soprattutto:

php artisan serve

Laravel moderno propone anche:

composer run dev

La differenza è importante:

php artisan serve

avvia solo il server locale Laravel.

composer run dev

avvia più processi insieme, tra cui:

- php artisan serve
- queue listener
- pail per i log
- npm run dev per Vite

Per questa prima lezione abbiamo scelto l’avvio minimale.

---

## 7. Avvio minimale del progetto

Siamo entrati nel progetto:

cd first-project

Poi abbiamo avviato Laravel con:

php artisan serve

Il progetto è stato aperto nel browser all’indirizzo locale:

http://127.0.0.1:8000

Risultato:

Laravel avviato correttamente.

Obiettivo principale della lezione raggiunto.

---

## 8. Verifica versione Laravel

Comando:

php artisan --version

Risultato:

Laravel Framework 13.7.0

Comando più dettagliato:

composer show laravel/framework

Informazioni importanti ricavate:

- Framework: Laravel 13.7.0
- Release: 2026-04-28
- PHP richiesto: ^8.3
- Percorso framework nel progetto: vendor/laravel/framework

Regola importante:

Non modificare mai direttamente la cartella vendor/.

La cartella vendor/ contiene il framework e le dipendenze installate da Composer.  
Se viene modificata a mano, le modifiche possono essere perse al prossimo composer install o composer update.

---

## 9. Lettura degli script Composer

Abbiamo letto gli script presenti in composer.json.

Comando corretto con jq:

jq '.scripts' composer.json

Errore iniziale:

jq "scripts"

Perché era sbagliato:

In jq, per accedere a una proprietà JSON serve il punto davanti al nome del campo.

Forma corretta:

jq '.scripts' composer.json

Altri esempi utili:

jq '.scripts.dev' composer.json

jq '.scripts.setup' composer.json

jq '.scripts | keys' composer.json

Script importanti trovati:

setup:
- composer install
- copia .env.example in .env se manca
- genera la chiave applicativa
- esegue le migration
- installa dipendenze npm
- esegue la build frontend

dev:
- avvia server Laravel
- avvia queue listener
- avvia pail
- avvia Vite

test:
- pulisce la config
- esegue i test Laravel

post-create-project-cmd:
- genera la application key
- crea database/database.sqlite se manca
- esegue le migration iniziali

---

## 10. Struttura del progetto Laravel

Comando usato:

tree -L 2

Cartelle principali viste:

app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
vendor/

File principali:

artisan
composer.json
composer.lock
package.json
phpunit.xml
vite.config.js

---

## 11. Significato delle cartelle principali

### app/

Contiene il codice PHP dell’applicazione.

Sottocartelle iniziali:

- app/Http
- app/Models
- app/Providers

Uso generale:

- app/Http: codice legato alle richieste HTTP, come controller e middleware
- app/Models: modelli Eloquent
- app/Providers: service provider Laravel

---

### routes/

Contiene le rotte dell’applicazione.

File visto:

routes/web.php

Questo file definisce cosa succede quando un utente visita un certo URL web.

---

### resources/

Contiene risorse sorgente usate dall’applicazione.

Sottocartelle iniziali:

- resources/css
- resources/js
- resources/views

La cartella più importante per ora è:

resources/views

Qui stanno le view Blade.

---

### database/

Contiene file e cartelle legate al database.

Elementi importanti:

- database/database.sqlite
- database/migrations
- database/factories
- database/seeders

SQLite è stato scelto per semplicità.

---

### public/

È la porta d’ingresso pubblica dell’applicazione.

File chiave:

public/index.php

In Laravel le richieste web passano da questo file.

---

### config/

Contiene i file di configurazione.

Esempi:

- config/app.php
- config/database.php
- config/cache.php
- config/mail.php
- config/session.php

All’inizio conviene guardarli, ma modificarli solo se necessario.

---

### storage/

Contiene file generati a runtime.

Esempi:

- log
- cache
- sessioni
- viste compilate
- file salvati dall’applicazione

File utile in caso di errori:

storage/logs/laravel.log

---

### vendor/

Contiene Laravel e tutte le dipendenze Composer.

Regola:

Non modificare vendor/ a mano.

---

### artisan

È il comando CLI di Laravel.

Esempi:

php artisan serve

php artisan migrate

php artisan route:list

php artisan test

---

## 12. Prima rotta Laravel

Contenuto di routes/web.php:

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Spiegazione:

Quando arriva una richiesta GET all’indirizzo /, Laravel esegue la funzione anonima e restituisce la view welcome.

Traduzione pratica:

/ -> resources/views/welcome.blade.php

Laravel non richiede di scrivere .blade.php dentro view('welcome').

Laravel cerca automaticamente:

resources/views/welcome.blade.php

---

## 13. Laravel Herd

Il video mostra anche Laravel Herd come alternativa più completa e grafica.

Herd può gestire:

- PHP
- Node
- siti locali .test
- servizi aggiuntivi
- ambiente più completo per lo sviluppo Laravel

Decisione presa nella lezione:

Non usare Herd per ora.

Motivo:

Per imparare Laravel da zero è meglio partire dal setup minimale:

- PHP
- Composer
- Node
- Laravel Installer
- SQLite
- php artisan serve

Herd può essere utile più avanti, ma all’inizio aggiunge complessità non necessaria.

---

## 14. Lesson Learned operative

### 1. Per iniziare Laravel non serve tutto il mondo

Non servono subito:

- Docker
- MySQL
- Nginx
- Laravel Herd
- Vue
- React
- Livewire

Per partire bastano:

- PHP
- Composer
- Node/npm
- Laravel Installer
- SQLite
- php artisan serve

---

### 2. Laravel moderno può essere più avanti del corso

Il video mostrava un flusso leggermente diverso, ma il progetto creato ora usa Laravel 13.7.0.

Il messaggio generato dal Laravel Installer suggerisce composer run dev, ma per replicare la lezione basta php artisan serve.

---

### 3. Composer global richiede attenzione al PATH

Se dopo composer global require laravel/installer il comando laravel non viene trovato, controllare:

composer global config bin-dir --absolute

Poi aggiungere quella directory al PATH.

Nel nostro caso:

export PATH="$HOME/.config/composer/vendor/bin:$PATH"

---

### 4. SQLite è ideale per imparare

SQLite evita la configurazione di un server database.  
Laravel crea o usa un file locale:

database/database.sqlite

Questo rende il primo setup molto più semplice.

---

### 5. vendor/ non si tocca

vendor/ contiene codice gestito da Composer.

Se si vuole cambiare il comportamento dell’applicazione, si lavora nel codice dell’app, non dentro vendor/.

---

### 6. routes/web.php è il primo punto da capire

La prima rotta mostra il meccanismo base:

URL -> funzione -> view

Nel caso iniziale:

/ -> view('welcome') -> resources/views/welcome.blade.php

---

## 15. Comandi riassuntivi della lezione

Verifica strumenti:

php -v

composer --version

node -v

npm -v

Installazione dipendenze Ubuntu:

sudo apt update

sudo apt install php8.3-cli php8.3-xml php8.3-mbstring php8.3-sqlite3 php8.3-curl unzip composer

Verifica estensioni PHP:

php -m | grep -E 'mbstring|xml|sqlite3|curl'

Installazione Laravel Installer:

composer global require laravel/installer

Verifica bin Composer globale:

composer global config bin-dir --absolute

Aggiunta PATH:

echo 'export PATH="$HOME/.config/composer/vendor/bin:$PATH"' >> ~/.bashrc

source ~/.bashrc

Verifica Laravel Installer:

laravel --version

Creazione progetto:

laravel new first-project

Entrare nel progetto:

cd first-project

Avvio minimale:

php artisan serve

Verifica versione Laravel:

php artisan --version

Dettagli framework:

composer show laravel/framework

Lettura script Composer:

jq '.scripts' composer.json

Struttura progetto:

tree -L 2

Prima rotta:

cat routes/web.php

---

## 16. Stato finale

Alla fine della lezione abbiamo:

- installato PHP 8.3
- installato Composer
- verificato Node e npm
- installato Laravel Installer
- creato il progetto first-project
- usato SQLite
- avviato Laravel con php artisan serve
- aperto Laravel nel browser
- verificato Laravel Framework 13.7.0
- letto composer.json
- letto la struttura del progetto
- spiegato routes/web.php

Obiettivo lezione 1 completato.

# Getting Started with Laravel — Lezione 01
## Let’s install Laravel

Data laboratorio: 2026-05-01  
Corso: Getting Started with Laravel  
Episodio: 01 — Let’s install Laravel  
Durata video: circa 12 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è uno solo:

> riuscire a creare un nuovo progetto Laravel in locale e aprirlo nel browser.

Questa lezione non serve ancora a imparare routing, controller, Blade, model, database applicativo o struttura interna del progetto.

Il punto è preparare l’ambiente minimo e verificare che Laravel parta.

---

## 2. Cosa serve per installare Laravel

Laravel è scritto in PHP, quindi serve prima di tutto un ambiente PHP funzionante.

Gli strumenti fondamentali sono:

- PHP
- Composer
- Node.js
- npm

### PHP

PHP è il linguaggio su cui gira Laravel.

Verifica:

php -v

Nel nostro laboratorio abbiamo installato PHP 8.3.

Laravel 13 richiede PHP 8.3, quindi questa versione è corretta.

### Composer

Composer è il gestore delle dipendenze PHP.

Serve per:

- installare Laravel
- installare i pacchetti richiesti dal framework
- gestire le librerie PHP del progetto

Verifica:

composer --version

### Node.js e npm

Node.js e npm servono per la parte frontend, cioè JavaScript e CSS.

Anche se nella prima lezione non entriamo davvero nel frontend, Laravel li usa per strumenti come Vite.

Verifiche:

node -v

npm -v

---

## 3. Stato iniziale del nostro ambiente

All’inizio del laboratorio avevamo:

- Node.js già installato
- npm già installato
- PHP mancante
- Composer mancante

Abbiamo quindi installato PHP, Composer e alcune estensioni utili.

Comando usato su Ubuntu 24.04:

sudo apt update

sudo apt install php8.3-cli php8.3-xml php8.3-mbstring php8.3-sqlite3 php8.3-curl unzip composer

Verifica delle estensioni PHP:

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

---

## 4. Laravel Installer

Per creare nuovi progetti Laravel da terminale si può installare Laravel Installer.

Comando:

composer global require laravel/installer

Verifica:

laravel --version

Nel nostro caso:

Laravel Installer 5.26.1

---

## 5. Problema incontrato: comando `laravel` non trovato

Dopo l’installazione, il comando:

laravel --version

inizialmente non funzionava.

Errore:

laravel: comando non trovato

Il problema era il PATH.

Composer installa i comandi globali in una directory specifica, che deve essere raggiungibile dalla shell.

Comando per scoprire la directory:

composer global config bin-dir --absolute

Nel nostro caso:

/home/baltimora/.config/composer/vendor/bin

Abbiamo aggiunto quella directory al PATH:

echo 'export PATH="$HOME/.config/composer/vendor/bin:$PATH"' >> ~/.bashrc

source ~/.bashrc

Dopo questa correzione:

laravel --version

ha funzionato.

---

## 6. Creazione del primo progetto Laravel

Il video mostra la creazione di un nuovo progetto Laravel.

Nel nostro laboratorio abbiamo creato:

first-project

Comando:

laravel new first-project

Durante la creazione del progetto Laravel fa alcune domande.

Scelte fatte:

| Domanda | Risposta |
|---|---|
| Starter kit | none |
| Testing framework | Pest |
| Database | SQLite |
| npm install / npm build automatico | No |

---

## 7. Perché scegliere `none` come starter kit

Per la prima lezione abbiamo scelto:

none

Motivo:

> l’obiettivo è imparare Laravel base, senza aggiungere subito Vue, React, Livewire o altri strumenti.

Uno starter kit frontend aggiunge molte cose:

- componenti frontend
- autenticazione
- Vite più centrale
- file JavaScript aggiuntivi
- più complessità iniziale

Per partire da zero è meglio evitare.

---

## 8. Perché scegliere SQLite

Il video propone SQLite come scelta semplice per iniziare.

SQLite è un database basato su file.

Invece di dover configurare un server MySQL o PostgreSQL, Laravel può usare un singolo file locale:

database/database.sqlite

Vantaggio:

> si può iniziare subito senza configurare un database server.

Per una prima installazione è perfetto.

---

## 9. npm install / npm build: perché abbiamo risposto No

Laravel chiede se vogliamo eseguire automaticamente:

npm install

e una build frontend.

Nel video il docente preferisce rispondere No e farlo eventualmente a mano dopo.

Abbiamo seguito la stessa logica.

Motivo:

> nella prima lezione vogliamo capire cosa stiamo facendo, non far partire troppi automatismi.

Per avviare Laravel in modo minimale non ci serve ancora lavorare davvero con Vite o frontend build.

---

## 10. Entrare nel progetto

Dopo la creazione del progetto:

cd first-project

Nel nostro laboratorio il progetto è finito poi dentro:

~/Progetti/web/laravel-lab/first-project

---

## 11. Avvio minimale con Artisan

Laravel include un comando CLI chiamato `artisan`.

Per avviare il server locale:

php artisan serve

Questo avvia un server di sviluppo PHP.

L’app diventa disponibile nel browser su un indirizzo simile a:

http://127.0.0.1:8000

Nel nostro caso l’avvio minimale è riuscito.

Questo è il punto principale della lezione:

> Laravel è installato, il progetto esiste e risponde nel browser.

---

## 12. `php artisan serve` vs `composer run dev`

Il progetto Laravel moderno può suggerire:

composer run dev

Questo comando avvia più processi insieme, per esempio:

- server Laravel
- queue listener
- log viewer
- Vite

Per la prima lezione però abbiamo scelto il percorso più semplice:

php artisan serve

Motivo:

> serve solo verificare che Laravel funzioni.

`composer run dev` sarà utile più avanti, quando avremo bisogno anche del frontend e degli altri processi.

---

## 13. Laravel Herd

Il video mostra anche Laravel Herd.

Laravel Herd è un’applicazione che aiuta a gestire un ambiente Laravel locale più completo.

Può offrire:

- siti locali con dominio `.test`
- gestione PHP
- integrazione con servizi aggiuntivi
- ambiente più comodo per chi sviluppa spesso con Laravel

Il docente però dice una cosa importante:

> se sei nuovo a PHP, ambienti di sviluppo e Laravel, puoi tranquillamente iniziare con la command line e `php artisan serve`.

Nel nostro laboratorio abbiamo seguito questa scelta.

Non abbiamo usato Herd.

---

## 14. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo spiegare:

- struttura completa del progetto Laravel
- cartella `app/`
- cartella `routes/`
- `routes/web.php`
- Blade
- controller
- model
- migrations in dettaglio
- factories
- seeders
- service provider
- `vendor/`

Questi argomenti appartengono alle lezioni successive.

---

## 15. Lesson Learned

### 1. Laravel richiede prima un ambiente PHP funzionante

Prima di pensare al framework, bisogna verificare:

php -v

composer --version

node -v

npm -v

Senza PHP e Composer non si parte.

---

### 2. Composer è il gestore delle dipendenze PHP

Composer sta a PHP un po’ come npm sta a JavaScript.

Serve per installare Laravel e le sue dipendenze.

---

### 3. Laravel Installer permette di creare progetti da terminale

Dopo averlo installato con Composer:

composer global require laravel/installer

si può creare un progetto con:

laravel new nome-progetto

---

### 4. Se `laravel` non viene trovato, controllare il PATH

Il comando `laravel` può essere installato ma non raggiungibile dalla shell.

Controllare con:

composer global config bin-dir --absolute

Poi aggiungere quella directory al PATH.

---

### 5. Per iniziare conviene scegliere `none` come starter kit

Starter kit come Vue, React o Livewire sono utili, ma aggiungono complessità.

Per la prima installazione è meglio Laravel pulito.

---

### 6. SQLite è la scelta più semplice per imparare

SQLite evita di installare subito MySQL o PostgreSQL.

Per una prima app Laravel è più che sufficiente.

---

### 7. Non serve fare subito la build frontend

Per verificare che Laravel parta, non serve ancora concentrarsi su npm, Vite o build CSS/JS.

`php artisan serve` basta.

---

### 8. `php artisan serve` è il modo più semplice per vedere Laravel nel browser

Comando:

php artisan serve

URL tipico:

http://127.0.0.1:8000

Se la pagina Laravel si apre nel browser, la lezione ha raggiunto il suo obiettivo.

---

### 9. Herd è utile, ma non necessario per iniziare

Laravel Herd può semplificare ambienti più completi, ma per imparare da zero non è obbligatorio.

Prima command line, poi eventualmente strumenti più comodi.

---

## 16. Comandi riassuntivi

Verifica strumenti:

php -v

composer --version

node -v

npm -v

Installazione dipendenze su Ubuntu 24.04:

sudo apt update

sudo apt install php8.3-cli php8.3-xml php8.3-mbstring php8.3-sqlite3 php8.3-curl unzip composer

Verifica estensioni PHP:

php -m | grep -E 'mbstring|xml|sqlite3|curl'

Installazione Laravel Installer:

composer global require laravel/installer

Controllo directory binaria globale Composer:

composer global config bin-dir --absolute

Aggiunta al PATH:

echo 'export PATH="$HOME/.config/composer/vendor/bin:$PATH"' >> ~/.bashrc

source ~/.bashrc

Verifica Laravel Installer:

laravel --version

Creazione progetto:

laravel new first-project

Entrare nel progetto:

cd first-project

Avvio server locale:

php artisan serve

---

## 17. Stato finale della lezione

Alla fine della lezione siamo arrivati a questo risultato:

- PHP installato
- Composer installato
- Node e npm verificati
- Laravel Installer installato
- comando `laravel` funzionante
- progetto `first-project` creato
- SQLite scelto come database semplice
- progetto avviato con `php artisan serve`
- Laravel visibile nel browser

Obiettivo raggiunto:

> Laravel è installato e il primo progetto funziona localmente.

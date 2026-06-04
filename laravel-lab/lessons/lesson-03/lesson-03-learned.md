# Getting Started with Laravel — Lezione 03

## First route

Data laboratorio: 2026-05-19  
Corso: Getting Started with Laravel  
Episodio: 03 — First route  
Durata video: circa 10 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è:
> scrivere la prima rotta Laravel e capire come una richiesta HTTP entra nell’applicazione.

Nelle lezioni precedenti abbiamo installato Laravel e guardato lo scheletro del progetto.  
Ora iniziamo finalmente a scrivere codice.

Il punto centrale è questo:
> una richiesta web parte da una rotta.

In modo molto semplificato:

```text
Browser
  ↓
URL richiesto
  ↓
routes/web.php
  ↓
codice associato alla rotta
  ↓
risposta HTTP
```

---

## 2. Dove si scrivono le rotte web

Le rotte web principali stanno nel file:

```text
routes/web.php
```

In un progetto Laravel appena creato troviamo una rotta iniziale simile a questa:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
```

Questa rotta dice:
> quando arriva una richiesta `GET` all’URL `/`, esegui questa funzione e restituisci la view `welcome`.

---

## 3. HTTP verbs: GET, POST, PUT, PATCH, DELETE

Laravel permette di definire rotte per vari "verbi HTTP".

Esempi:

```php
Route::get(...);
Route::post(...);
Route::put(...);
Route::patch(...);
Route::delete(...);
```

Per questa lezione usiamo solo:

```php
Route::get(...);
```

`GET` è il verbo tipico usato dal browser quando apriamo una pagina.

Esempio:

```text
http://127.0.0.1:8000/
```

è normalmente una richiesta `GET`.

---

## 4. Prima rotta `GET`

Una rotta minimale è fatta così:

```php
Route::get('/', function () {
    return response('home');
});
```

I due pezzi importanti sono:

| Pezzo | Significato     |
|-------|-----------------|
| `'/'` | URL della rotta |
| `function () { ... }`   | codice da eseguire quando quella rotta viene richiesta |

Questa rotta risponde con testo semplice:

```text
home
```

---

## 5. Approfondimento: Facade

Nel file `routes/web.php` troviamo:

```php
use Illuminate\Support\Facades\Route;
```

e poi:

```php
Route::get('/', function () {
    return response('home');
});
```

A prima vista sembra una normale chiamata statica PHP:

```php
Route::get(...)
```

Ma in Laravel `Route` è una **facade**.

### Cosa significa facade

Una facade è una classe che offre una sintassi comoda e breve per accedere a un servizio gestito da Laravel.

In pratica:

```php
Route::get(...)
```

sembra una chiamata statica, ma dietro le quinte Laravel inoltra quella chiamata a un oggetto reale già configurato nel container dell’applicazione.
Quindi non dobbiamo immaginare che tutta la logica viva davvero nella classe `Route`.

La facade è una specie di “sportello di comodo” davanti a un servizio più complesso.

### Immagine mentale

Senza facade potremmo immaginare qualcosa di più verboso:

```php
$router = ...; // recuperato dal container Laravel
$router->get('/', ...);
```

Con la facade scriviamo invece:

```php
Route::get('/', ...);
```

Laravel si occupa di recuperare il router vero.

### Perché Laravel usa le facade

Le facade rendono il codice:

- breve
- leggibile
- facile da scrivere
- uniforme in tutto il framework

Esempi di facade o API simili che incontreremo spesso:

```php
Route::get(...)
Config::get(...)
Cache::get(...)
Storage::put(...)
Log::info(...)
```

### Cosa NON devi pensare

Non devi pensare:

> “Laravel è pieno di metodi statici buttati lì.”

Meglio pensare:

> “Laravel mi offre accessi comodi a servizi già configurati.”

### Lesson learned sulle facade

Per ora non serve sapere come sono implementate internamente.

Serve solo capire che:

> `Route::get()` sembra statico, ma dietro usa il router reale di Laravel.

---

## 6. Approfondimento: Closure

Nella rotta:

```php
Route::get('/', function () {
    return response('home');
});
```

il secondo argomento è una **closure**.

### Cos’è una closure

Una closure è una funzione anonima.

Anonima significa:

> una funzione senza nome, definita direttamente nel punto in cui serve.

Esempio:

```php
function () {
    return response('home');
}
```

Questa funzione viene passata a Laravel.

Laravel la eseguirà quando arriverà una richiesta per quella rotta.

### Perché usare una closure nelle prime rotte

Le closure sono comode per esempi piccoli.

Per una pagina semplice possiamo scrivere:

```php
Route::get('/about', function () {
    return response('about');
});
```

È immediato e leggibile.

### Limite delle closure

Le closure vanno bene all’inizio, ma non sono ideali quando la logica cresce.

Se dentro una rotta iniziamo a mettere:

- query al database
- validazione
- chiamate a servizi
- logica di business
- gestione di form

il file `routes/web.php` diventa disordinato.

Più avanti useremo i **controller**.

La stessa idea diventerà qualcosa del tipo:

```php
Route::get('/about', [PageController::class, 'about']);
```

Per ora non serve ancora farlo.

### Closure e variabili esterne

In PHP una closure può anche “catturare” variabili esterne con `use`.

Esempio generale:

```php
$name = 'Laravel';

Route::get('/hello', function () use ($name) {
    return response("Hello {$name}");
});
```

Qui la closure usa una variabile definita fuori.

Non è il focus della lezione, ma aiuta a capire che una closure è una funzione vera, non una magia di Laravel.

### Lesson learned sulle closure

Per questa fase basta ricordare:

> una closure è il codice che Laravel esegue quando la rotta viene raggiunta.

---

## 7. Debug veloce: `dump()` e `dd()`

Il docente introduce due strumenti utili durante lo sviluppo:

```php
dump(...)
dd(...)
```

### `dump()`

`dump()` stampa il contenuto di una variabile o valore, ma lascia continuare l’esecuzione.

Esempio:

```php
Route::get('/', function () {
    dump('first route');

    return response('home');
});
```

La pagina mostrerà il dump e poi continuerà a produrre la risposta.

### `dd()`

`dd()` significa:

```text
dump and die
```

Cioè:

> stampa il valore e ferma l’esecuzione.

Esempio:

```php
Route::get('/', function () {
    dd('first route');

    return response('home');
});
```

In questo caso:

```php
return response('home');
```

non verrà mai eseguito.

### Quando usare `dd()`

`dd()` è utile per debug rapido mentre sviluppiamo.

Però non deve restare nel codice finale.

È uno strumento da laboratorio, non da produzione.

### Lesson learned su `dd()`

Se una pagina “si ferma” improvvisamente mostrando un dump, probabilmente c’è un `dd()` rimasto nel codice.

---

## 8. Rispondere a una richiesta con `response()`

Una rotta può restituire una risposta semplice:

```php
Route::get('/', function () {
    return response('home');
});
```

`response()` è un **helper function** Laravel.

Sembra una funzione semplice, ma dietro costruisce una vera risposta HTTP.

Una risposta HTTP può avere:

- contenuto
- status code
- header

Esempio con status code esplicito:

```php
Route::get('/', function () {
    return response('home', 200);
});
```

Qui:

| Valore   | Significato |
|----------|-------------|
| `'home'` | contenuto della risposta |
| `200`    | status code HTTP OK |

### `response()` non è una semplice echo

Questa cosa è importante.

Scrivere:

```php
return response('home', 200);
```

non è uguale mentalmente a fare solo:

```php
echo 'home';
```

Laravel sta creando una risposta HTTP corretta.

Per questo più avanti potremo gestire meglio:

- status code
- redirect
- JSON
- header
- cookie
- download
- view

### Lesson learned su `response()`

Per ora basta sapere:

> `response()` è il modo Laravel per costruire una risposta HTTP esplicita.

---

## 9. Creare più rotte

Possiamo creare una rotta per la homepage:

```php
Route::get('/', function () {
    return response('home', 200);
});
```

e una rotta per una pagina about:

```php
Route::get('/about', function () {
    return response('about', 200);
});
```

A questo punto:

```text
http://127.0.0.1:8000/
```

risponde:

```text
home
```

mentre:

```text
http://127.0.0.1:8000/about
```

risponde:

```text
about
```

Se visitiamo un URL non definito, Laravel risponde con un errore 404.

---

## 10. Dare un nome alle rotte

Laravel permette di dare un nome alle rotte:

```php
Route::get('/', function () {
    return response('home', 200);
})->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');
```

### Perché dare un nome a una rotta

I nomi aiutano a riferirsi alle rotte senza scrivere direttamente l’URL.

Esempio concettuale:

```php
route('home')
```

può generare l’URL della rotta chiamata `home`.

Il vantaggio è questo:

se un giorno cambiamo URL da:

```text
/about
```

a:

```text
/chi-siamo
```

il codice che usa il nome della rotta può continuare a funzionare, se aggiorniamo correttamente la definizione della rotta.

### Lesson learned sui nomi delle rotte

Prendere presto l’abitudine di dare nomi sensati alle rotte aiuta a mantenere l’applicazione più ordinata.

---

## 11. Approfondimento: `php artisan route:list`

Il comando:

```bash
php artisan route:list
```

mostra l’elenco delle rotte registrate nell’applicazione Laravel.
È uno dei comandi più utili quando si lavora con Laravel.

Serve a rispondere a domande come:

- quali rotte esistono?
- quale URL risponde a una certa pagina?
- quale metodo HTTP usa una rotta?
- una rotta ha un nome?
- una rotta punta a una closure, a un controller o a un servizio interno?
- perché un URL mi dà 404?

---

## 12. Come leggere `php artisan route:list`

Una riga di `route:list` può assomigliare a questa:

```text
GET|HEAD  /  home  Closure
```

I pezzi importanti sono:

| Colonna | Significato |
|---|---|
| Method | verbo HTTP, per esempio GET, POST, PUT |
| URI | percorso della rotta |
| Name | nome della rotta, se esiste |
| Action | cosa viene eseguito |
| Middleware | eventuali middleware applicati |

Laravel spesso mostra `GET|HEAD` insieme.

Questo è normale.

`HEAD` è simile a `GET`, ma chiede solo gli header della risposta, non il corpo.

Laravel registra spesso `HEAD` automaticamente per le rotte `GET`.

---

## 13. Le rotte extra viste nel nostro `route:list`

Nel nostro progetto, oltre alle rotte create da noi, sono apparse anche queste:

```text
GET|HEAD  storage/{path}  storage.local
PUT       storage/{path}  storage.local.upload
GET|HEAD  up
```

Queste rotte non le abbiamo scritte noi in `routes/web.php`.
Sono registrate dal framework Laravel.

### `GET|HEAD storage/{path}`

Esempio:

```text
GET|HEAD  storage/{path}  storage.local
```

Nel nostro output puntava a:

```text
vendor/laravel/framework/src/Illuminate/Filesystem/FilesystemServiceProvider.php
```

Significa che Laravel ha registrato una rotta interna per servire file dallo storage locale.

`{path}` è un parametro dinamico.

Vuol dire:

> qualunque valore in quella posizione viene trattato come path.

Esempio concettuale:

```text
storage/avatar.png
storage/images/photo.jpg
```

Non dobbiamo modificarla ora.
È una rotta di supporto registrata dal framework.

### `PUT storage/{path}`

Esempio:

```text
PUT storage/{path}  storage.local.upload
```

Anche questa riguarda lo storage locale.

`PUT` è un verbo HTTP usato spesso per caricare o sostituire una risorsa.

Nel nostro caso è una rotta interna/di supporto del framework.
Non è una rotta applicativa scritta da noi.

### `GET|HEAD up`

Esempio:

```text
GET|HEAD up
```

Nel nostro output puntava a:

```text
vendor/laravel/framework/src/Illuminate/Foundation/Configuration/ApplicationBuilder.php
```

Questa è una rotta registrata da Laravel per controllare che l’applicazione sia “up”, cioè viva/raggiungibile.
È una specie di endpoint di salute minimale.

In ambienti reali, endpoint simili sono spesso usati da sistemi di deploy, monitoraggio o load balancer.

### Lesson learned sulle rotte interne

Non tutte le rotte mostrate da `route:list` sono scritte da noi.
Alcune sono registrate automaticamente dal framework o dai service provider.

Per capire se una rotta è nostra o interna, guardiamo la colonna `Action`.

Se punta a:

```text
routes/web.php
```

o a un nostro controller, è applicativa.

Se punta a:

```text
vendor/laravel/framework/...
```

è fornita dal framework.

---

## 14. Pratica fatta sul nostro progetto

File modificato:

```text
routes/web.php
```

Versione coerente con la lezione:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('home', 200);
})->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');
```

Test manuale con browser o curl:

```bash
curl -i http://127.0.0.1:8000/

curl -i http://127.0.0.1:8000/about
```

Output atteso:

```text
HTTP/1.1 200 OK
...
home
```

e:

```text
HTTP/1.1 200 OK
...
about
```

Comando per vedere le rotte:

```bash
php artisan route:list
```

---

## 15. Lesson Learned

### 1. Le rotte sono l’ingresso dell’applicazione web

Una richiesta HTTP viene collegata al codice Laravel tramite una rotta.

Il file principale per le rotte web è:

```text
routes/web.php
```

---

### 2. `Route::get()` definisce una rotta GET

Esempio:

```php
Route::get('/about', function () {
    return response('about');
});
```

Qui `/about` è l’URL e la closure è il codice da eseguire.

---

### 3. `Route` è una facade

`Route::get()` sembra una chiamata statica, ma Laravel la usa come scorciatoia verso il router reale gestito dal framework.

---

### 4. Una closure è una funzione anonima

Nelle prime rotte possiamo usare closure perché sono semplici e immediate.

Più avanti, quando la logica crescerà, useremo controller.

---

### 5. `dd()` serve per debug rapido

`dd()` stampa un valore e ferma l’esecuzione.

È utile mentre sviluppiamo, ma non deve restare nel codice finale.

---

### 6. `dump()` stampa senza fermare l’esecuzione

`dump()` è simile a `dd()`, ma lascia continuare il programma.

---

### 7. `response()` costruisce una risposta HTTP

`response('home', 200)` restituisce contenuto `home` con status code `200`.

Non è solo una `echo`: Laravel costruisce una risposta vera.

---

### 8. Dare nomi alle rotte è una buona abitudine

Con:

```php
->name('home')
```

possiamo riferirci alla rotta per nome.

Questo rende il codice più robusto se gli URL cambiano.

---

### 9. `php artisan route:list` mostra tutte le rotte registrate

È il comando principale per controllare quali URL Laravel conosce.

Aiuta a diagnosticare errori 404 e a capire cosa è registrato nell’app.

---

### 10. Alcune rotte sono registrate dal framework

Rotte come:

```text
storage/{path}
up
```

possono apparire anche se non le abbiamo scritte noi.

Se la colonna `Action` punta a `vendor/laravel/framework`, sono rotte interne o di supporto fornite da Laravel.

---

## 16. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Leggere le rotte:

```bash
cat routes/web.php
```

Scrivere le prime rotte:

```php
Route::get('/', function () {
    return response('home', 200);
})->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');
```

Avviare il server:

```bash
php artisan serve
```

Testare homepage:

```bash
curl -i http://127.0.0.1:8000/
```

Testare about:

```bash
curl -i http://127.0.0.1:8000/about
```

Mostrare lista rotte:

```bash
php artisan route:list
```

---

## 18. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- dove si definiscono le rotte web
- come creare una rotta `GET`
- cosa sono le facade a livello introduttivo
- cosa sono le closure
- come usare `dump()` e `dd()` per debug rapido
- come restituire una risposta HTTP con `response()`
- come dare un nome alle rotte
- come leggere `php artisan route:list`
- perché compaiono rotte Laravel interne come `storage/{path}` e `up`

Obiettivo raggiunto:

> abbiamo scritto e capito le prime rotte Laravel.

# Getting Started with Laravel — Lezione 07
## Switching to controllers

Data laboratorio: 2026-06-05  
Corso: Getting Started with Laravel  
Episodio: 07 — Switching to controllers  
Durata video: circa 10 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è:

> spostare la logica dalla route a un controller.

Nelle lezioni precedenti abbiamo usato closure direttamente dentro `routes/web.php`.

Esempio:

```php
Route::get('/', function () {
    return view('pages.home', [
        'greeting' => 'Hello',
        'showGreeting' => true,
    ]);
})->name('home');
```

Questa soluzione va bene per esempi piccoli.

Ma quando l’applicazione cresce, mettere logica direttamente nelle rotte diventa disordinato.

La soluzione Laravel è usare i **controller**.

---

## 2. Perché passare ai controller

Una route dovrebbe rimanere leggibile.

Se dentro `routes/web.php` iniziamo a mettere:

- preparazione dati
- validazione
- logica applicativa
- query al database
- chiamate a servizi
- gestione form

il file delle rotte diventa presto un cassetto pieno di tutto.

I controller servono a separare meglio le responsabilità.

La route dice:

> quale URL esiste e quale controller deve gestirlo.

Il controller dice:

> cosa fare per produrre la risposta.

---

## 3. Prima: route con closure

Prima della lezione 07, la rotta homepage poteva essere così:

```php
Route::get('/', function () {
    return view('pages.home', [
        'greeting' => 'Hello',
        'showGreeting' => true,
    ]);
})->name('home');
```

Il secondo argomento è una closure.

La closure viene eseguita quando Laravel riceve una richiesta `GET` per `/`.

---

## 4. Dopo: route collegata a un controller

Con un controller, la route non contiene più direttamente la closure.

Esempio con controller invocabile:

```php
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
```

Qui Laravel dice:

> quando arriva `GET /`, chiama `HomeController`.

La logica si sposta dentro:

```text
app/Http/Controllers/HomeController.php
```

---

## 5. Creare un controller con Artisan

Laravel permette di creare controller con Artisan.

Comando:

```bash
php artisan make:controller HomeController
```

Questo crea:

```text
app/Http/Controllers/HomeController.php
```

Un controller è una normale classe PHP.

Esempio iniziale:

```php
<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    //
}
```

---

## 6. Il controller base

Il nuovo controller estende:

```php
Controller
```

cioè:

```php
class HomeController extends Controller
```

Nel progetto Laravel base, `Controller` è una classe astratta di partenza dentro:

```text
app/Http/Controllers/Controller.php
```

Il docente chiarisce che non c’è magia nascosta enorme lì dentro.

È una base comune che possiamo usare, se serve, per funzionalità condivise tra controller.

Per ora non dobbiamo modificarla.

---

## 7. Invocable controller

Il primo stile mostrato nella lezione è il controller **invocabile**.

Significa che il controller ha un solo metodo speciale:

```php
__invoke()
```

Esempio:

```php
<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('pages.home');
    }
}
```

`__invoke()` è un magic method PHP.

Non è una magia specifica di Laravel.

In PHP, un oggetto con metodo `__invoke()` può essere “chiamato” come se fosse una funzione.

Laravel sfrutta questa convenzione.

---

## 8. Usare un invocable controller nella route

Se il controller ha `__invoke()`, la route può essere molto compatta:

```php
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
```

Non dobbiamo indicare un metodo specifico, perché Laravel sa che deve usare:

```php
__invoke()
```

Questa forma è molto pulita quando un controller ha un solo compito.

Esempio:

- homepage
- pagina statica semplice
- endpoint molto specifico
- azione unica

---

## 9. `HomeController::class`

Questa sintassi:

```php
HomeController::class
```

restituisce il nome completo della classe.

Concettualmente equivale a qualcosa del tipo:

```text
App\Http\Controllers\HomeController
```

Laravel e PHP la usano per riferirsi alla classe in modo sicuro.

Il vantaggio è che l’editor può aiutare con import, refactor e autocomplete.

La `use` in cima al file tiene la route più leggibile:

```php
use App\Http\Controllers\HomeController;
```

Senza `use`, potremmo scrivere il nome completo direttamente, ma sarebbe più brutto.

---

## 10. `dd()` funziona anche nei controller

Il docente mostra che possiamo usare `dd()` anche dentro un controller.

Esempio temporaneo:

```php
public function __invoke()
{
    dd('home');
}
```

Questo dimostra che la route sta davvero chiamando il controller.

Poi si sostituisce il debug con la risposta reale:

```php
public function __invoke()
{
    return view('pages.home');
}
```

Regola già vista:

> `dd()` è solo debug temporaneo, non codice finale.

---

## 11. Restituire una view da un controller

Il passaggio dalla closure al controller non cambia il modo di restituire una view.

Dentro una closure:

```php
Route::get('/', function () {
    return view('pages.home');
});
```

Dentro un controller:

```php
public function __invoke()
{
    return view('pages.home');
}
```

La logica è la stessa.

Cambia solo dove si trova il codice.

---

## 12. Controller con metodi nominati

Il secondo stile mostrato nella lezione è il controller con metodo nominato.

Esempio:

```php
<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }
}
```

Qui il metodo si chiama:

```php
index()
```

La route deve indicare sia la classe sia il metodo.

---

## 13. Route verso metodo nominato

Per collegare una route a un metodo specifico, si usa un array:

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

Il primo elemento è la classe:

```php
HomeController::class
```

Il secondo elemento è il nome del metodo:

```php
'index'
```

Quindi:

```php
[HomeController::class, 'index']
```

significa:

> chiama il metodo `index()` di `HomeController`.

---

## 14. Errore se Laravel non sa quale metodo chiamare

Il docente mostra un errore utile.

Se il controller non ha più `__invoke()` ma la route resta così:

```php
Route::get('/', HomeController::class)->name('home');
```

Laravel non sa quale metodo chiamare.

Il browser mostra una pagina di errore Laravel.

Il messaggio importante è del tipo:

```text
Invalid route action
```

Questo significa:

> la route punta a un controller, ma Laravel non trova un’azione valida da eseguire.

Se usiamo `index()`, dobbiamo scrivere:

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

---

## 15. La pagina di errore Laravel

La lezione mostra anche la prima pagina di errore Laravel.

La pagina contiene:

- tipo di eccezione
- messaggio
- file coinvolti
- stack trace
- eventuali query database
- dettagli utili in ambiente locale

Questa pagina è molto utile durante lo sviluppo.

Attenzione:

> in produzione non dobbiamo mostrare errori dettagliati agli utenti.

Questo si collega alla configurazione:

```env
APP_DEBUG=false
```

in produzione.

---

## 16. Controller RESTful

Il docente accenna ai controller RESTful.

Esempio concettuale: un `CommentController`.

Un controller per commenti potrebbe avere metodi come:

```php
index()
show()
store()
update()
destroy()
```

Significato tipico:

| Metodo | Scopo |
|---|---|
| `index()` | mostrare lista di risorse |
| `show()` | mostrare una singola risorsa |
| `store()` | salvare una nuova risorsa |
| `update()` | aggiornare una risorsa |
| `destroy()` | eliminare una risorsa |

Per ora non entriamo ancora davvero nelle azioni REST.

Il docente le cita per far capire che un controller può avere più metodi.

---

## 17. Invocable controller vs metodo `index()`

Entrambi gli approcci sono validi.

### Invocable controller

```php
Route::get('/', HomeController::class)->name('home');
```

Controller:

```php
public function __invoke()
{
    return view('pages.home');
}
```

Vantaggio:

- molto pulito
- ideale per controller con un solo compito
- route compatta

### Metodo nominato

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

Controller:

```php
public function index()
{
    return view('pages.home');
}
```

Vantaggio:

- utile per controller con più azioni
- stile comune in controller RESTful
- esplicita quale metodo viene chiamato

Il docente dice che per controller semplici come `HomeController`, personalmente preferisce l’approccio invocabile.

---

## 18. Dot notation vs slash nei controller

Nelle lezioni precedenti abbiamo visto dot notation per le view:

```bash
php artisan make:view pages.home
```

che crea:

```text
resources/views/pages/home.blade.php
```

Per i controller, il docente mostra che la dot notation non è il formato giusto.

Per creare controller in sottocartelle si usa lo slash.

Esempio concettuale:

```bash
php artisan make:controller Admin/DashboardController
```

Questo crea una directory:

```text
app/Http/Controllers/Admin/DashboardController.php
```

Regola pratica:

```text
view annidate       → dot notation
controller annidati → slash
```

---

## 19. Cosa cambia in `routes/web.php`

Prima:

```php
Route::get('/', function () {
    return view('pages.home');
})->name('home');
```

Dopo, con controller invocabile:

```php
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
```

Il file delle rotte diventa più ordinato.

Le rotte restano responsabili di definire URL, verbi HTTP, nomi e destinazioni.

La logica si sposta nei controller.

---

## 20. Pratica fatta sul nostro progetto

File coinvolti:

```text
routes/web.php
app/Http/Controllers/HomeController.php
resources/views/pages/home.blade.php
```

### Creare il controller

```bash
php artisan make:controller HomeController
```

### Versione consigliata per il laboratorio: invocable controller

```php
<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('pages.home');
    }
}
```

### Route aggiornata

```php
<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');
```

Nota:

La route `/about` può restare con closure per ora.

Il focus della lezione è spostare almeno la homepage su controller.

---

## 21. Se vogliamo mantenere i dati della lezione 05

Nella lezione 07 il docente semplifica e rimuove i dati passati alla view.

Nel nostro laboratorio, però, possiamo anche tenere il concetto della lezione 05 dentro il controller:

```php
public function __invoke()
{
    return view('pages.home', [
        'greeting' => 'Hello',
        'showGreeting' => true,
    ]);
}
```

Questa versione mantiene:

- controller della lezione 07
- dati alla view della lezione 05
- titolo da config della lezione 06

È una buona sintesi progressiva.

---

## 22. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare già:

- resource controller completi
- REST in dettaglio
- form submission
- validazione
- dependency injection nei controller
- middleware nei controller
- database
- model binding
- autorizzazione
- testing dei controller

Il focus era:

> sostituire una closure in route con un controller.

---

## 23. Lesson Learned

### 1. Le closure nelle rotte vanno bene solo per esempi piccoli

Quando la logica cresce, `routes/web.php` diventa disordinato.

---

### 2. I controller organizzano meglio la logica

La route definisce l’URL.

Il controller prepara la risposta.

---

### 3. Un controller è una classe PHP

Laravel crea controller dentro:

```text
app/Http/Controllers
```

---

### 4. Artisan può creare controller

Comando:

```bash
php artisan make:controller HomeController
```

---

### 5. Un invocable controller usa `__invoke()`

Esempio:

```php
public function __invoke()
{
    return view('pages.home');
}
```

---

### 6. Una route può puntare direttamente a un invocable controller

Esempio:

```php
Route::get('/', HomeController::class)->name('home');
```

---

### 7. Un controller può avere metodi nominati

Esempio:

```php
public function index()
{
    return view('pages.home');
}
```

---

### 8. Una route verso metodo nominato usa un array

Esempio:

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

---

### 9. `HomeController::class` restituisce il nome completo della classe

È più sicuro e pulito che scrivere stringhe manuali.

---

### 10. La pagina di errore Laravel aiuta durante lo sviluppo

Errori come `Invalid route action` aiutano a capire che la route punta a qualcosa che Laravel non può eseguire.

---

### 11. Le route possono continuare ad avere nomi

Anche usando controller, possiamo scrivere:

```php
->name('home')
```

Il modo di definire URL e nomi non cambia.

---

### 12. Per controller semplici, `__invoke()` è molto pulito

Se il controller ha un solo compito, l’approccio invocabile mantiene la route compatta.

---

## 24. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Creare controller:

```bash
php artisan make:controller HomeController
```

Leggere controller:

```bash
cat app/Http/Controllers/HomeController.php
```

Leggere route:

```bash
cat routes/web.php
```

Mostrare rotte registrate:

```bash
php artisan route:list
```

Testare homepage:

```bash
curl -i http://127.0.0.1:8000/
```

---

## 25. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- perché non conviene tenere troppa logica dentro `routes/web.php`
- cos’è un controller
- come creare un controller con Artisan
- come funziona un invocable controller
- cosa fa il magic method PHP `__invoke()`
- come puntare una route a un controller
- come usare un metodo nominato tipo `index()`
- perché può comparire `Invalid route action`
- perché controller semplici possono essere invocabili
- come le route restano più ordinate usando controller

Obiettivo raggiunto:

> abbiamo spostato la homepage da una closure in route a un controller Laravel.

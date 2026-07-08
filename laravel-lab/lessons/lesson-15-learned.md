# Getting Started with Laravel — Lezione 15
## Controller practice

Data laboratorio: 2026-06-28  
Corso: Getting Started with Laravel  
Episodio: 15 — Controller practice  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è spostare la logica delle route dei progetti dentro un controller.

Nelle lezioni precedenti abbiamo costruito queste funzionalità direttamente in `routes/web.php`:

- pagina per creare un progetto
- salvataggio del progetto
- pagina dettaglio del progetto

Le route con closure funzionano, ma ora iniziano a contenere troppa logica.

Questa lezione serve a fare pulizia e a usare una struttura Laravel più realistica.

---

## 2. Situazione prima della lezione

Prima della lezione, `routes/web.php` contiene probabilmente qualcosa di simile:

```php
Route::get('/projects/create', function () {
    return view('projects.create');
})->name('projects.create');

Route::post('/projects', function (Request $request) {
    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
})->name('projects.store');

Route::get('/projects/{project:slug}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');
```

Questo codice è corretto, ma `routes/web.php` sta diventando un contenitore di logica applicativa.

---

## 3. Perché spostare codice in un controller

Il file delle route dovrebbe aiutare a capire:

```text
quale URL esiste
quale metodo HTTP usa
quale controller/metodo gestisce la richiesta
```

Non dovrebbe diventare il posto principale in cui scriviamo tutta la logica.

Spostando il codice in un controller otteniamo:

- route più leggibili
- responsabilità più chiare
- codice più facile da far crescere
- struttura più vicina a Laravel reale
- preparazione a validazione, redirect, flash messages e logica più complessa

---

## 4. Creare `ProjectController`

Comando Artisan:

```bash
php artisan make:controller ProjectController
```

Questo crea:

```text
app/Http/Controllers/ProjectController.php
```

Il controller conterrà i metodi dedicati ai progetti.

---

## 5. Controller singolo o controller separati

Il docente ricorda che ci sono più strade possibili.

Possiamo creare:

- un controller separato per ogni azione
- un controller RESTful con più metodi
- controller invocabili singoli

Nel laboratorio scegliamo un controller unico:

```text
ProjectController
```

con metodi:

```text
create
store
show
```

Questa è una scelta molto comune in Laravel.

---

## 6. Metodo `create`

La route:

```php
Route::get('/projects/create', function () {
    return view('projects.create');
})->name('projects.create');
```

diventa un metodo del controller:

```php
public function create()
{
    return view('projects.create');
}
```

Questo metodo mostra il form di creazione progetto.

---

## 7. Route `projects.create`

La route ora punta al controller:

```php
Route::get('/projects/create', [ProjectController::class, 'create'])
    ->name('projects.create');
```

Leggendo questa route si capisce subito:

```text
GET /projects/create → ProjectController@create
```

---

## 8. Metodo `store`

La route `POST /projects` salva un nuovo progetto.

Prima era:

```php
Route::post('/projects', function (Request $request) {
    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
})->name('projects.store');
```

Nel controller diventa:

```php
public function store(Request $request)
{
    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
}
```

---

## 9. Import necessari per `store`

Dentro `ProjectController` servono gli import:

```php
use App\Models\Project;
use Illuminate\Http\Request;
```

`Project` serve per usare:

```php
Project::create(...)
```

`Request` serve per ricevere i dati del form:

```php
public function store(Request $request)
```

---

## 10. Route `projects.store`

La route diventa:

```php
Route::post('/projects', [ProjectController::class, 'store'])
    ->name('projects.store');
```

Ora `routes/web.php` non contiene più la logica di creazione.

Contiene solo il collegamento:

```text
POST /projects → ProjectController@store
```

---

## 11. Metodo `show`

La route dettaglio progetto usava route model binding:

```php
Route::get('/projects/{project:slug}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');
```

Nel controller diventa:

```php
public function show(Project $project)
{
    return view('projects.show', [
        'project' => $project,
    ]);
}
```

La cosa importante è che il route model binding funziona anche nei controller.

---

## 12. Route `projects.show`

La route diventa:

```php
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');
```

Laravel continuerà a risolvere automaticamente il model `Project` usando lo slug.

Quindi:

```text
/projects/a-first-project
```

arriva a:

```php
public function show(Project $project)
```

con `$project` già caricato.

---

## 13. Route model binding dentro un controller

Il passaggio importante è questo:

```php
public function show(Project $project)
```

Il binding non è legato alle closure.

Funziona anche nei metodi dei controller, purché la route sia coerente:

```php
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);
```

Laravel vede:

```text
{project:slug}
```

e:

```php
Project $project
```

quindi cerca il model usando la colonna `slug`.

---

## 14. `routes/web.php` dopo la pulizia

Dopo la lezione, `routes/web.php` dovrebbe essere più leggibile.

Esempio:

```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');

Route::get('/eloquent', function () {
    dd(Project::all());
})->name('eloquent');

Route::get('/projects/create', [ProjectController::class, 'create'])
    ->name('projects.create');

Route::post('/projects', [ProjectController::class, 'store'])
    ->name('projects.store');

Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');
```

Nota: `Project` serve ancora solo per la route didattica `/eloquent`.

Se in futuro rimuoviamo `/eloquent`, potremo rimuovere anche l’import `App\Models\Project` da `routes/web.php`.

---

## 15. `ProjectController` completo

Versione coerente con la lezione:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        Project::create([
            'name' => $request->name,
            'slug' => str($request->name)->slug(),
        ]);

        return back();
    }

    public function show(Project $project)
    {
        return view('projects.show', [
            'project' => $project,
        ]);
    }
}
```

---

## 16. Convenzioni RESTful

Il docente usa nomi convenzionali Laravel:

```text
create
store
show
```

Significato:

| Metodo | Scopo | Route tipica |
|---|---|---|
| `create` | mostra il form di creazione | `GET /projects/create` |
| `store` | salva una nuova risorsa | `POST /projects` |
| `show` | mostra una singola risorsa | `GET /projects/{project}` |

Questi nomi sono importanti perché Laravel ha molte convenzioni intorno ai resource controller.

---

## 17. Resource controller

Il docente cita brevemente che Laravel può registrare automaticamente più route RESTful con una sola dichiarazione.

Esempio futuro:

```php
Route::resource('projects', ProjectController::class);
```

Questo genererebbe automaticamente route come:

- index
- create
- store
- show
- edit
- update
- destroy

La lezione non lo approfondisce per non aggiungere troppi concetti insieme.

Per ora restiamo su route esplicite.

---

## 18. Perché non usare ancora `Route::resource`

`Route::resource` è potente, ma all’inizio può nascondere troppa magia.

In questa fase è meglio vedere esplicitamente:

```php
Route::get('/projects/create', [ProjectController::class, 'create']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);
```

Così impariamo bene il collegamento tra:

```text
URL
metodo HTTP
controller
metodo del controller
nome route
```

---

## 19. Beneficio finale

Dopo questa lezione:

- `routes/web.php` è più ordinato
- la logica dei progetti sta in `ProjectController`
- il binding continua a funzionare
- il form continua a salvare
- la pagina dettaglio continua a mostrare il progetto
- siamo pronti a introdurre validazione

---

## 20. Cosa NON era obiettivo di questa lezione

Questa lezione non introduce ancora:

- validazione dei form
- form request dedicate
- redirect con messaggi flash
- gestione errori di validazione
- `Route::resource` completo
- layout condivisi
- service layer
- repository pattern
- policy/autorizzazioni

Il focus è:

> spostare le closure dei project in un controller.

---

## 21. Lesson Learned

### 1. Le closure nelle route sono utili all’inizio

Sono perfette per imparare e sperimentare.

---

### 2. Quando la logica cresce, meglio usare controller

`routes/web.php` deve restare leggibile.

---

### 3. `ProjectController` raccoglie le azioni sui progetti

Nel laboratorio contiene:

```text
create
store
show
```

---

### 4. `create` mostra il form

```php
public function create()
{
    return view('projects.create');
}
```

---

### 5. `store` salva il progetto

```php
public function store(Request $request)
{
    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
}
```

---

### 6. `show` mostra un singolo progetto

```php
public function show(Project $project)
{
    return view('projects.show', [
        'project' => $project,
    ]);
}
```

---

### 7. Il route model binding funziona anche nei controller

Non è limitato alle closure.

---

### 8. Le route diventano più dichiarative

Esempio:

```php
Route::post('/projects', [ProjectController::class, 'store'])
    ->name('projects.store');
```

---

### 9. `Route::resource` esiste, ma lo studieremo più avanti

Per ora meglio usare route esplicite.

---

## 22. Comandi utili

Entrare nell’app Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Creare il controller:

```bash
php artisan make:controller ProjectController
```

Mostrare route:

```bash
php artisan route:list
```

Avviare server:

```bash
php artisan serve
```

Aprire form:

```text
http://127.0.0.1:8000/projects/create
```

Aprire dettaglio progetto:

```text
http://127.0.0.1:8000/projects/a-first-project
```

---

## 23. Stato finale della lezione

Alla fine della lezione sappiamo:

- creare `ProjectController`
- spostare una closure `GET` nel metodo `create`
- spostare una closure `POST` nel metodo `store`
- spostare una closure con route model binding nel metodo `show`
- importare `Request`
- importare `Project`
- aggiornare le route per puntare a `[ProjectController::class, 'metodo']`
- mantenere i nomi route
- mantenere il binding `{project:slug}`
- rendere `routes/web.php` più pulito

Obiettivo raggiunto:

> abbiamo trasformato le route dei project da closure didattiche a metodi di un controller Laravel.

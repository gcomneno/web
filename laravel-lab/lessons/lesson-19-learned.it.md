# Getting Started with Laravel — Lezione 19
## Listing in Blade

[English](lesson-19-learned.md) | [Italiano](lesson-19-learned.it.md)

Data laboratorio: 2026-08-06
Corso: Getting Started with Laravel
Episodio: 19 — Listing in Blade
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è mostrare una lista di progetti in una view Blade.

Nelle lezioni precedenti abbiamo già imparato a:

- creare un progetto tramite form
- validare il form
- mostrare errori
- mostrare un messaggio flash
- vedere il dettaglio di un singolo progetto tramite slug

Ora aggiungiamo la pagina indice:

```text
GET /projects
```

Questa pagina deve mostrare tutti i progetti salvati nel database.

---

## 2. Nuovo metodo `index()`

In un controller RESTful Laravel, il metodo convenzionale per mostrare una lista di risorse è:

```php
index()
```

Nel nostro caso lo aggiungiamo a:

```text
app/Http/Controllers/ProjectController.php
```

Metodo iniziale:

```php
public function index()
{
    return view('projects.index');
}
```

Per ora mostra solo la view.

---

## 3. Nuova view `projects.index`

Comando Artisan:

```bash
php artisan make:view projects.index
```

Questo crea:

```text
resources/views/projects/index.blade.php
```

La dot notation:

```php
view('projects.index')
```

corrisponde a:

```text
resources/views/projects/index.blade.php
```

---

## 4. Nuova route `GET /projects`

In `routes/web.php` aggiungiamo:

```php
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');
```

Questa route collega:

```text
GET /projects
```

al metodo:

```text
ProjectController@index
```

---

## 5. Nominare le route

La lezione rafforza l’uso delle named routes.

Invece di scrivere URL direttamente nelle view:

```blade
<a href="/projects/create">New project</a>
```

è meglio usare:

```blade
<a href="{{ route('projects.create') }}">New project</a>
```

Perché?

Se domani l’URL cambia, ad esempio da:

```text
/projects/create
```

a:

```text
/project/new
```

non dobbiamo modificare tutte le view.

Ci basta aggiornare la route.

Il nome resta stabile.

---

## 6. Convenzione nomi route

La lezione usa nomi coerenti con la struttura RESTful:

```text
projects.index
projects.create
projects.store
projects.show
```

Questa forma è chiara perché segue lo schema:

```text
risorsa.azione
```

Esempi:

| Nome route | Significato |
|---|---|
| `projects.index` | lista dei progetti |
| `projects.create` | form di creazione progetto |
| `projects.store` | endpoint POST di salvataggio |
| `projects.show` | dettaglio di un singolo progetto |

---

## 7. Aggiornare il form con `route()`

Nella view `projects.create`, il form può passare da:

```blade
<form action="/projects" method="POST">
```

a:

```blade
<form action="{{ route('projects.store') }}" method="POST">
```

Questo evita di hardcodare l’URL.

Regola pratica:

> nelle view, preferisci `route('nome.route')` agli URL scritti a mano.

---

## 8. Passare dati alla view

Per mostrare tutti i progetti, il controller deve recuperarli e passarli alla view.

Abbiamo già visto che `view()` accetta un secondo argomento:

```php
return view('projects.index', [
    'projects' => Project::get(),
]);
```

Questo rende disponibile nella view la variabile:

```php
$projects
```

---

## 9. Recuperare i progetti con Eloquent

La lezione usa:

```php
Project::get()
```

`get()` esegue la query e restituisce una collection di model.

Nel nostro caso:

```php
$projects = Project::get();
```

restituisce una collection di oggetti `Project`.

Codice:

```php
public function index()
{
    return view('projects.index', [
        'projects' => Project::get(),
    ]);
}
```

---

## 10. Collection

Una collection Laravel è un contenitore di elementi.

Nel caso di Eloquent:

```php
Project::get()
```

restituisce una collection di model.

Ma il concetto di collection non è limitato al database.

Una collection può contenere:

- model Eloquent
- stringhe
- oggetti
- array
- dati arrivati da API
- elementi costruiti manualmente

Le collection hanno molti metodi utili per manipolare dati.

In questa lezione ci interessa soprattutto che siano iterabili.

---

## 11. Iterare una collection in Blade

Per scorrere tutti i progetti nella view, usiamo la direttiva Blade:

```blade
@foreach ($projects as $project)
    {{ $project->name }}
@endforeach
```

È simile a un normale `foreach` PHP.

Dentro il ciclo:

```php
$project
```

rappresenta un singolo model `Project`.

---

## 12. Prima view indice

Versione minima:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects</title>
</head>
<body>
    <h1>Projects</h1>

    <p>
        <a href="{{ route('projects.create') }}">New project</a>
    </p>

    <ul>
        @foreach ($projects as $project)
            <li>{{ $project->name }}</li>
        @endforeach
    </ul>
</body>
</html>
```

Questa view mostra titolo, link al form di creazione e lista dei nomi dei progetti.

---

## 13. Linkare ogni progetto al dettaglio

Ora che abbiamo una pagina dettaglio:

```text
GET /projects/{project:slug}
```

ogni progetto nella lista può diventare un link.

Route:

```php
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');
```

Nella view:

```blade
<a href="{{ route('projects.show', $project) }}">
    {{ $project->name }}
</a>
```

Laravel riceve il model `$project` e costruisce l’URL corretto.

---

## 14. Perché posso passare il model a `route()`

La route `projects.show` richiede un parametro:

```text
{project:slug}
```

Se chiamiamo:

```blade
route('projects.show', $project)
```

Laravel sa usare il model per generare il parametro richiesto.

Con il binding su slug, l’URL diventa qualcosa come:

```text
/projects/a-first-project
```

In alternativa, si potrebbe essere espliciti:

```blade
route('projects.show', $project->slug)
```

Ma passare il model è più flessibile.

Se in futuro cambia la chiave usata nella route, si riduce il codice da modificare.

---

## 15. Errore: parametro mancante

Se proviamo a generare la route senza passare il progetto:

```blade
route('projects.show')
```

Laravel non sa quale progetto inserire nell’URL.

Errore tipico:

```text
Missing required parameter
```

Questo ha senso perché la route ha bisogno di sapere quale valore usare per:

```text
{project:slug}
```

---

## 16. Passare parametri multipli a `route()`

La lezione accenna anche al caso di più parametri.

Se una route richiede più valori, si può passare un array:

```blade
route('some.route', [$first, $second])
```

Nel nostro caso serve un solo parametro, quindi basta:

```blade
route('projects.show', $project)
```

---

## 17. View finale consigliata

`resources/views/projects/index.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects</title>
</head>
<body>
    <h1>Projects</h1>

    <p>
        <a href="{{ route('projects.create') }}">New project</a>
    </p>

    <ul>
        @foreach ($projects as $project)
            <li>
                <a href="{{ route('projects.show', $project) }}">
                    {{ $project->name }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
```

---

## 18. `ProjectController` aggiornato

`app/Http/Controllers/ProjectController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::get(),
        ]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        Project::create([
            'name' => $request->name,
            'slug' => str($request->name)->slug(),
        ]);

        return back()->with('status', 'Your project was created.');
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

## 19. `routes/web.php` aggiornato

Ordine consigliato:

```php
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/create', [ProjectController::class, 'create'])
    ->name('projects.create');

Route::post('/projects', [ProjectController::class, 'store'])
    ->name('projects.store');

Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');
```

L’ordine è importante.

`/projects/create` deve restare prima di `/projects/{project:slug}` oppure Laravel potrebbe interpretare `create` come slug.

---

## 20. Cosa verrà dopo

La lezione chiude anticipando l’ordinamento.

Al momento:

```php
Project::get()
```

recupera i progetti senza ordine esplicito.

La prossima lezione introdurrà probabilmente:

```php
Project::orderBy(...)->get()
```

oppure metodi equivalenti di Eloquent.

---

## 21. Lesson Learned

### 1. `index()` mostra la lista di una risorsa

Nel controller RESTful, `index()` è il metodo convenzionale per la pagina elenco.

### 2. `projects.index` è la view della lista

Corrisponde a:

```text
resources/views/projects/index.blade.php
```

### 3. `Project::get()` restituisce una collection

La collection contiene più model `Project`.

### 4. Una collection può essere iterata in Blade

Esempio:

```blade
@foreach ($projects as $project)
    {{ $project->name }}
@endforeach
```

### 5. Le named routes evitano URL hardcoded

Meglio:

```blade
route('projects.create')
```

rispetto a:

```blade
/projects/create
```

### 6. `route()` può generare URL anche con parametri

Esempio:

```blade
route('projects.show', $project)
```

### 7. Con `{project:slug}`, Laravel può usare il model per generare l’URL

Passare `$project` a `route()` produce un URL con lo slug corretto.

### 8. Se manca un parametro, Laravel segnala errore

Esempio:

```blade
route('projects.show')
```

può generare:

```text
Missing required parameter
```

### 9. L’ordine delle route conta

`/projects/create` deve stare prima di `/projects/{project:slug}`.

---

## 22. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Creare la view:

```bash
php artisan make:view projects.index
```

Controllare route:

```bash
php artisan route:list
```

Avviare server:

```bash
php artisan serve
```

Aprire lista progetti:

```text
http://127.0.0.1:8000/projects
```

Aprire form creazione:

```text
http://127.0.0.1:8000/projects/create
```

Controllare controller:

```bash
sed -n '1,240p' app/Http/Controllers/ProjectController.php
```

Controllare view indice:

```bash
sed -n '1,220p' resources/views/projects/index.blade.php
```

---

## 23. Stato finale della lezione

Alla fine della lezione sappiamo:

- creare una pagina indice per una risorsa
- aggiungere `ProjectController@index`
- creare `projects.index`
- recuperare più model con `Project::get()`
- passare una collection a Blade
- iterare con `@foreach`
- creare link con named routes
- usare `route('projects.show', $project)`
- evitare URL hardcoded nelle view

Obiettivo raggiunto:

> l’app mostra una lista di progetti e ogni progetto linka alla propria pagina dettaglio.

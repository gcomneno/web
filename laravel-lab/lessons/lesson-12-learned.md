# Getting Started with Laravel — Lezione 12
## Playing with request data

Data laboratorio: 2026-06-18  
Corso: Getting Started with Laravel  
Episodio: 12 — Playing with request data  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è collegare insieme i pezzi studiati finora:

- route
- parametri nell’URL
- request data
- Eloquent
- model
- view Blade
- dati passati alla view

In pratica costruiamo un mini-flusso tipico di molte applicazioni web:

```text
URL /projects/1
        ↓
Laravel legge il parametro 1
        ↓
Eloquent cerca il Project con ID 1
        ↓
se esiste, passa il model alla view
        ↓
Blade mostra i dati all’utente
```

Questo è un passaggio importante perché smettiamo di usare solo esempi isolati e iniziamo a vedere una piccola applicazione reale.

---

## 2. Da `/eloquent` a una pagina progetto

Finora avevamo usato una route didattica tipo:

```php
Route::get('/eloquent', function () {
    dd(Project::all());
})->name('eloquent');
```

Era utile per esplorare Eloquent, ma non rappresenta una vera pagina applicativa.

Ora vogliamo qualcosa di più realistico:

```text
/projects/1
/projects/2
/projects/3
```

Ogni URL deve mostrare un progetto diverso.

---

## 3. Route parameter

Per rappresentare un valore dinamico dentro una route Laravel usiamo le parentesi graffe.

Esempio:

```php
Route::get('/projects/{id}', function ($id) {
    dd($id);
});
```

La parte:

```text
{id}
```

è un parametro dinamico.

Quindi:

```text
/projects/1 → $id = 1
/projects/2 → $id = 2
/projects/42 → $id = 42
```

---

## 4. Parametro URL

Un parametro URL è una porzione dinamica dell’indirizzo.

Nel nostro caso:

```text
/projects/1
```

il valore dinamico è:

```text
1
```

Laravel lo cattura tramite:

```php
{id}
```

e lo passa alla closure:

```php
function ($id) {
    //
}
```

---

## 5. Cast del parametro a intero

Il docente mostra che possiamo tipizzare il parametro:

```php
Route::get('/projects/{id}', function (int $id) {
    dd($id);
});
```

Così Laravel/PHP tratta `$id` come intero.

Esempio:

```text
/projects/1
```

produce:

```php
1
```

come integer.

Nota pratica:

> tipizzare aiuta a rendere più chiara l’intenzione del codice, ma non sostituisce validazione e gestione errori completa.

---

## 6. Cercare il progetto con Eloquent

Una volta ottenuto l’ID dall’URL, possiamo cercare il progetto:

```php
$project = Project::find($id);
```

Ma in questa lezione è meglio usare:

```php
$project = Project::findOrFail($id);
```

Perché?

Perché se il progetto non esiste, Laravel genera automaticamente una risposta 404.

---

## 7. `findOrFail()`

`findOrFail()` cerca un record per primary key.

Esempio:

```php
$project = Project::findOrFail($id);
```

Se esiste:

```text
restituisce un model Project
```

Se non esiste:

```text
genera una pagina 404
```

Questo è perfetto per una pagina dettaglio.

Esempi:

```text
/projects/1 → mostra il progetto se esiste
/projects/2 → 404 se il progetto non esiste
```

---

## 8. Prima versione della route

Una prima versione della route può essere:

```php
use App\Models\Project;

Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    dd($project);
});
```

Questa versione serve solo per verificare che il recupero funzioni.

Non è la versione finale, perché non vogliamo mostrare un dump tecnico all’utente.

---

## 9. Dalla route alla view

Una vera applicazione non mostra `dd()`.

Mostra una pagina HTML.

Quindi vogliamo:

```php
return view('projects.show', [
    'project' => $project,
]);
```

La route diventa:

```php
Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Qui succedono due cose importanti:

1. Laravel cerca la view `projects.show`
2. il model `$project` viene passato alla view come variabile `$project`

---

## 10. Creare la view `projects.show`

Il docente usa Artisan:

```bash
php artisan make:view projects.show
```

Questo crea:

```text
resources/views/projects/show.blade.php
```

La dot notation:

```text
projects.show
```

corrisponde a:

```text
resources/views/projects/show.blade.php
```

---

## 11. Passare il model intero alla view

Nella route possiamo passare tutto il model:

```php
return view('projects.show', [
    'project' => $project,
]);
```

Dentro la view avremo disponibile:

```php
$project
```

Il docente nota che potremmo anche passare solo singoli campi:

```php
return view('projects.show', [
    'name' => $project->name,
]);
```

Ma normalmente è comodo passare il model intero e decidere nella view quali proprietà mostrare.

---

## 12. Stampare il nome del progetto nella view

Nel file:

```text
resources/views/projects/show.blade.php
```

possiamo scrivere:

```blade
<h1>{{ $project->name }}</h1>
```

Ora visitando:

```text
http://127.0.0.1:8000/projects/1
```

Laravel:

1. legge `1`
2. cerca il progetto con ID 1
3. passa il model alla view
4. Blade stampa il nome del progetto

---

## 13. Accesso alle proprietà del model

Un model Eloquent è un oggetto PHP.

Per accedere alle sue proprietà usiamo:

```php
$project->name
$project->created_at
$project->updated_at
```

In Blade:

```blade
{{ $project->name }}
```

---

## 14. `created_at` e `updated_at`

Nella migration `projects` abbiamo usato:

```php
$table->timestamps();
```

Questo crea due colonne:

```text
created_at
updated_at
```

Laravel le gestisce automaticamente.

Quando un record viene creato, valorizza `created_at`.

Quando un record viene aggiornato, modifica `updated_at`.

---

## 15. Cast implicito delle date

Nel model `User` abbiamo già visto i cast espliciti.

Il docente chiarisce però una cosa importante:

`created_at` e `updated_at` sono già castati implicitamente da Laravel.

Quindi anche se nel model `Project` non scriviamo:

```php
protected function casts(): array
{
    return [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

Laravel tratta comunque questi campi come oggetti data.

---

## 16. Carbon

Laravel usa spesso **Carbon**, una libreria PHP per lavorare con date e orari.

Quando leggiamo:

```php
$project->created_at
```

non otteniamo una semplice stringa.

Otteniamo un oggetto Carbon.

Questo ci permette di chiamare metodi come:

```php
$project->created_at->toDateTimeString()
$project->created_at->toTimeString()
$project->created_at->diffForHumans()
```

---

## 17. `toDateTimeString()`

Metodo Carbon che mostra data e ora in formato leggibile.

Esempio:

```blade
{{ $project->created_at->toDateTimeString() }}
```

Output tipico:

```text
2026-06-18 16:47:08
```

---

## 18. `toTimeString()`

Metodo Carbon che mostra solo l’orario.

Esempio:

```blade
{{ $project->created_at->toTimeString() }}
```

Output tipico:

```text
16:47:08
```

---

## 19. `diffForHumans()`

Metodo Carbon che mostra una data in formato umano relativo.

Esempio:

```blade
{{ $project->created_at->diffForHumans() }}
```

Output tipico:

```text
22 minutes ago
```

Oppure:

```text
1 hour ago
```

È molto utile per interfacce utente più naturali.

---

## 20. View finale della lezione

Una view minima può essere:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->name }}</title>
</head>
<body>
    <h1>{{ $project->name }}</h1>

    <p>Created: {{ $project->created_at->diffForHumans() }}</p>
</body>
</html>
```

Oppure, se vogliamo una data più esplicita:

```blade
<p>Created: {{ $project->created_at->toDateTimeString() }}</p>
```

---

## 21. Flusso completo

Il flusso completo ora è:

```text
Browser apre /projects/1
        ↓
Route cattura {id}
        ↓
Closure riceve $id
        ↓
Project::findOrFail($id)
        ↓
se non esiste, 404
        ↓
se esiste, model Project
        ↓
return view('projects.show', ['project' => $project])
        ↓
Blade stampa $project->name e $project->created_at
```

Questo è un primo vero esempio di applicazione web dinamica.

---

## 22. Perché questa lezione è importante

Questa lezione unisce quasi tutto quello che abbiamo studiato:

| Concetto | Dove appare |
|---|---|
| Route | `/projects/{id}` |
| Parametro route | `{id}` |
| Closure | `function (int $id)` |
| Model | `Project` |
| Eloquent | `findOrFail()` |
| Errore HTTP | 404 automatico |
| View | `projects.show` |
| Blade | `{{ $project->name }}` |
| Database | tabella `projects` |
| Carbon | `diffForHumans()` |

Prima avevamo pezzi separati.

Ora iniziano a incastrarsi.

---

## 23. Collegamento alla route model binding

Il docente anticipa che il prossimo passo sarà il **route model binding**.

Oggi abbiamo scritto:

```php
Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Con route model binding, Laravel potrà fare automaticamente parte di questo lavoro.

L’idea futura sarà qualcosa del genere:

```php
Route::get('/projects/{project}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Ma questo verrà approfondito dopo.

---

## 24. Cosa NON era obiettivo di questa lezione

Questa lezione non entra ancora davvero in:

- form HTML
- POST request
- validazione input
- controller dedicato per i project
- route model binding completo
- pagine index/lista progetti
- layout Blade riutilizzabili
- componenti Blade
- relazioni tra model
- autorizzazioni

Il focus è:

> leggere un valore dall’URL, usarlo con Eloquent e mostrare il risultato in una view.

---

## 25. Codice pratico consigliato per il laboratorio

### `routes/web.php`

Versione coerente con il punto della lezione:

```php
<?php

use App\Http\Controllers\HomeController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');

Route::get('/eloquent', function () {
    dd(Project::all());
})->name('eloquent');

Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');
```

---

### `resources/views/projects/show.blade.php`

Versione minimale:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->name }}</title>
</head>
<body>
    <h1>{{ $project->name }}</h1>

    <p>Created: {{ $project->created_at->diffForHumans() }}</p>
</body>
</html>
```

---

## 26. Lesson Learned

### 1. Le route possono avere parametri dinamici

Esempio:

```php
Route::get('/projects/{id}', ...)
```

---

### 2. Il valore del parametro viene passato alla closure

Esempio:

```php
function (int $id) {
    //
}
```

---

### 3. Possiamo usare quel parametro con Eloquent

Esempio:

```php
Project::findOrFail($id)
```

---

### 4. `findOrFail()` è adatto alle pagine dettaglio

Se il record esiste, restituisce il model.

Se non esiste, Laravel mostra 404.

---

### 5. `dd()` serve per debug, non per mostrare pagine finali

Dopo aver verificato i dati, passiamo a una view.

---

### 6. Una view può ricevere un model intero

Esempio:

```php
return view('projects.show', [
    'project' => $project,
]);
```

---

### 7. In Blade possiamo leggere proprietà del model

Esempio:

```blade
{{ $project->name }}
```

---

### 8. `created_at` e `updated_at` sono gestiti automaticamente

Sono creati da:

```php
$table->timestamps();
```

---

### 9. Le date standard Eloquent sono castate automaticamente

`created_at` e `updated_at` sono oggetti Carbon.

---

### 10. Carbon permette formattazione comoda delle date

Esempi:

```php
toDateTimeString()
toTimeString()
diffForHumans()
```

---

### 11. Questa è la prima mini-pagina dinamica completa

Abbiamo collegato:

```text
URL → parametro → database → model → view → HTML
```

---

## 27. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Creare la view:

```bash
php artisan make:view projects.show
```

Mostrare le route:

```bash
php artisan route:list
```

Avviare il server:

```bash
php artisan serve
```

Aprire nel browser:

```text
http://127.0.0.1:8000/projects/1
```

Provare un ID inesistente:

```text
http://127.0.0.1:8000/projects/999
```

Dovrebbe produrre una pagina 404.

---

## 28. Stato finale della lezione

Alla fine della lezione sappiamo:

- definire una route con parametro dinamico
- leggere un ID dall’URL
- usare quell’ID con `Project::findOrFail()`
- ottenere automaticamente 404 se il record non esiste
- creare una view `projects.show`
- passare un model Eloquent alla view
- leggere proprietà del model dentro Blade
- mostrare `name`
- mostrare `created_at`
- usare metodi Carbon come `diffForHumans()`

Obiettivo raggiunto:

> abbiamo costruito la prima pagina dinamica completa basata su URL, database, Eloquent e Blade.

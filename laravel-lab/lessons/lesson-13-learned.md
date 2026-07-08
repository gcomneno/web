# Getting Started with Laravel — Lezione 13
## Route model binding

Data laboratorio: 2026-06-28  
Corso: Getting Started with Laravel  
Episodio: 13 — Route model binding  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è imparare il **route model binding**.

Nella lezione 12 avevamo scritto una route così:

```php
Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');
```

Funziona correttamente:

1. legge l’ID dall’URL
2. cerca il progetto con `Project::findOrFail($id)`
3. se non esiste, Laravel produce 404
4. se esiste, passa il model alla view

Ma Laravel può fare questo lavoro automaticamente.

---

## 2. Cos’è il route model binding

Il route model binding è una funzione di Laravel che collega automaticamente un parametro della route a un model Eloquent.

Invece di ricevere un ID e cercare a mano il model, possiamo chiedere direttamente un `Project`.

Esempio:

```php
Route::get('/projects/{project}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');
```

Laravel vede:

```text
{project}
```

e vede anche:

```php
Project $project
```

Quindi capisce:

> devo prendere il valore nell’URL e cercare un record del model `Project`.

---

## 3. Prima: ricerca manuale

Prima scrivevamo:

```php
Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Qui facciamo noi il lavoro:

```php
Project::findOrFail($id)
```

Non è sbagliato.

È esplicito e comprensibile.

Ma in Laravel è spesso ridondante, perché il framework ha già una scorciatoia standard per questo caso.

---

## 4. Dopo: binding automatico

Con route model binding:

```php
Route::get('/projects/{project}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Laravel fa dietro le quinte qualcosa di simile a:

```php
Project::findOrFail($valueFromUrl)
```

Se visitiamo:

```text
/projects/1
```

Laravel cerca:

```text
Project con id = 1
```

Se lo trova, passa il model alla closure.

Se non lo trova, restituisce automaticamente 404.

---

## 5. Perché il nome del parametro è importante

Nel binding implicito, il nome del parametro route deve corrispondere al nome della variabile tipizzata.

Esempio corretto:

```php
Route::get('/projects/{project}', function (Project $project) {
    //
});
```

Qui combaciano:

```text
{project}
$project
```

Esempio da evitare in questa fase:

```php
Route::get('/projects/{id}', function (Project $project) {
    //
});
```

Laravel non ha lo stesso aggancio nominale semplice.

Regola pratica:

> con route model binding, usa `{project}` e `Project $project`.

---

## 6. Binding su ID

Per impostazione predefinita, Laravel usa la primary key del model.

Nel nostro caso:

```text
id
```

Quindi:

```text
/projects/1
```

cerca il progetto con:

```text
id = 1
```

Non dobbiamo specificare `id`.

È il comportamento di default.

---

## 7. Vantaggio principale

Il route model binding elimina codice ripetitivo.

Prima:

```php
Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Dopo:

```php
Route::get('/projects/{project}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Il codice è più corto, più Laravel-style e mantiene comunque il 404 automatico.

---

## 8. Il route model binding non serve solo per mostrare dati

Il binding non è utile solo per pagine dettaglio.

Possiamo usarlo anche per:

- aggiornare un model
- eliminare un model
- passare dati a una form
- costruire pagine di editing
- gestire risorse in modo più pulito

Il punto è:

> Laravel ti dà direttamente il model già risolto.

---

## 9. Limite del binding di default

Il binding di default cerca il model tramite ID.

Questo va bene per:

```text
/projects/1
```

ma spesso nelle applicazioni vogliamo URL più leggibili:

```text
/projects/a-first-project
```

In quel caso vogliamo cercare per:

```text
slug
```

non per:

```text
id
```

---

## 10. Slug

Uno slug è una stringa leggibile e adatta agli URL.

Esempio:

```text
a-first-project
```

Rispetto a:

```text
1
```

è più descrittivo.

URL con ID:

```text
/projects/1
```

URL con slug:

```text
/projects/a-first-project
```

Lo slug deve essere unico se lo usiamo per identificare una risorsa.

---

## 11. Aggiungere la colonna `slug`

Per usare uno slug dobbiamo aggiungere una colonna alla tabella `projects`.

Comando:

```bash
php artisan make:migration add_slug_to_projects_table
```

Migration consigliata:

```php
Schema::table('projects', function (Blueprint $table) {
    $table->string('slug')->unique()->after('name');
});
```

Il docente usa `after('name')` per posizionare la colonna dopo `name`.

Nota importante:

> `after()` è comodo, ma il comportamento può dipendere dal database. In SQLite può non comportarsi come in MySQL.

---

## 12. Il problema del `down()`

Nel video il docente mostra un piccolo incidente: fa rollback, ma la migration non aveva un `down()` corretto.

Il punto didattico è importante.

Se in `up()` aggiungiamo una colonna:

```php
$table->string('slug')->unique()->after('name');
```

in `down()` dobbiamo rimuoverla:

```php
$table->dropColumn('slug');
```

Migration completa più corretta:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
```

Nel nostro laboratorio conviene fare così da subito.

---

## 13. Eseguire la migration

Dopo aver scritto la migration:

```bash
php artisan migrate
```

La tabella `projects` avrà una nuova colonna:

```text
slug
```

Per il laboratorio, il docente riempie manualmente il valore dello slug nel database.

Esempio:

```text
a-first-project
```

---

## 14. Ricerca manuale per slug

Senza route model binding, potremmo fare così:

```php
Route::get('/projects/{slug}', function (string $slug) {
    $project = Project::where('slug', $slug)->firstOrFail();

    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Funziona.

Ma di nuovo abbiamo codice ripetitivo:

```php
Project::where('slug', $slug)->firstOrFail()
```

---

## 15. Route model binding su colonna custom

Laravel permette di specificare quale colonna usare per il binding.

Sintassi:

```php
Route::get('/projects/{project:slug}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
});
```

La parte importante è:

```text
{project:slug}
```

Significa:

> risolvi il model `Project` cercando nella colonna `slug`.

Quindi:

```text
/projects/a-first-project
```

diventa:

```php
Project::where('slug', 'a-first-project')->firstOrFail()
```

ma lo fa Laravel automaticamente.

---

## 16. Route finale consigliata

Dopo la lezione 13, la route più coerente è:

```php
Route::get('/projects/{project:slug}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');
```

Questa route:

- riceve uno slug nell’URL
- cerca automaticamente il progetto
- restituisce 404 se non trova nulla
- passa il model alla view

---

## 17. Aggiornare `$fillable`

Dato che aggiungiamo `slug`, il model `Project` dovrebbe autorizzare anche quel campo per mass assignment.

Esempio:

```php
class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];
}
```

Questo sarà utile quando creeremo o aggiorneremo project tramite form.

---

## 18. Aggiornare la view

La view `projects.show` può restare quasi uguale.

Esempio:

```blade
<h1>{{ $project->name }}</h1>

<p>Slug: {{ $project->slug }}</p>

<p>Created: {{ $project->created_at->diffForHumans() }}</p>
```

Mostrare lo slug è utile per verificare che il binding funzioni davvero.

---

## 19. Flusso finale con slug

Il flusso diventa:

```text
Browser apre /projects/a-first-project
        ↓
Route cattura {project:slug}
        ↓
Laravel capisce che deve risolvere Project usando slug
        ↓
Eloquent cerca projects.slug = "a-first-project"
        ↓
se non trova nulla, 404
        ↓
se trova il record, passa Project $project alla closure
        ↓
la closure passa il model alla view
        ↓
Blade mostra nome, slug e data
```

---

## 20. Perché questa lezione è importante

Questa lezione fa vedere una delle comodità più tipiche di Laravel.

Non stiamo solo scrivendo meno codice.

Stiamo spostando un pattern ripetitivo nel framework.

Prima:

```php
$id = ...
$project = Project::findOrFail($id);
```

Poi:

```php
Project $project
```

Prima:

```php
$project = Project::where('slug', $slug)->firstOrFail();
```

Poi:

```php
{project:slug}
```

Il codice applicativo si concentra sul risultato, non sulla meccanica ripetitiva.

---

## 21. Cosa NON era obiettivo di questa lezione

Questa lezione non entra ancora in:

- form di creazione progetto
- validazione dello slug
- generazione automatica dello slug
- controller dedicato
- route resource
- policy/autorizzazioni
- relazioni tra model
- SEO avanzata
- gestione slug duplicati oltre al vincolo `unique`

Questi temi arriveranno o potranno essere studiati più avanti.

---

## 22. Lesson Learned

### 1. Route model binding risolve automaticamente un model

Laravel può convertire un parametro URL in un model Eloquent.

---

### 2. La versione manuale resta valida

Questa forma funziona:

```php
$project = Project::findOrFail($id);
```

Ma spesso è più verbosa del necessario.

---

### 3. Il binding implicito usa ID di default

`/projects/1` cerca `Project` con `id = 1`.

---

### 4. Il nome del parametro conta

Usare:

```php
{project}
Project $project
```

rende il binding chiaro e automatico.

---

### 5. Il binding mantiene il 404 automatico

Se il model non viene trovato, Laravel restituisce 404.

---

### 6. Possiamo usare una colonna diversa dall’ID

Esempio:

```php
{project:slug}
```

---

### 7. Uno slug rende l’URL più leggibile

Esempio:

```text
/projects/a-first-project
```

---

### 8. Se usiamo slug negli URL, deve essere unico

Per questo nella migration usiamo:

```php
$table->string('slug')->unique();
```

---

### 9. Il `down()` della migration deve essere coerente

Se `up()` aggiunge `slug`, `down()` deve rimuoverlo.

---

### 10. `$fillable` va aggiornato se useremo mass assignment

Con `slug`:

```php
protected $fillable = [
    'name',
    'slug',
];
```

---

## 23. Comandi utili

Entrare nell’app Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Creare la migration:

```bash
php artisan make:migration add_slug_to_projects_table
```

Eseguire migration:

```bash
php artisan migrate
```

Controllare stato migration:

```bash
php artisan migrate:status
```

Mostrare route:

```bash
php artisan route:list
```

Avviare server:

```bash
php artisan serve
```

Provare URL con slug:

```text
http://127.0.0.1:8000/projects/a-first-project
```

Provare 404:

```text
http://127.0.0.1:8000/projects/slug-che-non-esiste
```

---

## 24. Stato finale della lezione

Alla fine della lezione sappiamo:

- cos’è il route model binding
- come sostituisce `findOrFail($id)` manuale
- perché `{project}` e `Project $project` lavorano insieme
- che il binding usa l’ID di default
- come aggiungere una colonna `slug`
- perché `slug` deve essere unico
- come scrivere una migration con `up()` e `down()` coerenti
- come cercare manualmente per slug con `where(...)->firstOrFail()`
- come usare binding su colonna custom con `{project:slug}`
- perché questa funzione riduce codice ripetitivo

Obiettivo raggiunto:

> abbiamo trasformato la pagina progetto da ricerca manuale a route model binding, prima per ID e poi per slug.

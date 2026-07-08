# Laravel — Sintesi delle nozioni fondamentali

Sintesi personale delle prime 15 lezioni del percorso **Getting Started with Laravel**.

Questa pagina non sostituisce le lesson learned dettagliate: serve come mappa compatta dei concetti davvero acquisiti, utile per ripasso, orientamento nel repo e memoria operativa.

---

## 1. Ambiente e avvio

Laravel gira su **PHP 8.3+**, con:

- **Composer** per le dipendenze PHP
- **Node.js/npm** per la parte frontend e Vite
- **Artisan** come CLI principale
- **SQLite** come database semplice per iniziare

Creazione progetto:

```bash
laravel new first-project
```

Avvio server locale:

```bash
php artisan serve
```

Per partire in modo minimale:

- starter kit: `none`
- database: SQLite
- niente build frontend subito

**Artisan** è la CLI centrale di Laravel: crea file, avvia server, gestisce database, mostra route e fornisce comandi di supporto.

Regola pratica:

> prima di creare qualcosa a mano, chiediti se esiste un comando `make:*`.

---

## 2. Struttura del progetto

| Percorso | Ruolo |
|---|---|
| `app/` | Codice applicativo: model, controller, provider |
| `routes/web.php` | Ingresso delle richieste web |
| `resources/views/` | Template Blade, cioè HTML dinamico |
| `config/` | Configurazione Laravel |
| `.env` | Valori specifici dell’ambiente locale o produzione |
| `database/migrations/` | Schema database versionato |
| `vendor/` | Framework Laravel e dipendenze installate da Composer |

Il progetto Laravel è lo scheletro dell’applicazione.

Il framework vero e proprio sta in:

```text
vendor/laravel/framework
```

Due regole importanti:

- `.env` non si committa mai
- `vendor/` non si modifica a mano e non si committa

---

## 3. Flusso di una richiesta web

Flusso mentale base:

```text
Browser → URL → routes/web.php → Controller/Closure → View/Response → HTML
```

Concetti principali:

- una **route** collega URL + verbo HTTP al codice da eseguire
- `Route::get()` usa la facade `Route`
- una **facade** sembra una chiamata statica, ma dietro usa servizi gestiti da Laravel
- una **closure** nelle route va bene per esempi piccoli
- quando la logica cresce, si passa ai controller
- una **named route** assegna un nome stabile a una route
- `php artisan route:list` mostra tutte le route registrate

Esempio:

```php
Route::get('/', HomeController::class)->name('home');
```

---

## 4. View e Blade

L’HTML non dovrebbe stare dentro le route.

Va nelle view:

```text
resources/views/
```

Esempio:

```php
return view('pages.home');
```

corrisponde a:

```text
resources/views/pages/home.blade.php
```

Questa è la **dot notation**:

```text
pages.home → pages/home.blade.php
```

Passare dati alla view:

```php
return view('pages.home', [
    'greeting' => 'Hello',
]);
```

Dentro Blade:

```blade
{{ $greeting }}
```

Concetti Blade base:

```blade
{{ $var }}
```

stampa un valore.

```blade
@if ($condition)
    ...
@endif
```

mostra contenuto in modo condizionale.

Per valori globali dell’applicazione, come il nome app, meglio usare:

```blade
{{ config('app.name') }}
```

invece di passare quel valore manualmente da ogni route.

---

## 5. Configurazione: `.env → config → codice`

Flusso corretto:

```text
.env → config/*.php → codice applicativo
```

Il file `.env` contiene valori che cambiano tra ambienti:

```text
APP_NAME
APP_ENV
APP_DEBUG
DB_*
```

Nei file di configurazione Laravel si può usare:

```php
env('APP_NAME', 'Laravel')
```

Nel codice applicativo e nelle view, invece, si usa:

```php
config('app.name')
```

Regola pratica:

> nel codice applicativo usa `config()`, non `env()` direttamente.

In produzione:

```text
APP_DEBUG=false
```

---

## 6. Controller

Quando la logica cresce, si sposta dalle closure ai controller.

I controller stanno in:

```text
app/Http/Controllers/
```

Due stili visti finora:

| Stile | Route |
|---|---|
| Controller invocabile | `Route::get('/', HomeController::class)` |
| Metodo nominato | `Route::get('/', [ProjectController::class, 'show'])` |

Un controller invocabile usa:

```php
public function __invoke()
{
    //
}
```

Un controller con metodi nominati usa metodi come:

```php
create()
store()
show()
```

Regola mentale:

```text
Route = cosa viene chiamato
Controller = come viene gestita la richiesta
```

---

## 7. Database: Migration → Model → Eloquent

Flusso fondamentale:

```text
Migration → tabella database
Model     → classe PHP collegata alla tabella
Eloquent  → operazioni CRUD sui dati
```

---

## 8. Migration

Una migration descrive modifiche allo schema del database.

Esempio:

```bash
php artisan make:migration create_projects_table
```

Dentro una migration:

```php
public function up(): void
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
}
```

`up()` applica la modifica.

`down()` descrive come annullarla.

Per modificare una tabella esistente:

```php
Schema::table('projects', function (Blueprint $table) {
    $table->string('slug')->unique();
});
```

Comandi:

```bash
php artisan migrate
php artisan migrate:status
php artisan migrate:rollback
```

Regole importanti:

- le migration si committano
- il database locale non si committa
- in locale puoi fare rollback e rimigrare
- in produzione non si modificano migration già eseguite: si crea una nuova migration

---

## 9. Model

Un model è una classe PHP che rappresenta una tabella.

Esempio:

```bash
php artisan make:model Project
```

Per convenzione:

```text
Project → projects
User    → users
```

Laravel usa il model singolare e la tabella plurale.

Il model `Project` vive in:

```text
app/Models/Project.php
```

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

`$fillable` indica quali campi possono essere riempiti tramite mass assignment.

---

## 10. Eloquent e CRUD base

Eloquent è l’ORM di Laravel.

Permette di lavorare con il database usando model PHP.

| Operazione | Esempio |
|---|---|
| Create | `Project::create([...])` |
| Read tutti | `Project::all()` |
| Read per ID | `Project::find(1)` |
| Read o 404 | `Project::findOrFail(1)` |
| Query | `Project::where('name', 'x')->first()` |
| Update | `$project->update([...])` |
| Delete | `$project->delete()` |

Esempi:

```php
Project::create([
    'name' => 'A New Project',
    'slug' => 'a-new-project',
]);
```

```php
$project = Project::findOrFail(1);
```

```php
$projects = Project::all();
```

```php
$project = Project::where('slug', 'a-first-project')->firstOrFail();
```

`Project::all()` restituisce una **Collection**.

`Project::find()` restituisce un singolo model oppure `null`.

`Project::findOrFail()` restituisce un model oppure genera automaticamente 404.

---

## 11. Mass assignment

Mass assignment significa riempire un model passando un array di dati.

Esempio:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

Laravel protegge questa operazione.

Per autorizzare i campi:

```php
protected $fillable = [
    'name',
    'slug',
];
```

Senza `$fillable`, si può ottenere una `MassAssignmentException` oppure un errore SQL perché alcuni campi non vengono inseriti.

Regola pratica:

> prima di usare `create()` o `update()` con array, controlla `$fillable`.

---

## 12. Timestamp e Carbon

Se la migration contiene:

```php
$table->timestamps();
```

Laravel crea e gestisce automaticamente:

```text
created_at
updated_at
```

Questi campi vengono trattati come oggetti **Carbon**.

Esempi:

```php
$project->created_at->toDateTimeString()
$project->created_at->toTimeString()
$project->created_at->diffForHumans()
```

In Blade:

```blade
{{ $project->created_at->diffForHumans() }}
```

---

## 13. Pagina dinamica completa

Una pagina dettaglio progetto collega insieme route, Eloquent e view.

Versione manuale:

```php
Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Flusso:

```text
URL /projects/1
        ↓
parametro {id}
        ↓
Project::findOrFail($id)
        ↓
model Project
        ↓
view projects.show
        ↓
Blade stampa {{ $project->name }}
```

---

## 14. Route model binding

Laravel può risolvere automaticamente un parametro URL in un model.

Versione con binding su ID:

```php
Route::get('/projects/{project}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
});
```

Laravel capisce:

```text
{project} + Project $project
```

e cerca automaticamente il model.

Se non lo trova, produce 404.

Il binding funziona anche nei controller:

```php
public function show(Project $project)
{
    return view('projects.show', [
        'project' => $project,
    ]);
}
```

---

## 15. Binding su slug

Di default il binding usa l’ID.

Per URL più leggibili possiamo usare uno slug:

```text
/projects/a-first-project
```

Serve una colonna `slug` nella tabella `projects`:

```php
$table->string('slug')->unique();
```

Route model binding su colonna custom:

```php
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');
```

Qui Laravel cerca il progetto usando:

```text
projects.slug
```

e non `projects.id`.

Regola pratica:

> se uno slug identifica una risorsa nell’URL, deve essere unico.

---

## 16. Form e scrittura dati

Schema base:

```text
GET  /projects/create → mostra il form
POST /projects        → riceve e salva i dati
```

Route per mostrare il form:

```php
Route::get('/projects/create', [ProjectController::class, 'create'])
    ->name('projects.create');
```

Route per salvare:

```php
Route::post('/projects', [ProjectController::class, 'store'])
    ->name('projects.store');
```

Form Blade:

```blade
<form action="/projects" method="POST">
    @csrf

    <label for="name">Name</label>
    <input id="name" type="text" name="name">

    <button type="submit">Create project</button>
</form>
```

`@csrf` è obbligatorio nei form che modificano dati.

Senza token CSRF, Laravel può mostrare:

```text
Page expired
```

---

## 17. Request

`Illuminate\Http\Request` rappresenta la richiesta HTTP.

Nel controller:

```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    //
}
```

Leggere un campo:

```php
$request->get('name')
```

oppure:

```php
$request->name
```

Nel laboratorio:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

---

## 18. Slug generation

Laravel offre helper per lavorare con stringhe.

Esempio:

```php
str('A New Project')->slug()
```

Risultato:

```text
a-new-project
```

Nel form:

```php
'slug' => str($request->name)->slug(),
```

Nota importante:

> questa è una soluzione didattica. Se due progetti hanno lo stesso nome, producono lo stesso slug e il vincolo `unique()` può far fallire il salvataggio.

La gestione robusta degli slug duplicati richiede validazione o logica aggiuntiva.

---

## 19. Redirect semplice

Dopo il salvataggio, la lezione usa:

```php
return back();
```

`back()` rimanda l’utente alla pagina precedente.

Nel laboratorio, dopo `POST /projects`, l’utente torna a:

```text
/projects/create
```

Più avanti verranno studiati redirect più espliciti, flash messages e validazione.

---

## 20. Architettura dopo la lezione 15

La logica dei progetti viene spostata in `ProjectController`.

Route:

| Route | Metodo controller | Scopo |
|---|---|---|
| `GET /projects/create` | `create()` | Mostra il form |
| `POST /projects` | `store(Request $request)` | Salva nel database |
| `GET /projects/{project:slug}` | `show(Project $project)` | Mostra pagina dettaglio |

Controller:

```php
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

`routes/web.php` resta più dichiarativo.

La logica applicativa vive nel controller.

---

## 21. Convenzioni RESTful incontrate

| Metodo | Scopo |
|---|---|
| `create()` | mostra il form di creazione |
| `store()` | salva una nuova risorsa |
| `show()` | mostra una singola risorsa |

Laravel offre anche `Route::resource()`, che può registrare automaticamente route RESTful, ma nel laboratorio è stato solo citato.

Per ora è meglio tenere route esplicite.

---

## 22. Regole d’oro

1. **Separazione**: route → controller → model/Eloquent → view
2. **Convenzioni Laravel** prima di configurazioni manuali
3. **Artisan** per creare file: `make:view`, `make:controller`, `make:model`, `make:migration`
4. **`.env → config() → codice`**: non usare `env()` nel codice applicativo
5. **`$fillable`** prima di usare `create()` o `update()` con array
6. **Migration versionate**, database locale non committato
7. **`@csrf`** in ogni form che modifica dati
8. **Route model binding** per evitare `findOrFail()` ripetitivo
9. **`dd()`** solo per debug temporaneo
10. **Slug unique** se lo slug viene usato nell’URL
11. **Controller** quando la logica cresce oltre l’esempio didattico
12. **Non fidarsi dei dati del browser**: la validazione è il prossimo passo

---

## 23. Cosa manca ancora

Argomenti anticipati o naturali dopo le prime 15 lezioni:

- validazione dei form
- messaggi flash
- redirect espliciti
- gestione errori di validazione
- `Route::resource`
- layout Blade
- componenti Blade
- factory e seeder
- relazioni Eloquent
- autenticazione
- test
- gestione robusta degli slug duplicati

---

## 24. Mappa mentale finale

```text
Browser
  ↓
Route
  ↓
Controller
  ↓
Request / Route Model Binding
  ↓
Model + Eloquent
  ↓
Database
  ↓
View Blade
  ↓
HTML
```

Questa è la base pratica Laravel acquisita nelle prime 15 lezioni.

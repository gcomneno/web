# Getting Started with Laravel — Lezione 14
## Submitting a form

Data laboratorio: 2026-06-28  
Corso: Getting Started with Laravel  
Episodio: 14 — Submitting a form  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è creare il primo form funzionante nel laboratorio Laravel.

Finora abbiamo imparato a:

- definire route
- creare view
- usare Blade
- creare migration
- creare model
- leggere dati dal database con Eloquent
- usare route model binding

Ora iniziamo a fare una cosa tipica di quasi ogni applicazione web:

```text
utente compila un form
        ↓
browser invia i dati
        ↓
Laravel riceve la request
        ↓
Eloquent salva i dati nel database
        ↓
utente viene rimandato a una pagina
```

Questa è una lezione importante perché introduce la scrittura di dati dal browser verso il database.

---

## 2. Il web: inviare dati e vedere risultati

Il docente riassume bene il concetto:

> gran parte del web consiste nell’inviare informazioni, far succedere qualcosa sul server e poi vedere un risultato.

Esempi tipici:

- creare un progetto
- inviare un commento
- registrarsi
- fare login
- modificare un profilo
- inviare una ricerca
- salvare impostazioni

In questa lezione creiamo un form per aggiungere un nuovo progetto.

---

## 3. Route per mostrare il form

Per mostrare un form usiamo una route `GET`.

Convenzione Laravel/RESTful:

```text
/projects/create
```

Route:

```php
Route::get('/projects/create', function () {
    return view('projects.create');
})->name('projects.create');
```

Questa route non salva nulla.

Serve solo a mostrare la pagina con il form.

---

## 4. Creare la view `projects.create`

Comando Artisan:

```bash
php artisan make:view projects.create
```

Questo crea:

```text
resources/views/projects/create.blade.php
```

La dot notation:

```text
projects.create
```

corrisponde a:

```text
resources/views/projects/create.blade.php
```

---

## 5. Primo contenuto della view

Una view iniziale può essere:

```blade
<h1>New project</h1>

<form>
    <button>Create project</button>
</form>
```

Visitando:

```text
http://127.0.0.1:8000/projects/create
```

vediamo la pagina del form.

---

## 6. Attributi `action` e `method`

Un form HTML usa normalmente:

```html
<form action="/projects" method="POST">
    ...
</form>
```

Significato:

- `action="/projects"` indica dove inviare i dati
- `method="POST"` indica che stiamo inviando dati per creare qualcosa

Nel nostro caso:

```text
GET  /projects/create → mostra il form
POST /projects        → riceve i dati e crea il progetto
```

---

## 7. Route `POST` per salvare

Serve una seconda route:

```php
use Illuminate\Http\Request;

Route::post('/projects', function (Request $request) {
    dd($request);
})->name('projects.store');
```

Questa route riceve la request del form.

Per ora usiamo `dd($request)` solo per vedere cosa arriva.

---

## 8. `Request`

`Illuminate\Http\Request` rappresenta la richiesta HTTP ricevuta da Laravel.

Contiene molte informazioni:

- dati del form
- query string
- cookie
- header
- file caricati
- metodo HTTP
- URL
- sessione

Nel form della lezione ci interessa soprattutto leggere il campo `name`.

---

## 9. Errore “Page expired”

Quando proviamo a inviare un form `POST` senza token CSRF, Laravel mostra un errore tipo:

```text
Page expired
```

Questo succede perché Laravel protegge le richieste che modificano dati.

Non basta inviare un form: serve anche un token di sicurezza.

---

## 10. CSRF

CSRF significa:

```text
Cross-Site Request Forgery
```

È un tipo di attacco in cui un sito malevolo prova a far inviare una richiesta a nome dell’utente.

Esempio concettuale:

1. l’utente è loggato in una web app
2. un sito esterno prova a inviare una richiesta `POST` verso quella web app
3. se non ci fosse protezione, potrebbe creare/modificare dati a nome dell’utente

Laravel previene questo con un token.

---

## 11. `@csrf`

In Blade, per aggiungere il token CSRF a un form, usiamo:

```blade
@csrf
```

Esempio:

```blade
<form action="/projects" method="POST">
    @csrf

    <button>Create project</button>
</form>
```

Laravel genera automaticamente un input hidden simile a:

```html
<input type="hidden" name="_token" value="...">
```

Quando il form viene inviato, Laravel controlla quel token tramite middleware.

---

## 12. Ogni form che modifica dati deve avere `@csrf`

Regola pratica:

> se il form usa POST, PATCH, PUT o DELETE, metti `@csrf`.

Per i form `GET`, normalmente non serve.

---

## 13. Campo `name`

Il progetto ha almeno un campo:

```text
name
```

Quindi aggiungiamo un input:

```blade
<label for="name">Name</label>

<input id="name" type="text" name="name">
```

Il valore importante è:

```html
name="name"
```

Perché quello sarà il nome del campo letto nella request.

---

## 14. View completa del form

Versione minima:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New project</title>
</head>
<body>
    <h1>New project</h1>

    <form action="/projects" method="POST">
        @csrf

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name">
        </div>

        <button type="submit">Create project</button>
    </form>
</body>
</html>
```

---

## 15. Dove arrivano i dati del form

Quando inviamo il form, dentro `Request` arrivano almeno:

```text
_token
name
```

`_token` è il token CSRF.

`name` è il valore scritto dall’utente.

Non dobbiamo verificare manualmente `_token`: Laravel lo fa per noi.

---

## 16. Leggere dati dalla request con `get()`

Possiamo leggere il campo `name` così:

```php
$request->get('name')
```

Esempio:

```php
Route::post('/projects', function (Request $request) {
    dd($request->get('name'));
});
```

---

## 17. Leggere dati dalla request come proprietà

Laravel permette anche:

```php
$request->name
```

Esempio:

```php
Route::post('/projects', function (Request $request) {
    dd($request->name);
});
```

Il docente spiega che Laravel, tramite meccanismi interni di PHP e della classe `Request`, capisce che stiamo cercando un campo della richiesta.

Per ora è sufficiente sapere che entrambe le forme funzionano.

---

## 18. Altri helper della request

Il docente cita anche metodi come:

```php
$request->only([...])
```

Questi diventano utili quando vogliamo estrarre più campi controllati.

Esempio futuro:

```php
$request->only(['name', 'slug'])
```

In questa lezione usiamo solo `name`.

---

## 19. Creare un progetto con Eloquent

Abbiamo già visto nella lezione 11:

```php
Project::create([
    'name' => 'A second project',
]);
```

Ora il valore non è fisso.

Arriva dal form:

```php
Project::create([
    'name' => $request->name,
]);
```

Ma la tabella ora ha anche `slug`, quindi dobbiamo valorizzare anche quello.

---

## 20. Generare lo slug

Per creare uno slug partendo dal nome del progetto, Laravel offre helper per le stringhe.

Il docente mostra l’uso di:

```php
str($request->name)->slug()
```

Esempio:

```php
str('A New Project')->slug()
```

Risultato:

```text
a-new-project
```

Quindi possiamo creare:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

---

## 21. Helper `str()`

`str()` è un helper Laravel per lavorare con stringhe in modo fluente.

Esempio:

```php
str('A New Project')->slug()
```

Produce una stringa adatta agli URL.

Laravel ha molti helper per manipolare stringhe, ma qui ci interessa solo `slug()`.

---

## 22. `Str`

Il docente cita anche la classe:

```php
Illuminate\Support\Str
```

Si può usare così:

```php
Str::slug($request->name)
```

Ma nella lezione preferisce:

```php
str($request->name)->slug()
```

Per il laboratorio seguiamo l’approccio con `str()`.

---

## 23. Il problema di `$fillable`

Se proviamo a creare un project con `slug` ma nel model `Project` abbiamo solo:

```php
protected $fillable = [
    'name',
];
```

Laravel non inserirà `slug` tramite mass assignment.

Può comparire un errore SQL tipo:

```text
slug doesn't have a default value
```

Il problema reale è che `slug` non è autorizzato nel model.

La correzione è:

```php
protected $fillable = [
    'name',
    'slug',
];
```

Nel nostro laboratorio questo dovrebbe essere già stato fatto nella lezione 13.

---

## 24. Salvare il progetto

Route completa:

```php
Route::post('/projects', function (Request $request) {
    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
})->name('projects.store');
```

Questa route:

1. riceve i dati
2. legge `$request->name`
3. genera lo slug
4. crea il record nel database
5. torna alla pagina precedente

---

## 25. `return back()`

`back()` è un helper Laravel che rimanda l’utente alla pagina precedente.

Nel nostro caso:

```text
/projects/create
```

Dopo aver salvato il progetto, l’utente torna al form.

Il docente dice che in seguito verranno approfonditi meglio redirect, validazione e flash messages.

Per ora `return back()` basta per vedere che il salvataggio funziona.

---

## 26. Cosa manca ancora

Questa lezione non affronta ancora:

- validazione
- messaggi di successo
- messaggi di errore
- gestione slug duplicati
- redirect verso la pagina dettaglio del progetto
- lista progetti
- form con valore precedente
- protezione più robusta dei dati
- controller dedicato
- route resource

Sono argomenti naturali per le lezioni successive.

---

## 27. Codice pratico consigliato per il laboratorio

### Route `projects.create`

```php
Route::get('/projects/create', function () {
    return view('projects.create');
})->name('projects.create');
```

---

### Route `projects.store`

```php
use Illuminate\Http\Request;

Route::post('/projects', function (Request $request) {
    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
})->name('projects.store');
```

---

### View `projects.create`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New project</title>
</head>
<body>
    <h1>New project</h1>

    <form action="/projects" method="POST">
        @csrf

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name">
        </div>

        <button type="submit">Create project</button>
    </form>
</body>
</html>
```

---

## 28. Attenzione: slug duplicati

Dato che la colonna `slug` è `unique`, se proviamo a creare due progetti con lo stesso nome:

```text
A New Project
A New Project
```

entrambi produrranno:

```text
a-new-project
```

Il secondo inserimento può fallire per violazione del vincolo unique.

Per ora va bene: non abbiamo ancora studiato validazione e gestione errori.

Ma è un punto da ricordare.

---

## 29. Lesson Learned

### 1. Un form `GET` mostra dati o pagine

Nel nostro caso:

```text
GET /projects/create
```

mostra il form.

---

### 2. Un form `POST` invia dati

Nel nostro caso:

```text
POST /projects
```

crea un nuovo progetto.

---

### 3. `@csrf` è obbligatorio nei form che modificano dati

Senza `@csrf`, Laravel mostra “Page expired”.

---

### 4. `Request` contiene i dati della richiesta HTTP

Esempio:

```php
function (Request $request) {
    //
}
```

---

### 5. Possiamo leggere un campo con `get()`

Esempio:

```php
$request->get('name')
```

---

### 6. Possiamo leggere un campo anche come proprietà

Esempio:

```php
$request->name
```

---

### 7. Eloquent può salvare dati ricevuti dal form

Esempio:

```php
Project::create([
    'name' => $request->name,
]);
```

---

### 8. `str()->slug()` genera uno slug

Esempio:

```php
str($request->name)->slug()
```

---

### 9. `$fillable` deve includere i campi creati via mass assignment

Nel nostro caso:

```php
protected $fillable = [
    'name',
    'slug',
];
```

---

### 10. `return back()` rimanda alla pagina precedente

È una scorciatoia utile nelle prime prove con i form.

---

## 30. Comandi utili

Entrare nell’app Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Creare la view:

```bash
php artisan make:view projects.create
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

Controllare nel database tramite Tinker:

```bash
php artisan tinker
```

Dentro Tinker:

```php
App\Models\Project::all();
exit
```

---

## 31. Stato finale della lezione

Alla fine della lezione sappiamo:

- creare una route `GET` per mostrare un form
- creare una route `POST` per ricevere dati
- creare una view `projects.create`
- costruire un form HTML in Blade
- usare `@csrf`
- ricevere una `Request`
- leggere `$request->name`
- creare un record con `Project::create()`
- generare uno slug con `str()->slug()`
- evitare l’errore di mass assignment aggiornando `$fillable`
- tornare alla pagina precedente con `return back()`

Obiettivo raggiunto:

> abbiamo creato il primo form Laravel che salva un progetto nel database.

# Getting Started with Laravel — Lezione 21
## Other HTTP verbs

[English](lesson-21-learned.md) | [Italiano](lesson-21-learned.it.md)

Data laboratorio: 2026-08-06
Corso: Getting Started with Laravel
Episodio: 21 — Other HTTP verbs
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è usare verbi HTTP diversi da `GET` e `POST` dentro un’app Blade tradizionale.

Il caso pratico scelto è la cancellazione di un progetto.

Finora abbiamo:

```text
GET  /projects          → lista progetti
GET  /projects/create   → form creazione
POST /projects          → salva progetto
GET  /projects/{slug}   → dettaglio progetto
```

Ora aggiungiamo:

```text
DELETE /projects/{slug} → elimina progetto
```

---

## 2. Perché non usare un link GET per cancellare

Una tentazione sbagliata sarebbe creare una route tipo:

```php
Route::get('/projects/{project:slug}/delete', ...);
```

oppure un link:

```blade
<a href="/projects/some-project/delete">Delete</a>
```

Questo è sbagliato perché una richiesta `GET` non dovrebbe modificare lo stato dell’applicazione.

Una cancellazione modifica dati.

Quindi non deve essere eseguita con `GET`.

---

## 3. Problema di sicurezza

Se la cancellazione fosse una semplice route `GET`, basterebbe visitare un URL per eliminare un progetto.

Esempio:

```text
/projects/a-first-project/delete
```

Questo sarebbe pericoloso.

Un attaccante potrebbe indurre l’utente a visitare quell’URL, per esempio tramite link o immagine nascosta.

Per operazioni distruttive serve usare il verbo HTTP corretto e proteggere la richiesta con CSRF.

---

## 4. Verbo HTTP corretto: `DELETE`

Per cancellare una risorsa, il verbo HTTP corretto è:

```text
DELETE
```

In Laravel:

```php
Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

Questa route dice:

```text
DELETE /projects/{project:slug} → ProjectController@destroy
```

---

## 5. Metodo `destroy()`

Nel controller RESTful, il metodo convenzionale per eliminare una risorsa è:

```php
destroy()
```

Nel nostro caso:

```php
public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

Il parametro `Project $project` usa route model binding.

Laravel carica automaticamente il progetto usando lo slug definito nella route:

```text
{project:slug}
```

---

## 6. Route model binding anche per DELETE

La route:

```php
Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

e il metodo:

```php
public function destroy(Project $project)
```

funzionano insieme.

Laravel:

1. legge lo slug dall’URL
2. cerca il `Project`
3. lo passa al metodo `destroy()`
4. se non lo trova, genera 404

---

## 7. I form HTML non supportano direttamente DELETE

Un form HTML tradizionale supporta direttamente soprattutto:

```text
GET
POST
```

Non possiamo fare semplicemente:

```blade
<form method="DELETE">
```

in modo affidabile nei browser.

Per questo Laravel usa il method spoofing.

---

## 8. Method spoofing

Method spoofing significa:

> inviare realmente una richiesta POST, ma dire a Laravel di trattarla come DELETE.

Il form usa:

```blade
method="POST"
```

ma dentro contiene:

```blade
@method('DELETE')
```

Laravel genera un campo hidden simile a:

```html
<input type="hidden" name="_method" value="DELETE">
```

Quando la request arriva, Laravel capisce che deve trattarla come `DELETE`.

---

## 9. Form di cancellazione

Nella lista progetti possiamo aggiungere un form per ogni progetto:

```blade
<form action="{{ route('projects.destroy', $project) }}" method="POST">
    @csrf
    @method('DELETE')

    <button type="submit">Delete</button>
</form>
```

Punti importanti:

- `action` usa la named route `projects.destroy`
- passiamo `$project` perché la route richiede `{project:slug}`
- il metodo HTML è `POST`
- `@method('DELETE')` dice a Laravel di usare `DELETE`
- `@csrf` protegge la richiesta

---

## 10. Perché serve ancora `@csrf`

Anche se stiamo simulando `DELETE`, il form invia comunque una richiesta che modifica dati.

Quindi serve:

```blade
@csrf
```

Regola pratica:

> ogni form che modifica dati deve avere `@csrf`.

Questo vale per:

```text
POST
PUT
PATCH
DELETE
```

---

## 11. Errore senza `@method('DELETE')`

Se il form usa solo:

```blade
<form method="POST">
```

ma la route è:

```php
Route::delete(...)
```

Laravel non trova una route `POST` compatibile.

Errore tipico:

```text
The POST method is not supported for route ...
Supported methods: DELETE
```

La correzione è aggiungere:

```blade
@method('DELETE')
```

---

## 12. Cancellare con Eloquent

Abbiamo già visto che un model Eloquent può essere cancellato con:

```php
$project->delete();
```

Nel metodo `destroy()`:

```php
public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

Dato che `$project` arriva già dal route model binding, non dobbiamo fare una query manuale.

---

## 13. Redirect dopo cancellazione

Dopo la cancellazione possiamo usare:

```php
return back();
```

oppure un redirect esplicito:

```php
return redirect()->route('projects.index');
```

La lezione mostra anche la scorciatoia:

```php
return to_route('projects.index');
```

Tutte queste opzioni possono funzionare.

Per un’azione distruttiva dalla lista progetti, è sensato tornare alla lista.

---

## 14. Redirect dopo creazione

La lezione migliora anche il comportamento dopo la creazione.

Prima:

```php
return back()->with('status', 'Your project was created.');
```

cioè dopo aver creato il progetto, l’utente tornava al form.

Ora ha più senso tornare alla lista:

```php
return redirect()
    ->route('projects.index')
    ->with('status', 'Your project was created.');
```

Così l’utente vede subito il progetto appena creato nella lista.

---

## 15. Spostare il flash message sulla lista

Se dopo la creazione reindirizziamo verso:

```text
/projects
```

il messaggio flash deve essere mostrato nella view `projects.index`, non solo in `projects.create`.

Quindi il blocco:

```blade
@session('status')
    <p>{{ $value }}</p>
@endsession
```

va messo in:

```text
resources/views/projects/index.blade.php
```

Possiamo anche tenerlo in entrambe le view, ma in questa fase è più coerente metterlo dove reindirizziamo.

---

## 16. View indice aggiornata

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

    @session('status')
        <p>{{ $value }}</p>
    @endsession

    <p>
        <a href="{{ route('projects.create') }}">New project</a>
    </p>

    <ul>
        @foreach ($projects as $project)
            <li>
                <a href="{{ route('projects.show', $project) }}">
                    {{ $project->name }}
                </a>

                <form action="{{ route('projects.destroy', $project) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
```

L’HTML è volutamente semplice e non ancora curato.

---

## 17. Controller aggiornato

`app/Http/Controllers/ProjectController.php`:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'max:255'],
    ]);

    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return redirect()
        ->route('projects.index')
        ->with('status', 'Your project was created.');
}

public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

---

## 18. Route aggiornata

`routes/web.php`:

```php
Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

Questa route va insieme alle altre route dei progetti.

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

Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

---

## 19. `redirect()->route()` e `to_route()`

La lezione cita due modi per reindirizzare a una named route.

Forma esplicita:

```php
return redirect()->route('projects.index');
```

Scorciatoia:

```php
return to_route('projects.index');
```

In questa fase usiamo `redirect()->route()` perché è più descrittivo per lo studio.

---

## 20. Messaggi flash anche con redirect esplicito

Possiamo concatenare `with()` anche a un redirect verso una route:

```php
return redirect()
    ->route('projects.index')
    ->with('status', 'Your project was created.');
```

Quindi `with()` non funziona solo con `back()`.

Funziona anche con redirect verso named route.

---

## 21. Problema ancora non risolto: slug duplicati

Durante la lezione compare ancora il problema dello slug duplicato.

Se creiamo due progetti con lo stesso nome:

```text
Great project
Great project
```

entrambi generano:

```text
great-project
```

La colonna `slug` è unica, quindi il secondo insert può fallire.

Questo sarà da gestire più avanti con validazione o logica dedicata.

---

## 22. Cosa non viene ancora gestito

Questa lezione non introduce ancora:

- pagina di conferma cancellazione
- dialog JavaScript di conferma
- autorizzazioni
- policy
- soft delete
- messaggio flash dopo cancellazione
- gestione elegante dello slug duplicato
- layout condiviso per flash messages

---

## 23. Lesson Learned

### 1. Non usare GET per azioni distruttive

Una cancellazione non deve essere un link GET.

---

### 2. Per cancellare si usa DELETE

In Laravel:

```php
Route::delete(...)
```

---

### 3. I form Blade usano POST + method spoofing

Esempio:

```blade
<form method="POST">
    @csrf
    @method('DELETE')
</form>
```

---

### 4. `@method('DELETE')` genera un campo hidden `_method`

Laravel lo usa per trattare la request come DELETE.

---

### 5. `@csrf` serve anche per DELETE

Ogni form che modifica dati deve essere protetto.

---

### 6. `destroy()` è il metodo RESTful per eliminare

Esempio:

```php
public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

---

### 7. Dopo una creazione è spesso meglio tornare alla lista

Esempio:

```php
return redirect()
    ->route('projects.index')
    ->with('status', 'Your project was created.');
```

---

### 8. `with()` funziona anche con redirect verso named route

Non solo con `back()`.

---

### 9. Il flash message va mostrato nella pagina di destinazione

Se reindirizzi a `projects.index`, mostra il messaggio in `projects.index`.

---

## 24. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Controllare route:

```bash
php artisan route:list
```

Controllare controller:

```bash
sed -n '1,260p' app/Http/Controllers/ProjectController.php
```

Controllare view indice:

```bash
sed -n '1,260p' resources/views/projects/index.blade.php
```

Avviare server:

```bash
php artisan serve
```

Aprire lista progetti:

```text
http://127.0.0.1:8000/projects
```

---

## 25. Stato finale della lezione

Alla fine della lezione sappiamo:

- usare una route `DELETE`
- creare un metodo `destroy()`
- cancellare un model con `$project->delete()`
- usare un form Blade per inviare una cancellazione
- usare `@method('DELETE')`
- proteggere il form con `@csrf`
- reindirizzare verso `projects.index`
- flashare un messaggio anche su redirect esplicito
- spostare il messaggio flash nella view di destinazione

Obiettivo raggiunto:

> la lista progetti ora permette di cancellare un progetto usando il verbo HTTP corretto.

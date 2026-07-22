# Getting Started with Laravel — Lezione 18
## Flashing messages

Data laboratorio: 2026-07-22  
Corso: Getting Started with Laravel  
Episodio: 18 — Flashing messages  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è mostrare un messaggio temporaneo dopo un redirect.

Nelle lezioni precedenti abbiamo costruito il ciclo base del form:

```text
16 → validare i dati
17 → mostrare gli errori
18 → mostrare un messaggio di successo
```

Ora vogliamo che, dopo aver creato un progetto, la pagina mostri qualcosa come:

```text
Your project was created.
```

Questo messaggio deve essere temporaneo.

Deve comparire subito dopo il redirect e sparire al refresh successivo.

---

## 2. Il punto di partenza

Nel controller abbiamo già:

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

    return back();
}
```

Questo codice:

1. valida la request
2. salva il progetto
3. torna alla pagina precedente

Ma l’utente non riceve conferma visibile del successo.

---

## 3. Flash message

Un flash message è un messaggio salvato temporaneamente in sessione.

Caratteristiche:

- vive solo per la richiesta successiva
- è perfetto dopo un redirect
- viene mostrato una volta
- poi viene rimosso automaticamente

Esempi tipici:

```text
Project created.
Profile updated.
Password changed.
Comment deleted.
```

---

## 4. `with()`

Laravel permette di allegare dati flash a un redirect con:

```php
return back()->with('status', 'Your project was created.');
```

`with()` riceve:

```text
chiave
valore
```

Nel nostro caso:

```text
status → Your project was created.
```

Codice finale:

```php
return back()->with('status', 'Your project was created.');
```

---

## 5. Controller aggiornato

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

    return back()->with('status', 'Your project was created.');
}
```

Questo è il modo compatto e consigliato in questa fase.

---

## 6. Mostrare il messaggio nella view

Nella view `projects.create`, possiamo leggere il messaggio dalla sessione.

Approccio con direttiva Blade:

```blade
@session('status')
    <p>{{ $value }}</p>
@endsession
```

Dentro `@session`, Laravel rende disponibile la variabile:

```php
$value
```

che contiene il valore della chiave `status`.

---

## 7. View aggiornata

`resources/views/projects/create.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New project</title>
</head>
<body>
    <h1>New project</h1>

    @session('status')
        <p>{{ $value }}</p>
    @endsession

    <form action="/projects" method="POST">
        @csrf

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}">

            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Create project</button>
    </form>
</body>
</html>
```

---

## 8. Alternativa con `@if`

La lezione mostra anche un approccio più esplicito:

```blade
@if (session()->has('status'))
    <p>{{ session()->get('status') }}</p>
@endif
```

Oppure, in forma ancora più compatta:

```blade
@if (session('status'))
    <p>{{ session('status') }}</p>
@endif
```

Entrambi gli approcci funzionano.

La direttiva `@session` è più breve.

La forma `@if` è più esplicita.

---

## 9. Differenza tra `@error` e `@session`

Per gli errori di validazione abbiamo usato:

```blade
@error('name')
    <p>{{ $message }}</p>
@enderror
```

Dentro `@error`, la variabile disponibile è:

```php
$message
```

Per il messaggio flash con `@session`:

```blade
@session('status')
    <p>{{ $value }}</p>
@endsession
```

Dentro `@session`, la variabile disponibile è:

```php
$value
```

Attenzione quindi a non usare `$message` dentro `@session`.

---

## 10. Errore comune

Questo non funziona dentro `@session`:

```blade
@session('status')
    <p>{{ $message }}</p>
@endsession
```

Può generare:

```text
Undefined variable $message
```

Perché `$message` esiste dentro `@error`, non dentro `@session`.

Con `@session` bisogna usare:

```blade
{{ $value }}
```

---

## 11. Sessione manuale con `session()->flash()`

La lezione mostra anche il meccanismo manuale.

Invece di:

```php
return back()->with('status', 'Your project was created.');
```

si potrebbe fare:

```php
session()->flash('status', 'Your project was created.');

return back();
```

Questo produce lo stesso effetto.

`with()` è una scorciatoia comoda per flashare dati durante un redirect.

---

## 12. `session()->put()` non è flash

La lezione mostra un passaggio importante.

Questo:

```php
session()->put('status', 'Your project was created.');
```

non crea un flash message.

Inserisce un valore persistente nella sessione.

Quindi il messaggio può restare anche dopo il refresh.

Non è quello che vogliamo per un messaggio di successo temporaneo.

---

## 13. `flash()` vs `put()`

Differenza fondamentale:

| Metodo | Comportamento |
|---|---|
| `session()->flash()` | valore temporaneo, disponibile nella prossima richiesta |
| `session()->put()` | valore persistente nella sessione |
| `return back()->with()` | scorciatoia per flashare dati nel redirect |

Regola pratica:

> per messaggi dopo un redirect, usa `with()` oppure `session()->flash()`, non `put()`.

---

## 14. Perché il messaggio sparisce al refresh

Un flash message vive solo per la richiesta successiva.

Flusso:

```text
POST /projects
        ↓
salvataggio
        ↓
return back()->with(...)
        ↓
GET /projects/create
        ↓
messaggio mostrato
        ↓
refresh
        ↓
messaggio sparito
```

Questo è il comportamento corretto.

---

## 15. Test manuale

Apri:

```text
http://127.0.0.1:8000/projects/create
```

Inserisci un nome progetto nuovo, per evitare collisioni di slug.

Esempio:

```text
Tabby's project
```

Invia il form.

Risultato atteso:

```text
Your project was created.
```

Poi aggiorna la pagina.

Risultato atteso:

```text
il messaggio sparisce
```

---

## 16. Attenzione agli slug duplicati

Durante il test conviene usare nomi sempre diversi.

Esempi:

```text
A very new project
Another new project
Tabby's project
```

Perché lo slug viene generato da:

```php
str($request->name)->slug()
```

e la colonna `slug` è `unique`.

Se usiamo due volte lo stesso nome, il secondo insert può fallire per collisione slug.

Questo problema non è ancora risolto nel corso.

---

## 17. Codice finale consigliato

### Controller

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

    return back()->with('status', 'Your project was created.');
}
```

### View

```blade
@session('status')
    <p>{{ $value }}</p>
@endsession
```

---

## 18. Lesson Learned

### 1. Un flash message è temporaneo

Viene mostrato nella richiesta successiva e poi sparisce.

---

### 2. `with()` flasha dati durante un redirect

Esempio:

```php
return back()->with('status', 'Your project was created.');
```

---

### 3. `@session` legge valori dalla sessione nella view

Esempio:

```blade
@session('status')
    <p>{{ $value }}</p>
@endsession
```

---

### 4. Dentro `@session` si usa `$value`

Non `$message`.

`$message` appartiene a `@error`.

---

### 5. `session()->flash()` è equivalente ma più esplicito

Esempio:

```php
session()->flash('status', 'Your project was created.');

return back();
```

---

### 6. `session()->put()` non è temporaneo

`put()` salva un valore persistente nella sessione.

Non è adatto per messaggi di successo che devono sparire.

---

### 7. Errori e messaggi di successo sono due facce della UX del form

- `@error` dice cosa correggere
- flash message dice che l’azione è riuscita

---

## 19. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Controllare controller:

```bash
sed -n '1,200p' app/Http/Controllers/ProjectController.php
```

Controllare view:

```bash
sed -n '1,220p' resources/views/projects/create.blade.php
```

Avviare server:

```bash
php artisan serve
```

Aprire form:

```text
http://127.0.0.1:8000/projects/create
```

Controllare ultimi progetti:

```bash
php artisan tinker
```

Dentro Tinker:

```php
App\Models\Project::latest()->take(5)->get();
exit
```

---

## 20. Stato finale della lezione

Alla fine della lezione sappiamo:

- flashare un messaggio temporaneo con `with()`
- mostrare il messaggio nella view con `@session`
- distinguere `$value` da `$message`
- distinguere `session()->flash()` da `session()->put()`
- confermare all’utente che un progetto è stato creato
- completare il ciclo base di un form Laravel

Obiettivo raggiunto:

> dopo la creazione di un progetto, il form mostra un messaggio temporaneo di successo.

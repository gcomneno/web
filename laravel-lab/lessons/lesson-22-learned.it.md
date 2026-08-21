# Getting Started with Laravel — Lezione 22
## Eloquent events

[English](lesson-22-learned.md) | [Italiano](lesson-22-learned.it.md)

Data laboratorio: 2026-08-21  
Corso: Getting Started with Laravel  
Episodio: 22 — Eloquent events  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è introdurre gli eventi Eloquent e usarli per spostare nel model una logica che finora stava nel controller.

Il problema pratico è lo slug duplicato.

Nelle lezioni precedenti, quando creavamo un progetto, facevamo:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

Questo genera lo slug partendo dal nome.

Se però creiamo due progetti con lo stesso nome, otteniamo lo stesso slug.

Esempio:

```text
A new project
A new project
```

produce due volte:

```text
a-new-project
```

Dato che la colonna `slug` è unica, Laravel/database restituisce un errore di vincolo.

---

## 2. Il problema dello slug duplicato

La tabella `projects` ha una colonna `slug` con vincolo `unique`.

Questo è sensato perché lo slug viene usato nella URL:

```text
/projects/{project:slug}
```

Se due progetti avessero lo stesso slug, Laravel non saprebbe distinguere quale progetto caricare da quella URL.

Quindi il vincolo `unique` è corretto.

Il problema non è il vincolo.

Il problema è che la generazione dello slug è troppo semplice.

---

## 3. Soluzione pigra: aggiungere timestamp

Una soluzione veloce è aggiungere un valore che renda lo slug diverso.

La lezione mostra l’idea di concatenare un timestamp.

Esempio concettuale:

```php
'slug' => str($request->name . '-' . now()->getTimestamp())->slug(),
```

Così:

```text
A new project
```

può diventare:

```text
a-new-project-1787313600
```

Il timestamp riduce il rischio di duplicati.

Non è la soluzione più elegante in assoluto, ma è sufficiente per il laboratorio.

---

## 4. Perché spostare la logica fuori dal controller

Finora lo slug viene creato dentro `ProjectController@store`.

Questo funziona solo quando il progetto viene creato da quel controller.

Ma in un’app reale potremmo creare progetti da area admin, API, seeder, job in background, comandi Artisan, altri controller o test automatici.

Se la logica dello slug resta nel controller, dobbiamo ricordarci di duplicarla ovunque.

Meglio spostarla nel model `Project`.

---

## 5. Eloquent events

Eloquent permette di agganciarsi agli eventi del ciclo di vita di un model.

Esempi di eventi:

```text
creating
created
updating
updated
deleting
deleted
```

Questi eventi permettono di eseguire codice in momenti specifici.

Nel nostro caso vogliamo impostare lo slug prima che il record venga salvato.

Quindi usiamo:

```text
creating
```

---

## 6. `creating` vs `created`

Differenza importante:

```text
creating → before the record is inserted into the database
created  → after the record has been inserted into the database
```

Per impostare lo slug dobbiamo intervenire prima dell’inserimento.

Quindi l’evento giusto è:

```php
creating
```

Se usassimo `created`, il record sarebbe già stato salvato e sarebbe troppo tardi per valorizzare lo slug al primo insert.

---

## 7. Metodo `booted()`

Nel model possiamo definire:

```php
protected static function booted()
{
    //
}
```

Questo metodo viene chiamato quando il model viene avviato da Eloquent.

Dentro `booted()` possiamo registrare eventi del model.

Nel corso viene usata l’idea:

```php
static::creating(function (Project $project) {
    //
});
```

---

## 8. Spostare la generazione dello slug nel model

Nel model `Project` aggiungiamo:

```php
protected static function booted()
{
    static::creating(function (Project $project) {
        $project->slug = str($project->name . '-' . now()->getTimestamp())->slug();
    });
}
```

Quando chiamiamo:

```php
Project::create([
    'name' => $request->name,
]);
```

Eloquent esegue l’evento `creating`.

Prima di salvare il record, assegna automaticamente:

```php
$project->slug
```

---

## 9. Controller più pulito

Dopo questa modifica, il controller non deve più occuparsi dello slug.

Prima:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

Dopo:

```php
Project::create([
    'name' => $request->name,
]);
```

Il controller gestisce la request.

Il model gestisce una regola interna del model.

---

## 10. Perché questa scelta è utile

Spostare lo slug nel model significa che ovunque venga creato un `Project`, lo slug viene generato automaticamente.

Esempio:

```php
Project::create([
    'name' => 'A new project',
]);
```

funziona da controller, Tinker, seeder, test, job e API.

Questo rende il comportamento più coerente.

---

## 11. Attenzione: logica nascosta

La lezione nota anche un aspetto importante.

Gli eventi Eloquent possono nascondere comportamento.

Quando leggiamo il controller:

```php
Project::create([
    'name' => $request->name,
]);
```

non vediamo immediatamente da dove arrivi lo slug.

La logica è nel model.

Questo può essere comodo, ma bisogna sapere che esiste.

Regola pratica:

> gli eventi Eloquent sono potenti, ma non vanno usati per nascondere troppa logica in modo poco leggibile.

---

## 12. Soluzione ancora provvisoria

La lezione non implementa una logica sofisticata per controllare se uno slug esiste già.

Usa un timestamp per evitare il conflitto.

Questa è una soluzione didattica accettabile in questa fase.

Non viene ancora trattato:

- controllo incrementale tipo `a-new-project-2`
- validazione del nome duplicato
- generazione slug più leggibile
- retry in caso di collisione
- service dedicato per slug
- observer separato

---

## 13. Model finale consigliato

`app/Models/Project.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted()
    {
        static::creating(function (Project $project) {
            $project->slug = str($project->name . '-' . now()->getTimestamp())->slug();
        });
    }
}
```

Nota: anche se ora lo slug viene generato automaticamente, lasciamo `slug` in `$fillable` per coerenza con lo stato didattico precedente del corso, salvo diversa evoluzione futura.

---

## 14. Controller finale consigliato

Nel controller, `store()` diventa:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'max:255'],
    ]);

    Project::create([
        'name' => $request->name,
    ]);

    return redirect()
        ->route('projects.index')
        ->with('status', 'Your project was created.');
}
```

---

## 15. Test manuale

Apri:

```text
http://127.0.0.1:8000/projects/create
```

Crea un progetto con un nome già usato, per esempio:

```text
A new project
```

Risultato atteso:

```text
no unique constraint error
redirect to /projects
flash message visible
new project in the list
URL with a slug that also contains a timestamp
```

Esempio di slug:

```text
a-new-project-1787313600
```

---

## 16. Test con Tinker

Entrare in Tinker:

```bash
php artisan tinker
```

Creare un progetto:

```php
App\Models\Project::create([
    'name' => 'A new project',
]);
```

Controllare lo slug:

```php
App\Models\Project::latest()->first();
```

Uscire:

```php
exit
```

Il punto importante è che lo slug venga creato anche da Tinker, non solo dal controller.

---

## 17. Lesson Learned

### 1. Gli Eloquent events permettono di agganciarsi al ciclo di vita del model

Esempi:

```text
creating
created
updating
updated
deleting
deleted
```

### 2. `creating` avviene prima del salvataggio

È il posto giusto per impostare dati necessari prima dell’insert.

### 3. `created` avviene dopo il salvataggio

Non è adatto per valorizzare una colonna richiesta prima dell’insert.

### 4. `booted()` registra eventi del model

Esempio:

```php
protected static function booted()
{
    static::creating(function (Project $project) {
        //
    });
}
```

### 5. Lo slug può essere generato automaticamente dal model

Così il controller non deve più occuparsene.

### 6. Spostare logica nel model rende il comportamento più globale

Ogni `Project::create()` genera lo slug, indipendentemente da dove viene chiamato.

### 7. Gli eventi Eloquent vanno usati con attenzione

Possono rendere il controller più pulito, ma anche nascondere comportamento.

### 8. Il timestamp evita il conflitto didattico degli slug duplicati

Non è una soluzione perfetta, ma risolve il problema incontrato nella lezione.

---

## 18. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Controllare il model:

```bash
sed -n '1,220p' app/Models/Project.php
```

Controllare il controller:

```bash
sed -n '1,220p' app/Http/Controllers/ProjectController.php
```

Avviare server:

```bash
php artisan serve
```

Testare Tinker:

```bash
php artisan tinker
```

Dentro Tinker:

```php
App\Models\Project::create(['name' => 'A new project']);
App\Models\Project::latest()->first();
exit
```

---

## 19. Stato finale della lezione

Alla fine della lezione sappiamo:

- che cosa sono gli Eloquent events
- usare `booted()` nel model
- agganciarci all’evento `creating`
- modificare il model prima del salvataggio
- spostare la generazione dello slug dal controller al model
- evitare il problema dello slug duplicato aggiungendo un timestamp
- rendere la creazione dello slug coerente ovunque venga creato un progetto

Obiettivo raggiunto:

> ogni nuovo progetto genera automaticamente il proprio slug tramite un evento Eloquent del model.

# Getting Started with Laravel — Lezione 11
## Meet Eloquent

Data laboratorio: 2026-06-18  
Corso: Getting Started with Laravel  
Episodio: 11 — Meet Eloquent  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è incontrare davvero **Eloquent**, l’ORM di Laravel.

Nella lezione 10 abbiamo visto solo un assaggio:

```php
Project::all()
```

Ora iniziamo a usare Eloquent per le operazioni CRUD fondamentali:

```text
Create → creare record
Read   → leggere record
Update → aggiornare record
Delete → eliminare record
```

Il docente chiarisce che Eloquent è molto ampio e meriterebbe un corso dedicato.

In questa lezione, però, l’obiettivo è imparare il minimo operativo per proseguire nel corso Laravel.

---

## 2. Dove eravamo rimasti

Dalle lezioni precedenti abbiamo:

- una migration per creare la tabella `projects`
- un model `Project`
- una route didattica `/eloquent`
- un primo esperimento con `Project::all()`

Il collegamento concettuale è:

```text
Migration projects → tabella projects
Model Project      → classe PHP per rappresentare righe della tabella
Eloquent           → strumento per leggere/scrivere dati tramite il model
```

---

## 3. Convenzione model/tabella

Laravel usa molto le convenzioni.

Un model singolare viene associato automaticamente alla tabella plurale.

Esempio:

| Model | Tabella prevista |
|---|---|
| `Project` | `projects` |
| `User` | `users` |
| `Post` | `posts` |

Quindi se abbiamo:

```php
class Project extends Model
{
    //
}
```

Laravel assume che il model usi la tabella:

```text
projects
```

---

## 4. Cambiare nome tabella manualmente

Se la tabella non rispetta la convenzione Laravel, possiamo specificarla nel model.

Esempio:

```php
class Project extends Model
{
    protected $table = 'some_projects';
}
```

Nel nostro laboratorio non serve.

Abbiamo:

```text
Project → projects
```

quindi la convenzione funziona.

Regola pratica:

> se puoi seguire le convenzioni Laravel, segui le convenzioni Laravel.

---

## 5. CRUD

CRUD è un acronimo fondamentale.

Significa:

| Lettera | Operazione | Significato |
|---|---|---|
| C | Create | creare dati |
| R | Read | leggere dati |
| U | Update | aggiornare dati |
| D | Delete | eliminare dati |

Questa lezione mostra un primo giro pratico di CRUD con Eloquent.

---

## 6. Read: leggere tutti i record con `all()`

Metodo già visto:

```php
Project::all()
```

Questo restituisce tutti i record della tabella `projects`.

Risultato:

```text
Collection di Project
```

Se la tabella è vuota, la collection è vuota.

Se contiene record, ogni record viene restituito come oggetto `Project`.

---

## 7. Create: creare un record con `create()`

Per creare un progetto possiamo usare:

```php
Project::create([
    'name' => 'A second project',
]);
```

Questo dice a Eloquent:

> crea una nuova riga nella tabella `projects` con `name = A second project`.

Ma alla prima prova compare un errore.

---

## 8. Mass assignment exception

Se proviamo a usare `create()` senza configurare il model, Laravel mostra una:

```text
MassAssignmentException
```

Il messaggio dice di aggiungere `name` alla proprietà `$fillable`.

Questo succede perché Laravel protegge i model dall’assegnazione massiva non autorizzata.

---

## 9. Mass assignment

Mass assignment significa riempire un model passando un array di dati.

Esempio:

```php
Project::create([
    'name' => 'A second project',
]);
```

È comodo, ma potenzialmente pericoloso.

Se accettassimo dati utente senza controllo, qualcuno potrebbe provare a riempire campi che non dovrebbe toccare.

Esempio concettuale pericoloso:

```php
[
    'name' => 'Project',
    'is_admin' => true,
]
```

Per questo Laravel richiede una lista di campi consentiti.

---

## 10. `$fillable`

Nel model possiamo aggiungere:

```php
protected $fillable = [
    'name',
];
```

Esempio completo:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
    ];
}
```

Ora questo funziona:

```php
Project::create([
    'name' => 'A second project',
]);
```

`$fillable` dice:

> questi campi possono essere riempiti tramite mass assignment.

---

## 11. `$guarded`

Il docente cita anche l’approccio opposto:

```php
protected $guarded = [];
```

Questo significa:

> non proteggere nessun campo.

È comodo, ma più rischioso.

Permetterebbe di impostare anche campi come:

- `id`
- `created_at`
- `updated_at`
- eventuali campi sensibili futuri

Il docente dice che spesso gli sviluppatori si stancano di `$fillable` e preferiscono unguardare tutto.

Per ora nel nostro laboratorio restiamo più prudenti:

```php
protected $fillable = [
    'name',
];
```

---

## 12. Read: leggere un singolo record con `find()`

Per cercare un record per ID:

```php
Project::find(1)
```

Questo cerca nella tabella `projects` il record con:

```text
id = 1
```

Non serve scrivere:

```php
Project::find(['id' => 1])
```

`find()` lavora direttamente sulla primary key.

Esempi:

```php
Project::find(1);
Project::find(2);
```

Risultato:

- se trova il record, restituisce un oggetto `Project`
- se non lo trova, restituisce `null`

---

## 13. Model instance

Quando facciamo:

```php
$project = Project::find(1);
```

`$project` è un’istanza del model `Project`.

Quindi non è una collection.

È un singolo oggetto che rappresenta una singola riga del database.

---

## 14. Query con `where()`

Eloquent permette anche query più esplicite:

```php
Project::where('name', 'A second project')
```

Attenzione però:

questa riga da sola non restituisce ancora i risultati.

Restituisce un **query builder**.

Serve chiudere la query con un metodo finale, ad esempio:

```php
get()
first()
firstOrFail()
```

---

## 15. Query builder

Quando scriviamo:

```php
Project::where('name', 'A second project')
```

stiamo costruendo una query.

Laravel non la esegue ancora.

È come dire:

> prepara una ricerca sui project dove name è uguale a A second project.

Per ottenere risultati dobbiamo concludere la catena.

---

## 16. `get()`

Esempio:

```php
Project::where('name', 'A second project')->get()
```

`get()` esegue la query e restituisce una collection.

Anche se trova un solo record, il risultato è comunque una collection.

Questo perché potrebbero esistere più record con lo stesso nome.

---

## 17. `first()`

Esempio:

```php
Project::where('name', 'A second project')->first()
```

`first()` esegue la query e restituisce solo il primo record trovato.

Risultato:

- un model `Project`, se trova qualcosa
- `null`, se non trova niente

---

## 18. Cercare per ID con `where()`

Possiamo anche scrivere:

```php
Project::where('id', 1)->first()
```

Questo è simile a:

```php
Project::find(1)
```

`find()` è più compatto quando cerchiamo per primary key.

---

## 19. `firstOrFail()`

Se vogliamo ottenere un record o fallire con errore 404:

```php
Project::where('id', 5)->firstOrFail()
```

Se non esiste un progetto con ID 5, Laravel genera automaticamente una risposta 404.

Questo è utile nelle pagine che devono mostrare una risorsa specifica.

---

## 20. `findOrFail()`

Versione compatta per cercare per ID e fallire con 404:

```php
Project::findOrFail(5)
```

Se il record non esiste:

```text
404 Not Found
```

Se esiste, restituisce il model.

Esempio:

```php
Project::findOrFail(1)
```

---

## 21. Update: aggiornare un record

Prima recuperiamo il model:

```php
$project = Project::find(1);
```

Poi aggiorniamo:

```php
$project->update([
    'name' => 'A first project',
]);
```

Nota importante:

`update()` qui non è statico.

Lo chiamiamo sull’istanza specifica:

```php
$project->update(...)
```

perché stiamo aggiornando quel record.

---

## 22. Attenzione ai nomi delle colonne

Nel video il docente sbaglia operativamente usando un campo non corretto, tipo `title`, mentre la tabella ha `name`.

Se la tabella ha:

```text
name
```

dobbiamo aggiornare:

```php
$project->update([
    'name' => 'A first project',
]);
```

Non:

```php
$project->update([
    'title' => 'A first project',
]);
```

Eloquent lavora sui nomi reali delle colonne.

---

## 23. `updated_at`

Quando aggiorniamo un record con Eloquent, Laravel aggiorna automaticamente:

```text
updated_at
```

Questo succede perché la migration contiene:

```php
$table->timestamps();
```

Laravel gestisce automaticamente:

```text
created_at
updated_at
```

se il model usa i timestamp standard.

---

## 24. Delete: eliminare un record

Prima recuperiamo il record:

```php
$project = Project::find(2);
```

Poi eliminiamo:

```php
$project->delete();
```

Questo elimina la riga corrispondente dal database.

Anche qui `delete()` è chiamato sull’istanza del model.

---

## 25. Route didattica usata nella lezione

Il docente usa una route sperimentale per provare Eloquent.

Nel nostro laboratorio possiamo continuare a usare `/eloquent`.

Esempio:

```php
use App\Models\Project;

Route::get('/eloquent', function () {
    Project::create([
        'name' => 'A second project',
    ]);

    dd(Project::all());
})->name('eloquent');
```

Questa route è solo didattica.

Non è codice da lasciare in una vera applicazione così com’è, perché ogni refresh creerebbe un nuovo record.

---

## 26. Evitare creazioni duplicate a ogni refresh

Per evitare che ogni refresh inserisca un nuovo record, per la pratica possiamo commentare o cambiare spesso la route.

Esempio di sola lettura:

```php
Route::get('/eloquent', function () {
    dd(Project::all());
})->name('eloquent');
```

Oppure prova di lettura singola:

```php
Route::get('/eloquent', function () {
    dd(Project::find(1));
})->name('eloquent');
```

Oppure prova di query:

```php
Route::get('/eloquent', function () {
    dd(Project::where('name', 'A second project')->first());
})->name('eloquent');
```

L’obiettivo è imparare, non creare una route definitiva.

---

## 27. Collegamento alla prossima lezione

Il docente anticipa che nella prossima lezione si lavorerà con dati dalla request.

Quindi invece di scrivere ID o valori fissi nel codice, inizieremo a leggere dati dalla richiesta HTTP.

Questo porterà verso concetti come:

- parametri URL
- request data
- route model binding
- form
- input utente

---

## 28. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare già:

- query complesse
- relazioni tra model
- eager loading
- scope
- factory
- seeder
- validazione request
- form HTML
- route model binding completo
- repository pattern
- service layer

Il focus era:

> imparare le operazioni CRUD minime con Eloquent.

---

## 29. Lesson Learned

### 1. Eloquent è l’ORM di Laravel

Permette di lavorare con il database usando model PHP.

---

### 2. Laravel associa model e tabella tramite convenzione

`Project` usa automaticamente la tabella `projects`.

---

### 3. Possiamo sovrascrivere il nome tabella

Con:

```php
protected $table = 'some_projects';
```

---

### 4. CRUD significa Create, Read, Update, Delete

Sono le operazioni base sui dati.

---

### 5. `Project::create()` crea un record

Esempio:

```php
Project::create([
    'name' => 'A second project',
]);
```

---

### 6. Per usare `create()` serve configurare `$fillable`

Esempio:

```php
protected $fillable = [
    'name',
];
```

---

### 7. `$guarded = []` disattiva la protezione mass assignment

Comodo ma più rischioso.

Per ora preferiamo `$fillable`.

---

### 8. `Project::all()` restituisce una collection

Contiene tutti i record della tabella `projects`.

---

### 9. `Project::find(1)` cerca per ID

Restituisce un singolo model oppure `null`.

---

### 10. `where()` costruisce una query

Da solo non restituisce i risultati finali.

---

### 11. `get()` restituisce una collection

Esempio:

```php
Project::where('name', 'A second project')->get()
```

---

### 12. `first()` restituisce il primo model trovato

Oppure `null`.

---

### 13. `firstOrFail()` genera 404 se non trova nulla

Utile per pagine dettaglio.

---

### 14. `findOrFail()` cerca per ID o genera 404

Esempio:

```php
Project::findOrFail(5)
```

---

### 15. `update()` aggiorna un model esistente

Esempio:

```php
$project->update([
    'name' => 'A first project',
]);
```

---

### 16. `delete()` elimina un model esistente

Esempio:

```php
$project->delete();
```

---

### 17. `updated_at` si aggiorna automaticamente

Se la tabella usa:

```php
$table->timestamps();
```

---

### 18. Le route didattiche con `dd()` sono strumenti di esplorazione

Non sono codice finale da applicazione reale.

---

## 30. Comandi e file utili

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Controllare il model:

```bash
sed -n '1,120p' app/Models/Project.php
```

Controllare route:

```bash
sed -n '1,160p' routes/web.php
```

Mostrare route:

```bash
php artisan route:list
```

Avviare server:

```bash
php artisan serve
```

Aprire route didattica:

```text
http://127.0.0.1:8000/eloquent
```

Controllare stato Git:

```bash
cd ~/Progetti/web
git status --short
```

---

## 31. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- perché Eloquent è molto ampio
- come Laravel capisce che `Project` usa `projects`
- come sovrascrivere il nome tabella con `$table`
- cosa significa CRUD
- come creare un record con `create()`
- perché serve `$fillable`
- cosa significa mass assignment
- perché `$guarded = []` è comodo ma rischioso
- come leggere tutti i record con `all()`
- come leggere un record per ID con `find()`
- come usare `where()`
- differenza tra builder, `get()` e `first()`
- come usare `firstOrFail()` e `findOrFail()`
- come aggiornare un record con `update()`
- come eliminare un record con `delete()`
- perché `updated_at` cambia automaticamente

Obiettivo raggiunto:

> abbiamo iniziato a usare Eloquent per le operazioni CRUD fondamentali.

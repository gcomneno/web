# Getting Started with Laravel — Lezione 10
## Making Models

Data laboratorio: 2026-06-18  
Corso: Getting Started with Laravel  
Episodio: 10 — Making Models  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è creare il primo **model** Laravel e capire come si collega alle migration.

La lezione precedente ha introdotto le migration.

Questa lezione introduce i model.

Il percorso concettuale è:

```text
Migration → crea/modifica la tabella
Model     → rappresenta quella tabella nel codice PHP
Eloquent  → permette di leggere/scrivere dati usando i model
```

Il docente tocca appena Eloquent, senza approfondirlo ancora.

L’approfondimento arriverà nelle lezioni successive.

---

## 2. Ripartenza del docente rispetto alla lezione precedente

Nel video il docente cancella manualmente quello che aveva fatto nella lezione 09:

- migration creata
- tabella nel database
- riga nella tabella `migrations`

Lo fa per ripartire da zero e mostrare un comando più comodo.

Nel nostro laboratorio, invece, **non conviene cancellare la migration già committata**.

Abbiamo già committato:

```text
database/migrations/...create_projects_table.php
```

Quindi nel nostro repo teniamo la migration della lezione 09 e aggiungiamo solo il model `Project`.

Il concetto resta lo stesso.

---

## 3. Cos’è un model

Un model è una classe PHP che rappresenta una tabella del database.

Esempio:

```text
tabella database: projects
model PHP:        Project
```

Per convenzione Laravel usa:

- model al singolare
- tabella al plurale

Esempio:

| Model | Tabella |
|---|---|
| `User` | `users` |
| `Project` | `projects` |
| `Post` | `posts` |

Questa convenzione permette a Laravel di collegare automaticamente model e tabella.

---

## 4. Il model `User` già presente

Laravel contiene già un model:

```text
app/Models/User.php
```

Questo model rappresenta la tabella:

```text
users
```

Il docente lo usa per mostrare alcune parti tipiche di un model Laravel.

Il model `User` è più complesso di un model base perché supporta anche funzionalità di autenticazione.

---

## 5. `User` estende `Authenticatable`

Nel model `User` vediamo qualcosa come:

```php
class User extends Authenticatable
```

Non estende direttamente il model base Eloquent.

Questo perché l’utente Laravel può partecipare al sistema di autenticazione.

`Authenticatable` aggiunge funzionalità utili per login, password, identificazione utente e meccanismi collegati.

Per ora non dobbiamo approfondire l’autenticazione.

La cosa importante è:

> `User` è un model speciale perché è collegato anche al sistema auth.

---

## 6. Trait nei model

Nel model `User` si vedono trait come:

```php
use HasFactory, Notifiable;
```

Laravel usa spesso i trait per aggiungere funzionalità.

Esempi:

### `HasFactory`

Permette di usare factory per generare dati finti.

Utile per:

- test
- seeding
- dati demo

### `Notifiable`

Permette al model di ricevere notifiche Laravel.

Per ora non serve usarli direttamente, ma è utile riconoscerli.

---

## 7. Proprietà `$fillable`

Nel model `User` c’è una proprietà:

```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

`$fillable` indica quali campi possono essere riempiti tramite assegnazione massiva.

Esempio concettuale futuro:

```php
User::create([
    'name' => 'Giancarlo',
    'email' => 'giancarlo@example.com',
    'password' => 'secret',
]);
```

Laravel permette di riempire solo i campi elencati in `$fillable`.

Serve come protezione.

Il docente anticipa che può diventare fastidioso, perché ogni nuovo campo va aggiunto alla lista.

Più avanti vedremo anche approcci alternativi.

---

## 8. Proprietà `$hidden`

Nel model `User` c’è anche:

```php
protected $hidden = [
    'password',
    'remember_token',
];
```

`$hidden` indica quali campi nascondere quando il model viene trasformato in array o JSON.

Esempio tipico:

- risposta API
- debug strutturato
- serializzazione dati

Campi come `password` e `remember_token` non devono uscire facilmente.

È una protezione importante.

---

## 9. Cast

Nel model `User` troviamo anche i cast.

I cast indicano a Laravel come trasformare certi valori quando entrano o escono dal model.

Esempi concettuali:

```php
'email_verified_at' => 'datetime'
'password' => 'hashed'
```

### Cast a data

Un timestamp del database può diventare un oggetto data più comodo da usare in PHP.

Laravel usa spesso Carbon per date e orari.

### Cast `hashed`

Una password può essere automaticamente hashata quando viene salvata.

Il docente dice che potresti tecnicamente assegnare una password in chiaro al model e lasciare che il cast la trasformi.

Naturalmente il concetto importante è:

> il database conserva l’hash, non la password leggibile.

---

## 10. Carbon

Carbon è una libreria PHP per lavorare con date e orari.

Laravel la usa spesso.

Permette di:

- leggere date
- formattare date
- confrontare date
- aggiungere o sottrarre tempo
- manipolare timestamp

Nel contesto della lezione, Carbon compare quando Laravel trasforma campi data/timestamp in oggetti più comodi.

---

## 11. Creare un model con Artisan

Per creare un model si usa Artisan:

```bash
php artisan make:model Project
```

Questo crea:

```text
app/Models/Project.php
```

Un model base è molto semplice:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
}
```

Questo `Project` estende:

```php
Illuminate\Database\Eloquent\Model
```

Quindi è un model Eloquent.

---

## 12. Model e tabella

Se creiamo:

```php
class Project extends Model
{
    //
}
```

Laravel si aspetta per convenzione una tabella:

```text
projects
```

Non dobbiamo scrivere manualmente questa associazione se seguiamo le convenzioni.

Laravel fa il collegamento:

```text
Project → projects
```

---

## 13. Creare model e migration insieme

Il docente mostra che spesso, quando creiamo un model, vogliamo anche creare la relativa migration.

Possiamo farlo in un solo comando:

```bash
php artisan make:model Project -m
```

La flag:

```text
-m
```

significa:

```text
migration
```

Quindi il comando crea:

```text
app/Models/Project.php
database/migrations/...create_projects_table.php
```

Questo è molto comodo perché model e tabella nascono insieme.

---

## 14. Altre flag utili

Il docente cita anche la factory, ma non la usa ancora.

Esempio futuro:

```bash
php artisan make:model Project -mf
```

Dove:

- `-m` crea la migration
- `-f` crea la factory

Per ora usiamo solo il model.

Nel nostro laboratorio abbiamo già creato la migration nella lezione 09, quindi ora basta:

```bash
php artisan make:model Project
```

---

## 15. Collegamento con la migration della lezione 09

Nella lezione 09 abbiamo creato una migration per la tabella:

```text
projects
```

con campi:

```text
id
name
created_at
updated_at
```

Ora creiamo il model:

```text
Project
```

che rappresenta quella tabella.

Quindi il ponte diventa:

```text
database/migrations/...create_projects_table.php
        ↓
tabella projects
        ↓
app/Models/Project.php
```

---

## 16. Primo assaggio di Eloquent

Il docente mostra una route temporanea per sperimentare Eloquent.

Esempio:

```php
use App\Models\Project;

Route::get('/eloquent', function () {
    dd(Project::all());
});
```

Questo chiama:

```php
Project::all()
```

e restituisce tutti i record della tabella `projects`.

Per ora non è ancora una pagina vera dell’app.

È solo un esperimento per vedere il model in azione.

---

## 17. `Project::all()`

`Project::all()` chiede a Eloquent:

> dammi tutti i record della tabella collegata al model `Project`.

Laravel capisce che `Project` usa la tabella `projects`.

Il risultato è una **collection**.

---

## 18. Collection

Una collection Laravel è un contenitore di elementi.

Nel caso di:

```php
Project::all()
```

la collection contiene zero, uno o più oggetti `Project`.

Se la tabella `projects` è vuota, la collection è vuota.

Se nella tabella c’è una riga, la collection contiene un model `Project`.

Se ci sono più righe, contiene più model `Project`.

---

## 19. Ogni riga diventa un oggetto model

Quando Eloquent legge una riga dal database, la rappresenta come oggetto model.

Esempio concettuale:

```text
riga database projects:
id = 1
name = Big Project
created_at = ...
updated_at = ...

oggetto PHP:
Project {
    id: 1,
    name: "Big Project",
    created_at: ...
    updated_at: ...
}
```

Questa è l’idea centrale di un ORM.

---

## 20. ORM

ORM significa:

```text
Object-Relational Mapper
```

Eloquent è l’ORM di Laravel.

Il suo compito è collegare:

```text
database relazionale ↔ oggetti PHP
```

Invece di scrivere subito SQL a mano, possiamo usare classi e metodi PHP.

Esempio:

```php
Project::all()
```

più avanti vedremo anche creazione, filtro, aggiornamento ed eliminazione.

---

## 21. Inserimento manuale dati nel video

Nel video il docente inserisce manualmente un record nella tabella `projects`.

Esempio:

```text
name = A big project
created_at = now
updated_at = now
```

Poi ricarica la route `/eloquent` e vede che la collection contiene un elemento.

Nel nostro laboratorio possiamo evitare l’inserimento manuale via GUI e usare più avanti Eloquent o Tinker.

Per questa lezione basta capire il meccanismo.

---

## 22. Route temporanea `/eloquent`

La route `/eloquent` serve solo per esperimento.

Esempio:

```php
Route::get('/eloquent', function () {
    dd(Project::all());
});
```

Questa route è utile didatticamente, ma non è necessariamente una route definitiva del progetto.

Può essere committata se vogliamo mantenere traccia della pratica, oppure rimossa dopo l’esperimento.

Nel nostro laboratorio didattico possiamo tenerla per ora perché rappresenta esattamente la lezione.

---

## 23. Cosa cambia rispetto alla lezione 09

Lezione 09:

```text
abbiamo creato la tabella projects
```

Lezione 10:

```text
creiamo il model Project
leggiamo la tabella projects tramite Eloquent
```

Prima avevamo solo lo scheletro del database.

Ora abbiamo una classe PHP che rappresenta quello scheletro.

---

## 24. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare già:

- creare record con Eloquent
- aggiornare record
- eliminare record
- validare input
- usare form
- mostrare dati in Blade
- relazioni tra model
- factory
- seeder
- autenticazione
- query complesse

Il focus era:

> creare un model e vedere che può leggere dati dalla tabella tramite Eloquent.

---

## 25. Pratica della lezione nel nostro progetto

Nel nostro caso la migration `projects` esiste già, quindi non dobbiamo usare:

```bash
php artisan make:model Project -m
```

perché creerebbe una seconda migration.

Usiamo invece:

```bash
php artisan make:model Project
```

Poi aggiungiamo una route didattica temporanea:

```php
use App\Models\Project;

Route::get('/eloquent', function () {
    dd(Project::all());
})->name('eloquent');
```

Questa route permette di vedere il risultato di Eloquent nel browser.

---

## 26. Stato atteso dei file

Dopo la pratica avremo un nuovo file:

```text
app/Models/Project.php
```

e una modifica a:

```text
routes/web.php
```

La migration della lezione 09 resta quella già esistente.

Non dobbiamo creare una seconda migration `create_projects_table`.

---

## 27. Lesson Learned

### 1. Un model rappresenta una tabella

`Project` rappresenta `projects`.

---

### 2. Laravel usa convenzioni

Model singolare, tabella plurale.

Esempio:

```text
Project → projects
User    → users
```

---

### 3. I model stanno in `app/Models`

Esempio:

```text
app/Models/Project.php
```

---

### 4. Un model base estende `Illuminate\Database\Eloquent\Model`

Questo lo rende un model Eloquent.

---

### 5. `php artisan make:model Project` crea il model

Comando:

```bash
php artisan make:model Project
```

---

### 6. `php artisan make:model Project -m` crea model e migration

Comodo quando la migration non esiste ancora.

Nel nostro caso la migration esiste già, quindi usiamo solo `make:model`.

---

### 7. `User` è un model più complesso

Perché è collegato anche all’autenticazione.

---

### 8. `$fillable` controlla quali campi si possono riempire massivamente

Serve quando si creano o aggiornano record da array di dati.

---

### 9. `$hidden` nasconde campi sensibili

Esempi:

```text
password
remember_token
```

---

### 10. I cast trasformano valori tra database e PHP

Esempi:

- timestamp → oggetto data/Carbon
- password → hash

---

### 11. Eloquent è l’ORM di Laravel

Collega tabelle del database e oggetti PHP.

---

### 12. `Project::all()` legge tutti i progetti

Restituisce una collection di model `Project`.

---

### 13. Una collection può contenere zero, uno o molti model

Se la tabella è vuota, la collection è vuota.

---

### 14. Una riga del database diventa un oggetto model

Eloquent trasforma righe relazionali in oggetti PHP.

---

## 28. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Creare model:

```bash
php artisan make:model Project
```

Controllare model:

```bash
sed -n '1,120p' app/Models/Project.php
```

Controllare route:

```bash
php artisan route:list
```

Eseguire server:

```bash
php artisan serve
```

Aprire route didattica:

```text
http://127.0.0.1:8000/eloquent
```

Controllare modifiche Git:

```bash
cd ~/Progetti/web
git status --short
```

---

## 29. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- cos’è un model Laravel
- dove si trovano i model
- come `User` rappresenta la tabella `users`
- perché `User` è più complesso di un model base
- cosa sono trait come `HasFactory` e `Notifiable`
- a cosa serve `$fillable`
- a cosa serve `$hidden`
- cosa sono i cast
- cos’è Carbon
- come creare un model con Artisan
- come creare model e migration insieme con `-m`
- perché nel nostro caso usiamo solo `make:model`
- come il model `Project` si collega alla tabella `projects`
- cos’è Eloquent a livello introduttivo
- cosa fa `Project::all()`
- cos’è una collection

Obiettivo raggiunto:

> abbiamo creato il primo model concettuale e visto come Laravel collega tabella, model ed Eloquent.

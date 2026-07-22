# Getting Started with Laravel — Lezione 16
## Validating forms

Data laboratorio: 2026-07-22  
Corso: Getting Started with Laravel  
Episodio: 16 — Validating forms  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è introdurre la validazione dei dati inviati da un form.

Nelle lezioni precedenti abbiamo creato un form che salva un progetto:

```text
GET  /projects/create → mostra il form
POST /projects        → salva il progetto
```

Il problema è che, fino a questo punto, il form accetta i dati così come arrivano dal browser.

Questo è pericoloso e fragile.

La lezione introduce quindi il primo controllo fondamentale:

> prima di salvare dati nel database, Laravel deve validarli.

---

## 2. Il problema: dati vuoti dal form

Prima della validazione, se l’utente apre:

```text
/projects/create
```

e clicca “Create project” senza inserire nulla, Laravel prova comunque a eseguire:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

Se `name` è vuoto o nullo, il database può generare errore.

Questo significa che stiamo lasciando al database il compito di scoprire che i dati non vanno bene.

Non è l’approccio corretto.

---

## 3. Perché non affidarsi al database

Il database deve proteggere la consistenza dei dati, ma non deve essere il primo livello di validazione mostrato all’utente.

Problemi dell’approccio “ci penserà il DB”:

- l’utente vede un errore brutto o generico
- l’applicazione arriva troppo tardi al controllo
- la logica di validazione non è esplicita nel codice
- in locale con `APP_DEBUG=true` si vedono dettagli tecnici
- in produzione l’errore sarebbe nascosto, ma l’esperienza utente resterebbe pessima

Regola pratica:

> i dati del browser vanno validati prima di arrivare al database.

---

## 4. Dove validare

Laravel offre diversi modi per validare:

- direttamente nel controller tramite `$request->validate(...)`
- tramite Form Request dedicate
- tramite validator manuali
- tramite regole personalizzate

In questa lezione si usa il modo più semplice:

```php
$request->validate([...]);
```

---

## 5. Validazione moderna: `$request->validate()`

Il docente segnala una differenza rispetto a vecchi corsi Laravel.

In passato si poteva vedere spesso:

```php
$this->validate(...)
```

nei controller.

Ora l’approccio mostrato è:

```php
$request->validate(...)
```

Ha senso perché stiamo validando proprio la request.

---

## 6. Prima validazione minima

Nel metodo `store()` di `ProjectController`, prima di `Project::create()`, aggiungiamo:

```php
$request->validate([
    'name' => ['required'],
]);
```

Esempio:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required'],
    ]);

    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
}
```

Questa regola dice:

> il campo `name` deve essere presente e non vuoto.

---

## 7. Cosa succede se la validazione fallisce

Se il campo `name` non passa la validazione:

1. Laravel ferma l’esecuzione del metodo
2. `Project::create()` non viene eseguito
3. nessun dato viene inserito nel database
4. l’utente viene reindirizzato indietro
5. gli errori di validazione vengono messi a disposizione della sessione

In questa lezione non mostriamo ancora gli errori nella view.

Questo sarà il passo successivo.

---

## 8. Regola `required`

La regola `required` indica che il campo deve essere presente nei dati e non deve essere vuoto.

Esempio:

```php
$request->validate([
    'name' => ['required'],
]);
```

Laravel considera vuoto un campo quando, ad esempio:

- è assente
- è stringa vuota
- è `null`
- è un array vuoto
- è un file senza path valido

Per il nostro form, il caso importante è:

```text
l’utente invia name vuoto
```

---

## 9. Validazione con più regole

Spesso un campo deve rispettare più regole.

Nel nostro caso, `name` deve essere:

- obbligatorio
- non più lungo di una certa lunghezza

Esempio:

```php
$request->validate([
    'name' => ['required', 'max:255'],
]);
```

---

## 10. Regola `max`

La regola `max` limita la dimensione massima di un campo.

Per una stringa:

```php
'max:255'
```

significa:

```text
massimo 255 caratteri
```

Questo è coerente con una colonna database creata così:

```php
$table->string('name');
```

In Laravel, `string()` normalmente crea una colonna `VARCHAR(255)`.

Quindi ha senso validare:

```php
'name' => ['required', 'max:255']
```

prima di salvare.

---

## 11. Perché usare `max:255`

Se il database accetta al massimo 255 caratteri, non vogliamo arrivare al database con una stringa più lunga.

Senza validazione, il database potrebbe:

- troncare il valore
- generare errore
- comportarsi diversamente a seconda del driver

Con la validazione, invece, Laravel blocca la request prima.

Regola pratica:

> la validazione applicativa deve rispettare i vincoli del database.

---

## 12. Sintassi a stringa

Laravel permette di scrivere più regole usando il simbolo pipe:

```php
$request->validate([
    'name' => 'required|max:255',
]);
```

Significa:

```text
required AND max:255
```

È una sintassi compatta e molto diffusa.

---

## 13. Sintassi ad array

La lezione mostra anche la sintassi ad array:

```php
$request->validate([
    'name' => ['required', 'max:255'],
]);
```

Questa forma è spesso preferibile perché:

- è più leggibile quando le regole crescono
- è più facile da modificare
- supporta meglio regole basate su classi
- evita stringhe troppo lunghe

Nel laboratorio usiamo questa forma.

---

## 14. Regole con parametri

Alcune regole sono semplici:

```php
'required'
```

Altre richiedono parametri:

```php
'max:255'
```

La parte dopo i due punti è il parametro della regola.

Esempio:

```text
max:255
```

significa:

```text
massimo 255
```

Alcune regole possono avere più parametri separati da virgole.

---

## 15. Test rapido con `max:10`

Per verificare che la regola funzioni, il docente mostra un test con limite più basso:

```php
$request->validate([
    'name' => ['required', 'max:10'],
]);
```

Se inseriamo un nome troppo lungo, Laravel blocca il salvataggio e torna indietro.

Questo dimostra che la validazione sta funzionando.

Nel codice finale del laboratorio, però, è più coerente usare:

```php
'max:255'
```

per allinearsi alla colonna database.

---

## 16. Codice finale consigliato

`app/Http/Controllers/ProjectController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
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

---

## 17. Cosa non viene ancora mostrato

Dopo questa lezione, la validazione funziona, ma l’utente non vede ancora un messaggio chiaro nel form.

Se il form fallisce:

```text
Laravel torna indietro
```

ma non abbiamo ancora stampato gli errori nella view.

Questo sarà il contenuto naturale della prossima lezione:

```text
Showing validation errors
```

---

## 18. Cosa succede al database

Con la validazione attiva:

- form vuoto → nessun insert
- nome troppo lungo → nessun insert
- nome valido → insert eseguito

Il database non è più il primo punto in cui scopriamo l’errore.

La request viene fermata prima.

---

## 19. Debito tecnico ancora presente

Anche con questa validazione, resta un problema:

```php
'slug' => str($request->name)->slug(),
```

Se due progetti hanno lo stesso nome, producono lo stesso slug.

Esempio:

```text
A New Project → a-new-project
A New Project → a-new-project
```

La colonna `slug` è `unique`, quindi il secondo salvataggio può fallire.

La lezione 16 non risolve ancora questo problema.

Per ora ci limitiamo a validare il campo `name`.

---

## 20. Lesson Learned

### 1. Non fidarsi mai dei dati del browser

Il browser può inviare campi vuoti, mancanti, troppo lunghi o manipolati.

### 2. Il database non deve essere il primo livello di validazione

Il database protegge la consistenza, ma Laravel deve validare prima.

### 3. `$request->validate()` è il modo più semplice per iniziare

Esempio:

```php
$request->validate([
    'name' => ['required', 'max:255'],
]);
```

### 4. Se la validazione fallisce, Laravel torna indietro

Il codice successivo non viene eseguito.

Quindi `Project::create()` non parte.

### 5. `required` impedisce valori vuoti

Esempio:

```php
'name' => ['required']
```

### 6. `max:255` protegge il limite della colonna string

Esempio:

```php
'name' => ['max:255']
```

### 7. La sintassi ad array è più leggibile

Meglio:

```php
'name' => ['required', 'max:255']
```

rispetto a:

```php
'name' => 'required|max:255'
```

soprattutto quando le regole aumentano.

### 8. Gli errori esistono, ma non li stiamo ancora mostrando

La validazione funziona già.

La prossima lezione servirà a mostrarla bene all’utente.

---

## 21. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Controllare il controller:

```bash
sed -n '1,200p' app/Http/Controllers/ProjectController.php
```

Avviare il server:

```bash
php artisan serve
```

Aprire il form:

```text
http://127.0.0.1:8000/projects/create
```

Controllare le route:

```bash
php artisan route:list
```

Controllare gli ultimi progetti in Tinker:

```bash
php artisan tinker
```

Dentro Tinker:

```php
App\Models\Project::latest()->take(5)->get();
exit
```

---

## 22. Stato finale della lezione

Alla fine della lezione sappiamo:

- perché il form non deve arrivare al database con dati non validi
- usare `$request->validate()`
- applicare `required`
- applicare `max:255`
- scrivere le regole come array
- fermare il salvataggio se la validazione fallisce
- proteggere il campo `name` prima di `Project::create()`

Obiettivo raggiunto:

> il form di creazione progetto ora valida il nome prima di salvare nel database.

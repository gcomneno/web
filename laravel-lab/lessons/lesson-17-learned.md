# Getting Started with Laravel — Lezione 17
## Showing validation errors

Data laboratorio: 2026-07-22  
Corso: Getting Started with Laravel  
Episodio: 17 — Showing validation errors  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è mostrare nella pagina del form gli errori prodotti dalla validazione.

Nella lezione 16 abbiamo aggiunto la validazione nel controller:

```php
$request->validate([
    'name' => ['required', 'max:255'],
]);
```

La validazione funziona già:

- se il campo è vuoto, Laravel blocca il salvataggio
- se il campo è troppo lungo, Laravel blocca il salvataggio
- se la validazione fallisce, Laravel reindirizza indietro

Il problema è che l’utente non vede ancora una spiegazione chiara.

Questa lezione risolve proprio questo:

> quando il form è sbagliato, l’utente deve vedere cosa correggere.

---

## 2. Cosa succede quando la validazione fallisce

Quando `$request->validate()` fallisce, Laravel:

1. interrompe il metodo del controller
2. non esegue `Project::create()`
3. reindirizza l’utente alla pagina precedente
4. porta con sé gli errori di validazione
5. porta con sé anche i valori precedentemente inseriti

Questi dati vengono resi disponibili alla view tramite la sessione.

Noi non dobbiamo gestire manualmente tutto il meccanismo.

Laravel ci offre scorciatoie Blade e helper appositi.

---

## 3. Gli errori arrivano dalla sessione

Tecnicamente gli errori di validazione sono disponibili nella sessione dopo il redirect.

In un framework più manuale potremmo dover scrivere codice del tipo:

```php
if (session has errors) {
    // mostra errori
}
```

In Laravel, nella maggior parte dei casi, non serve fare tutto questo.

Blade offre una direttiva molto comoda:

```blade
@error
```

---

## 4. La direttiva `@error`

Per mostrare l’errore di un campo specifico usiamo:

```blade
@error('name')
    <p>{{ $message }}</p>
@enderror
```

Significato:

- controlla se esiste un errore per il campo `name`
- se esiste, esegue il blocco
- dentro il blocco è disponibile `$message`
- `$message` contiene il testo dell’errore

Esempio di messaggio:

```text
The name field is required.
```

---

## 5. Dove mettere `@error`

Nel form di creazione progetto, l’errore va vicino al campo `name`.

Esempio:

```blade
<div>
    <label for="name">Name</label>
    <input id="name" type="text" name="name">

    @error('name')
        <p>{{ $message }}</p>
    @enderror
</div>
```

Così l’utente vede l’errore nel punto giusto.

---

## 6. Messaggi di errore predefiniti

Laravel fornisce già messaggi di errore standard.

Esempi:

```text
The name field is required.
The name field must not be greater than 255 characters.
```

Questi messaggi vengono generati a partire dalle regole di validazione.

Possono essere personalizzati tramite file di lingua o configurazioni dedicate, ma la lezione non approfondisce ancora questa parte.

Per ora usiamo i messaggi predefiniti.

---

## 7. Validazione visibile all’utente

Prima della lezione 17:

```text
utente invia form sbagliato
        ↓
Laravel torna indietro
        ↓
utente non capisce cosa è successo
```

Dopo la lezione 17:

```text
utente invia form sbagliato
        ↓
Laravel torna indietro
        ↓
Blade mostra il messaggio di errore vicino al campo
```

Questo è un miglioramento importante dell’esperienza utente.

---

## 8. Il secondo problema: perdere il valore inserito

La lezione mostra un secondo problema.

Supponiamo di avere una regola molto restrittiva per test:

```php
'name' => ['required', 'max:2']
```

Se l’utente scrive:

```text
Alex's project
```

Laravel mostra l’errore perché il valore supera 2 caratteri.

Ma senza accorgimenti aggiuntivi, il campo input torna vuoto.

Questo è fastidioso.

Per un campo piccolo come `name` è solo scomodo.

Per un form più lungo, come registrazione o scrittura di un post, sarebbe pessimo.

---

## 9. `old()`

Laravel conserva i vecchi valori della request dopo un errore di validazione.

Per recuperarli nella view usiamo:

```blade
old('name')
```

Esempio:

```blade
<input id="name" type="text" name="name" value="{{ old('name') }}">
```

Quando la validazione fallisce:

- Laravel torna indietro
- il messaggio di errore viene mostrato
- il campo resta compilato con il valore precedente

---

## 10. Perché `old()` è importante

Senza `old()`:

```text
utente compila form
        ↓
errore validazione
        ↓
utente perde il testo scritto
```

Con `old()`:

```text
utente compila form
        ↓
errore validazione
        ↓
utente vede errore
        ↓
utente corregge il valore già presente
```

Regola pratica:

> se un input può fallire validazione, quasi sempre deve usare `old()`.

---

## 11. View finale consigliata

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

## 12. Controller dopo la lezione

Il controller resta sostanzialmente quello della lezione 16.

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

    return back();
}
```

La lezione usa temporaneamente `max:2` o `max:10` per dimostrare l’errore, ma nel codice finale conviene mantenere:

```php
'max:255'
```

perché è coerente con la colonna database `string`.

---

## 13. Test manuali

### Campo vuoto

Apri:

```text
http://127.0.0.1:8000/projects/create
```

Invia il form senza compilare `name`.

Risultato atteso:

```text
The name field is required.
```

Nessun progetto viene creato.

---

### Campo troppo lungo

Per test temporaneo, nel controller puoi mettere:

```php
'name' => ['required', 'max:2'],
```

Poi invia:

```text
Alex's project
```

Risultato atteso:

```text
The name field must not be greater than 2 characters.
```

Il campo deve conservare il valore inserito grazie a:

```blade
value="{{ old('name') }}"
```

Dopo il test, rimetti:

```php
'name' => ['required', 'max:255'],
```

---

## 14. Differenza tra validare e mostrare errori

La lezione 16 ha introdotto la validazione.

La lezione 17 introduce la visualizzazione degli errori.

Sono due passaggi distinti:

```text
Controller → valida i dati
View       → mostra gli errori
```

Se manca la validazione, gli errori non esistono.

Se manca la visualizzazione, gli errori esistono ma l’utente non li vede.

---

## 15. Lesson Learned

### 1. Laravel reindirizza indietro con gli errori

Quando `$request->validate()` fallisce, Laravel torna alla pagina precedente portando con sé gli errori.

---

### 2. `@error` mostra l’errore di un campo

Esempio:

```blade
@error('name')
    <p>{{ $message }}</p>
@enderror
```

---

### 3. `$message` contiene il testo dell’errore

Dentro `@error`, Laravel rende disponibile la variabile `$message`.

---

### 4. Gli errori vanno mostrati vicino al campo

Per un form usabile, l’errore deve stare dove l’utente può correggere il valore.

---

### 5. `old()` recupera il valore precedente

Esempio:

```blade
value="{{ old('name') }}"
```

---

### 6. Non bisogna far perdere dati all’utente

Se un form fallisce validazione, l’utente deve poter correggere ciò che ha scritto, non ricominciare da zero.

---

### 7. I messaggi standard possono essere personalizzati

Laravel fornisce messaggi già pronti, ma in futuro possiamo modificarli.

---

## 16. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Aprire la view del form:

```bash
sed -n '1,200p' resources/views/projects/create.blade.php
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

Controllare che non siano stati creati record indesiderati:

```bash
php artisan tinker
```

Dentro Tinker:

```php
App\Models\Project::latest()->take(5)->get();
exit
```

---

## 17. Stato finale della lezione

Alla fine della lezione sappiamo:

- mostrare errori di validazione nella view
- usare `@error('name')`
- usare `$message`
- recuperare il vecchio valore con `old('name')`
- evitare che l’utente perda ciò che ha scritto
- distinguere tra validazione nel controller e visualizzazione errori nella view

Obiettivo raggiunto:

> il form di creazione progetto ora spiega all’utente cosa non va e conserva il valore inserito.

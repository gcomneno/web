# Getting Started with Laravel — Lezione 05
## Blade and view data

Data laboratorio: 2026-05-26  
Corso: Getting Started with Laravel  
Episodio: 05 — Blade and view data  
Durata video: circa 5 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è:

> passare dati da una rotta Laravel a una view Blade e mostrarli nella pagina.

Nella lezione precedente abbiamo creato una view e l’abbiamo restituita da una rotta.

Ora facciamo un passo avanti:

- la route prepara dei dati
- la view riceve quei dati
- Blade li stampa nell’HTML
- Blade può anche decidere se mostrarli oppure no

Questo è il primo passaggio verso pagine dinamiche.

---

## 2. Punto di partenza

Nella lezione 04 la homepage era servita tramite una view.

Esempio:

```php
Route::get('/', function () {
    return view('pages.home');
})->name('home');
```

La view era un file Blade, per esempio:

```text
resources/views/pages/home.blade.php
```

Dentro potevamo scrivere HTML normale.

Ma fino a questo punto la pagina era statica.

Esempio:

```html
<h1>Home</h1>
```

Il testo era scritto direttamente nella view.

---

## 3. Perché passare dati alle view

Una pagina statica va bene per un sito molto semplice.

Ma in una vera applicazione vogliamo mostrare dati variabili.

Esempi futuri:

- nome utente
- lista di articoli
- prodotti
- messaggi
- dati presi dal database
- risultati di una ricerca

Il docente anticipa proprio questa idea:

> più avanti prenderemo dati dal database, li passeremo alla view e li mostreremo nella pagina.

In questa lezione non usiamo ancora il database.  
Passiamo solo dati semplici dalla route alla view.

---

## 4. Passare dati con `view()`

L’helper `view()` può ricevere un secondo argomento.

Il primo argomento è il nome della view.

Il secondo argomento è un array di dati.

Esempio:

```php
Route::get('/', function () {
    return view('pages.home', [
        'greeting' => 'Hello',
    ]);
})->name('home');
```

Qui stiamo dicendo:

> carica la view `pages.home` e rendi disponibile dentro la view una variabile chiamata `$greeting` con valore `Hello`.

---

## 5. L’array passato alla view

Questo pezzo:

```php
[
    'greeting' => 'Hello',
]
```

è un array associativo PHP.

La chiave:

```php
'greeting'
```

diventa una variabile disponibile nella view:

```php
$greeting
```

Quindi:

```php
'greeting' => 'Hello'
```

nella view diventa:

```blade
{{ $greeting }}
```

Regola mentale:

```text
chiave array PHP → variabile Blade
```

Esempio:

```php
[
    'name' => 'Giancarlo',
    'role' => 'Developer',
]
```

nella view diventa:

```blade
{{ $name }}
{{ $role }}
```

---

## 6. Stampare dati in Blade

Blade stampa valori usando doppie parentesi graffe:

```blade
{{ $greeting }}
```

Quindi, se nella route abbiamo:

```php
return view('pages.home', [
    'greeting' => 'Hello',
]);
```

nella view possiamo scrivere:

```blade
<h1>{{ $greeting }}</h1>
```

Risultato nel browser:

```text
Hello
```

---

## 7. Perché si usano `{{ ... }}`

Le doppie parentesi graffe sono la sintassi Blade per stampare valori.

Blade converte quel codice in PHP valido dietro le quinte.

Quindi:

```blade
{{ $greeting }}
```

significa concettualmente:

> stampa il contenuto della variabile `$greeting` dentro l’HTML.

Più avanti vedremo che Blade gestisce anche aspetti di sicurezza come l’escaping dell’output.

Per ora basta ricordare:

> `{{ ... }}` serve per mostrare un valore nella view.

---

## 8. Aggiungere un secondo dato: `showGreeting`

Il docente aggiunge un altro valore per controllare se mostrare o no il saluto.

Esempio:

```php
Route::get('/', function () {
    return view('pages.home', [
        'greeting' => 'Hello',
        'showGreeting' => true,
    ]);
})->name('home');
```

Ora nella view abbiamo due variabili:

```blade
$greeting
$showGreeting
```

`$greeting` contiene il testo.

`$showGreeting` dice se mostrarlo oppure no.

---

## 9. Le direttive Blade

Blade non serve solo a stampare variabili.

Permette anche di usare strutture di controllo come:

- `if`
- `else`
- `foreach`
- `for`
- `while`

La sintassi Blade usa il simbolo `@`.

Esempio:

```blade
@if ($showGreeting)
    <h1>{{ $greeting }}</h1>
@endif
```

Questo significa:

> se `$showGreeting` è vero, mostra il saluto.

---

## 10. `@if` e `@endif`

In PHP normale potremmo scrivere:

```php
if ($showGreeting) {
    echo $greeting;
}
```

In Blade scriviamo:

```blade
@if ($showGreeting)
    {{ $greeting }}
@endif
```

Blade richiede una direttiva di apertura:

```blade
@if (...)
```

e una direttiva di chiusura:

```blade
@endif
```

Questa sintassi è pensata per essere leggibile dentro file HTML.

---

## 11. Esempio completo della view

Esempio di `resources/views/pages/home.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Project</title>
</head>
<body>
    @if ($showGreeting)
        <h1>{{ $greeting }}</h1>
    @endif
</body>
</html>
```

Se `$showGreeting` è `true`, vedremo:

```text
Hello
```

Se `$showGreeting` è `false`, il saluto non verrà mostrato.

---

## 12. Blade è molto più ampio

Il docente dice chiaramente che Blade ha molte funzionalità.

In questa lezione vediamo solo una piccola parte.

Blade può fare anche:

- condizioni
- cicli
- layout
- componenti
- include
- gestione dati
- form
- escaping
- classi condizionali

Ma per ora bastano due idee:

1. stampare dati con `{{ ... }}`
2. usare strutture di controllo con `@...` e `@end...`

Il docente dice che, capite queste due cose, si ha già una buona base per leggere la documentazione e scoprire il resto.

---

## 13. Pratica fatta sul nostro progetto

File coinvolti:

```text
routes/web.php
resources/views/pages/home.blade.php
```

### Rotta aggiornata

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home', [
        'greeting' => 'Hello',
        'showGreeting' => true,
    ]);
})->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');
```

### View aggiornata

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Project</title>
</head>
<body>
    @if ($showGreeting)
        <h1>{{ $greeting }}</h1>
    @endif
</body>
</html>
```

---

## 14. Esperimento utile

Per capire bene il comportamento, possiamo cambiare:

```php
'showGreeting' => true,
```

in:

```php
'showGreeting' => false,
```

Con `true`, il saluto appare.

Con `false`, il saluto sparisce.

Questo dimostra che la view non è più statica: reagisce ai dati ricevuti dalla route.

---

## 15. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare già:

- database
- model
- controller
- layout Blade
- componenti Blade
- form
- validazione
- escaping in dettaglio
- cicli `@foreach`
- passaggio di dati complessi

Il docente introduce solo il minimo indispensabile:

> dati dalla route alla view, stampa con `{{ }}` e condizione con `@if`.

---

## 16. Lesson Learned

### 1. Le view possono ricevere dati dalla route

`view()` accetta un secondo argomento:

```php
return view('pages.home', [
    'greeting' => 'Hello',
]);
```

---

### 2. Le chiavi dell’array diventano variabili nella view

Questa chiave:

```php
'greeting' => 'Hello'
```

diventa:

```blade
$greeting
```

dentro il file Blade.

---

### 3. Blade stampa valori con doppie parentesi graffe

Esempio:

```blade
{{ $greeting }}
```

---

### 4. Le view diventano dinamiche quando ricevono dati

Prima la view conteneva solo HTML statico.

Ora può mostrare valori decisi dalla route.

---

### 5. Blade usa direttive con `@`

Esempio:

```blade
@if ($showGreeting)
    ...
@endif
```

---

### 6. `@if` permette di mostrare contenuto in modo condizionale

Se la condizione è vera, il contenuto viene mostrato.

Se è falsa, non viene mostrato.

---

### 7. Le direttive Blade hanno apertura e chiusura

Esempio:

```blade
@if (...)
@endif
```

Questo rende il codice leggibile dentro l’HTML.

---

### 8. Questa è la base per mostrare dati dal database

Più avanti i dati non saranno scritti a mano nella route.

Arriveranno da database, model o servizi.

Ma il meccanismo sarà simile:

```text
dati → view → HTML
```

---

## 17. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Leggere la route:

```bash
cat routes/web.php
```

Leggere la view:

```bash
cat resources/views/pages/home.blade.php
```

Avviare server:

```bash
php artisan serve
```

Aprire homepage:

```text
http://127.0.0.1:8000/
```

Testare da terminale:

```bash
curl -i http://127.0.0.1:8000/
```

Mostrare le rotte:

```bash
php artisan route:list
```

---

## 18. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- come passare dati da una route a una view
- come usare il secondo argomento di `view()`
- come una chiave dell’array diventa variabile Blade
- come stampare una variabile con `{{ ... }}`
- come usare una condizione Blade con `@if`
- come chiudere una condizione Blade con `@endif`
- perché questo rende la pagina dinamica

Obiettivo raggiunto:

> abbiamo passato dati alla prima view Blade e li abbiamo mostrati condizionalmente.

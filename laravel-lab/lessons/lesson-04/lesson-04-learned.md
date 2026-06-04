# Getting Started with Laravel — Lezione 04

## View basics

Data laboratorio: 2026-05-20  
Corso: Getting Started with Laravel  
Episodio: 04 — View basics  
Durata video: circa 8 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è:

> sostituire una risposta testuale semplice con una view Blade.

Nella lezione precedente abbiamo creato rotte che rispondevano con testo semplice usando `response()`.

Esempio:

```php
Route::get('/', function () {
    return response('home', 200);
});
```

Questa tecnica va bene per esempi piccoli o API, ma non è il modo normale di costruire pagine HTML in Laravel.

Per costruire pagine web vere usiamo le **view**.

---

## 2. Perché non scrivere HTML direttamente nella rotta

In una vera applicazione Laravel, non vogliamo costruire l’HTML direttamente dentro la route.
Tecnicamente potremmo fare qualcosa del genere:

```php
Route::get('/', function () {
    return response('<h1>Home</h1>');
});
```

Ma è una cattiva direzione.
Motivi:

- la route diventerebbe disordinata
- mischieremmo logica e presentazione
- l’HTML diventerebbe difficile da mantenere
- non potremmo riusare bene pezzi di layout
- il file `routes/web.php` diventerebbe presto ingestibile

La soluzione Laravel è:

> la rotta decide cosa restituire, ma l’HTML vive in una view separata.

---

## 3. Dove stanno le view

Le view Laravel stanno nella cartella:

```text
resources/views
```

In un progetto nuovo Laravel contiene già una view iniziale:

```text
resources/views/welcome.blade.php
```

---

## 4. Cos’è Blade

Blade è il motore di template di Laravel.

I file view Laravel usano spesso estensione:

```text
.blade.php
```

Per ora possiamo pensare a un file Blade come a:

> un file HTML con superpoteri Laravel.

Viene trattato sostanzialmente come un file HTML.
Quindi dentro una view possiamo scrivere HTML normale:

```html
<h1>Home</h1>
```

Più avanti Blade permetterà anche:

- stampare variabili
- usare condizioni
- usare cicli
- creare layout
- creare componenti
- includere altre view

Ma questa lezione resta sul concetto base:

> creare una view e restituirla da una rotta.

---

## 5. Creare una view manualmente

Una view si potrebbe creare a mano aggiungendo un file dentro:

```text
resources/views
```

Per esempio:

```text
resources/views/home.blade.php
```

Questo funziona.

---

## 6. Creare una view con Artisan

Laravel offre un comando Artisan per creare view.

Comando:

```bash
php artisan make:view home
```

Questo comando crea:

```text
resources/views/home.blade.php
```

Nel video, appena il comando viene eseguito, il file appare automaticamente nella cartella `resources/views`.

Artisan genera un contenuto iniziale molto semplice, che poi possiamo modificare.

---

## 7. Scrivere HTML dentro la view

Dopo aver creato:

```text
resources/views/home.blade.php
```

possiamo modificarla.

Esempio minimale:

```html
<h1>Home</h1>
```

Oppure una struttura HTML più completa:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Project</title>
</head>
<body>
    <h1>Home</h1>
</body>
</html>
```

Per ora non serve complicare.

L’idea importante è:

> l’HTML della pagina vive nella view, non nella route.

---

## 8. Restituire una view da una rotta

Per restituire una view da una rotta si usa l’helper:

```php
view()
```

Esempio:

```php
Route::get('/', function () {
    return view('home');
});
```

Questo dice a Laravel:

> cerca una view chiamata `home` dentro `resources/views`.

Laravel traduce:

```php
view('home')
```

in:

```text
resources/views/home.blade.php
```

---

## 9. Non si scrive `.blade.php` dentro `view()`

Questa è una regola importante.

Non dobbiamo scrivere:

```php
return view('home.blade.php');
```

Si scrive solo:

```php
return view('home');
```

Perché Laravel sa già che le view Blade finiscono con:

```text
.blade.php
```

Quindi il nome logico della view è:

```text
home
```

Il percorso fisico è:

```text
resources/views/home.blade.php
```

---

## 10. `view()` è un helper Laravel

Come `response()`, anche `view()` è un helper.

Sembra una funzione semplice:

```php
view('home')
```

ma dietro Laravel usa il sistema delle view, il container e la view factory per trovare e renderizzare il file corretto.
Non serve sapere tutti i dettagli interni ora.

Serve però capire che:

> `view('home')` non legge un file a caso: chiede a Laravel di renderizzare una view registrata nel posto giusto.

---

## 11. Sub-directory nelle view

Si possono anche organizzare le view in sottocartelle.

Per esempio, invece di:

```text
resources/views/home.blade.php
```

possiamo avere:

```text
resources/views/pages/home.blade.php
```

Questo è utile quando l’app cresce.

Esempi:

```text
resources/views/pages/home.blade.php
resources/views/pages/about.blade.php
resources/views/posts/index.blade.php
resources/views/admin/dashboard.blade.php
```

---

## 12. Creare view in sottocartelle con Artisan

Per creare una view dentro una sottocartella, Laravel usa la **dot notation**.

Comando:

```bash
php artisan make:view pages.home
```

Questo crea:

```text
resources/views/pages/home.blade.php
```

Attenzione:

non si scrive:

```bash
php artisan make:view pages/home
```

Nel video il docente sottolinea che, nel contesto Laravel, le sottocartelle vengono spesso indicate con il punto.

---

## 13. Usare view in sottocartelle

Se la view è:

```text
resources/views/pages/home.blade.php
```

la richiamiamo così:

```php
return view('pages.home');
```

Non così:

```php
return view('pages/home');
```

Quindi:

| File fisico | Nome view Laravel |
|-------------|-------------------|
| `resources/views/home.blade.php` | `home` |
| `resources/views/pages/home.blade.php` | `pages.home` |
| `resources/views/admin/dashboard.blade.php` | `admin.dashboard` |

Questa convenzione ritornerà spesso in Laravel.

---

## 14. Dot notation

La dot notation è una convenzione molto usata in Laravel.

In questa lezione la vediamo per le view:

```php
view('pages.home')
```

Significa:

```text
resources/views/pages/home.blade.php
```

L’idea è:

> il punto rappresenta una discesa dentro una sottocartella.

Questa notazione rende i nomi più leggibili e indipendenti dal separatore del filesystem.

---

## 15. Pratica fatta sul nostro progetto

File principali coinvolti:

```text
routes/web.php
resources/views/home.blade.php
resources/views/pages/home.blade.php
```

### Creare una view semplice

```bash
php artisan make:view home
```

Questo crea:

```text
resources/views/home.blade.php
```

Contenuto possibile:

```html
<h1>Home</h1>
```

Rotta:

```php
Route::get('/', function () {
    return view('home');
})->name('home');
```

### Creare una view in sottocartella

```bash
php artisan make:view pages.home
```

Questo crea:

```text
resources/views/pages/home.blade.php
```

Rotta:

```php
Route::get('/', function () {
    return view('pages.home');
})->name('home');
```

---

## 16. Versione consigliata per il nostro progetto

Per restare ordinati fin da subito, possiamo usare una sottocartella `pages`.

Struttura:

```text
resources/views/pages/home.blade.php
```

Rotta:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');
```

Contenuto di `resources/views/pages/home.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Project</title>
</head>
<body>
    <h1>Home</h1>
</body>
</html>
```

La pagina `/about` può restare testuale per ora, perché la lezione si concentra principalmente sulla prima view.

---

## 18. Lesson Learned

### 1. Le pagine HTML non si scrivono direttamente nelle rotte

Una rotta deve rimanere leggibile.
L’HTML va in una view.

---

### 2. Le view stanno in `resources/views`

Laravel cerca le view dentro:

```text
resources/views
```

---

### 3. I file Blade finiscono con `.blade.php`

Esempio:

```text
home.blade.php
```

Blade è il motore di template di Laravel.

---

### 4. Una view può essere creata a mano o con Artisan

Comando:

```bash
php artisan make:view home
```

---

### 5. `view('home')` carica `resources/views/home.blade.php`

Non serve scrivere `.blade.php`.

Corretto:

```php
return view('home');
```

Sbagliato:

```php
return view('home.blade.php');
```

---

### 6. `view()` è un helper Laravel

Come `response()`, sembra una funzione semplice, ma dietro usa servizi Laravel già configurati.

---

### 7. Le view possono stare in sottocartelle

Esempio:

```text
resources/views/pages/home.blade.php
```

---

### 8. Laravel usa la dot notation per le sottocartelle

Per richiamare:

```text
resources/views/pages/home.blade.php
```

si usa:

```php
view('pages.home')
```

---

### 9. `php artisan make:view pages.home` crea una view annidata

Il punto indica la sottocartella.

Comando:

```bash
php artisan make:view pages.home
```

Risultato:

```text
resources/views/pages/home.blade.php
```

---

### 10. Le view sono il primo passo verso pagine vere

Con `response()` restituiamo testo.

Con `view()` iniziamo a costruire vere pagine HTML.

---

## 19. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Creare view semplice:

```bash
php artisan make:view home
```

Creare view in sottocartella:

```bash
php artisan make:view pages.home
```

Leggere le view:

```bash
find resources/views -maxdepth 3 -type f | sort
```

Leggere le rotte:

```bash
cat routes/web.php
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

---

## 20. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- perché non conviene generare HTML direttamente nelle rotte
- dove stanno le view Laravel
- cos’è un file `.blade.php`
- come creare una view con Artisan
- come restituire una view con `view()`
- perché non si scrive `.blade.php` nel nome passato a `view()`
- come usare sottocartelle nelle view
- come funziona la dot notation per `pages.home`

Obiettivo raggiunto:

> abbiamo trasformato una rotta testuale in una rotta che restituisce una view Blade.

# Getting Started with Laravel — Lezione 06
## Env configuration

Data laboratorio: 2026-05-26  
Corso: Getting Started with Laravel  
Episodio: 06 — Env configuration  
Durata video: circa 7 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è:

> capire il rapporto tra `.env`, file `config/*.php` e codice applicativo Laravel.

Nelle lezioni precedenti abbiamo visto le view e il passaggio di dati alla view.

Ora torniamo sulla configurazione, perché in Laravel valori come nome app, ambiente, debug, URL e database sono usati continuamente.

Il punto centrale è:

```text
.env
  ↓
config/*.php
  ↓
codice PHP / Blade
```

La regola pratica è:

> nel codice dell’applicazione è meglio leggere da `config()`, non direttamente da `env()`.

---

## 2. `.env` e `config/`: due livelli diversi

Laravel usa due livelli distinti:

- `.env`
- `config/*.php`

Il file `.env` contiene valori specifici dell’ambiente.

La cartella `config/` contiene file PHP di configurazione Laravel.

Esempio:

```text
.env
config/app.php
```

Dentro `.env` possiamo avere:

```env
APP_NAME="My Project"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
```

Dentro `config/app.php` troviamo valori che spesso leggono da `.env`:

```php
'name' => env('APP_NAME', 'Laravel'),
```

Questo significa:

> prendi `APP_NAME` da `.env`; se non esiste, usa `Laravel`.

---

## 3. Leggere un valore di configurazione con `config()`

Laravel fornisce l’helper:

```php
config()
```

Per leggere il nome dell’applicazione:

```php
config('app.name')
```

Questo legge il valore:

```php
'name'
```

dal file:

```text
config/app.php
```

La notazione:

```text
app.name
```

significa:

```text
file config/app.php → chiave name
```

---

## 4. Dot notation nella configurazione

Laravel usa spesso la dot notation.

Nel caso di `config()`:

```php
config('app.name')
```

vuol dire:

```text
config/app.php
└── name
```

Altro esempio:

```php
config('database.default')
```

vuol dire:

```text
config/database.php
└── default
```

Questa convenzione rende comodo accedere a configurazioni annidate.

---

## 5. Debug rapido con `dd(config(...))`

Il docente mostra un controllo rapido dentro `routes/web.php`.

Esempio:

```php
Route::get('/', function () {
    dd(config('app.name'));
});
```

Questo stampa il valore della configurazione e ferma l’esecuzione.

Se in `.env` abbiamo:

```env
APP_NAME="My Project"
```

allora:

```php
config('app.name')
```

restituisce:

```text
My Project
```

Nota:

`dd()` serve solo per debug temporaneo.  
Non deve restare nel codice finale.

---

## 6. Usare `config()` dentro una view Blade

Il docente mostra che gli helper Laravel possono essere usati anche dentro Blade.

Esempio nel file:

```text
resources/views/pages/home.blade.php
```

Possiamo scrivere:

```blade
<title>{{ config('app.name') }}</title>
```

Questo imposta il titolo HTML usando il valore della configurazione.

La cosa importante è che non dobbiamo per forza passare sempre questo valore dalla route alla view.

Potremmo fare così:

```php
return view('pages.home', [
    'title' => config('app.name'),
]);
```

e poi nella view:

```blade
<title>{{ $title }}</title>
```

Ma per un valore globale come il nome dell’applicazione è più comodo usare direttamente:

```blade
<title>{{ config('app.name') }}</title>
```

---

## 7. Perché non passare sempre tutto dalla route

Se un valore serve in tantissime pagine, passarlo manualmente da ogni route diventa ripetitivo.

Esempio brutto:

```php
return view('pages.home', [
    'title' => config('app.name'),
]);

return view('pages.about', [
    'title' => config('app.name'),
]);

return view('pages.contact', [
    'title' => config('app.name'),
]);
```

Per valori globali come il nome dell’applicazione, è più semplice usare `config()` direttamente nella view.

Esempio:

```blade
<title>{{ config('app.name') }}</title>
```

---

## 8. Helper e facade dentro Blade

Il docente spiega che dentro i file Blade possiamo usare anche helper e facade Laravel.

Esempi di helper:

```blade
{{ config('app.name') }}
{{ route('home') }}
```

Tecnicamente una view Blade viene trasformata in PHP, quindi può usare funzionalità Laravel.

Regola pratica:

> possiamo usare helper dentro Blade, ma senza trasformare la view in un posto pieno di logica applicativa.

La view deve restare soprattutto presentazione.

---

## 9. Valori importanti in `.env`

Il docente cita alcuni valori `.env` usati spesso.

### `APP_NAME`

Nome dell’applicazione.

Esempio:

```env
APP_NAME="My Project"
```

Di solito viene letto tramite:

```php
config('app.name')
```

### `APP_URL`

URL base dell’applicazione.

Esempio locale:

```env
APP_URL=http://localhost
```

In produzione potrebbe diventare:

```env
APP_URL=https://example.com
```

### `APP_DEBUG`

Controlla se Laravel mostra errori dettagliati.

In locale:

```env
APP_DEBUG=true
```

In produzione:

```env
APP_DEBUG=false
```

Regola importante:

> in produzione `APP_DEBUG` deve essere `false`.

### `APP_ENV`

Indica l’ambiente.

Esempi:

```env
APP_ENV=local
APP_ENV=production
APP_ENV=staging
```

### Configurazione database

Esempi:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

oppure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_project
DB_USERNAME=root
DB_PASSWORD=
```

Questi valori cambiano spesso tra locale, staging e produzione.

---

## 10. Quando mettere un valore in `.env`

Il docente dà una regola pratica molto utile.

Metti un valore in `.env` quando:

- cambia tra ambienti diversi
- è privato o sensibile
- riguarda credenziali, password, token, chiavi
- riguarda configurazioni di deploy
- riguarda servizi esterni
- non deve essere fissato direttamente nel codice

Esempi:

- nome app se cambia tra ambienti
- database
- mail server
- API keys
- debug
- URL produzione

---

## 11. Quando NON serve `.env`

Non ogni valore deve stare in `.env`.

Se un valore:

- non è sensibile
- non cambia tra ambienti
- è una scelta interna stabile dell’applicazione

può stare direttamente in un file di configurazione o nel codice appropriato.

Esempio citato dal docente:

```php
'cipher' => 'AES-256-CBC',
```

Un valore tecnico stabile come questo non deve necessariamente essere una variabile `.env`.

---

## 12. Fallback dei valori `env()`

Nei file config troviamo spesso:

```php
env('APP_NAME', 'Laravel')
```

Il secondo argomento è il fallback.

Significa:

> se `APP_NAME` non esiste in `.env`, usa `Laravel`.

Altro esempio generico:

```php
env('SOME_VALUE', 'default')
```

Se `SOME_VALUE` non è definito, Laravel usa:

```text
default
```

Questo rende la configurazione più robusta.

---

## 13. Aggiungere nuove variabili `.env`

Il docente spiega che può capitare di trovare config che leggono valori non ancora presenti nel `.env`.

In quel caso possiamo aggiungere noi la variabile al file `.env`.

Esempio concettuale:

```php
'example' => env('APP_EXAMPLE', 'default-value'),
```

Possiamo aggiungere in `.env`:

```env
APP_EXAMPLE=custom-value
```

Da quel momento:

```php
config('app.example')
```

potrà usare quel valore, se la config è stata definita.

Nota importante:

> aggiungere una variabile a `.env` non basta da solo se nessun file config la legge.

Di solito il flusso corretto è:

```text
.env definisce il valore
config/*.php lo espone
codice applicativo legge config()
```

---

## 14. Perché evitare `env()` direttamente nel codice

Il docente chiude con una regola molto importante:

> evitare di usare `env()` direttamente nel codice applicativo o nelle view.

Tecnicamente funzionerebbe:

```blade
{{ env('APP_NAME') }}
```

Ma è meglio usare:

```blade
{{ config('app.name') }}
```

### Perché è meglio `config()`

Usare `config()` è meglio perché:

- centralizza la configurazione
- evita di spargere nomi di variabili `.env` ovunque
- rende il codice più chiaro
- semplifica cambi futuri
- si integra meglio con la cache della configurazione Laravel
- mantiene `.env` come livello basso, non come API usata ovunque

Se un giorno cambia il nome della variabile `.env`, aggiorniamo il file config, non cento punti diversi del codice.

---

## 15. Gerarchia mentale corretta

La gerarchia corretta è:

```text
.env
  contiene valori specifici dell’ambiente

config/*.php
  legge .env e organizza la configurazione Laravel

codice PHP / Blade
  legge da config()
```

Quindi:

```blade
{{ config('app.name') }}
```

è preferibile a:

```blade
{{ env('APP_NAME') }}
```

---

## 16. Pratica fatta sul nostro progetto

File coinvolti:

```text
.env
config/app.php
resources/views/pages/home.blade.php
routes/web.php
```

### Controllare il valore in config

Debug temporaneo:

```php
Route::get('/', function () {
    dd(config('app.name'));
});
```

Questo mostra il nome dell’applicazione.

Poi il `dd()` va rimosso.

### Usare `config()` nella view

Nel file:

```text
resources/views/pages/home.blade.php
```

possiamo usare:

```blade
<title>{{ config('app.name') }}</title>
```

Esempio completo:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
</head>
<body>
    @if ($showGreeting)
        <h1>{{ $greeting }}</h1>
    @endif
</body>
</html>
```

### Route pulita

La route continua a passare solo i dati specifici della pagina:

```php
Route::get('/', function () {
    return view('pages.home', [
        'greeting' => 'Hello',
        'showGreeting' => true,
    ]);
})->name('home');
```

Il nome dell’app, essendo un valore globale, viene letto direttamente nella view tramite `config()`.

---

## 17. Sicurezza Git: `.env` non si committa

Questa lezione tocca `.env`, quindi va ribadito:

> `.env` non deve finire nel repository.

Nel repository pubblico deve esserci solo:

```text
.env.example
```

Il file reale:

```text
.env
```

deve restare locale.

Controllo utile:

```bash
git ls-files | grep -E '(^|/)\.env$' || true
```

Se stampa qualcosa, c’è un problema.

---

## 18. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare già:

- configurazione database completa
- deploy in produzione
- config cache in dettaglio
- mail configuration completa
- gestione segreti in produzione
- Laravel Cloud / Forge
- service provider avanzati
- scrittura di file config personalizzati

Il focus era:

> capire come leggere valori di configurazione e perché usare `config()` al posto di `env()` nel codice.

---

## 19. Lesson Learned

### 1. `.env` contiene valori specifici dell’ambiente

Locale, staging e produzione possono avere valori diversi.

---

### 2. `config/*.php` organizza la configurazione Laravel

I file in `config/` leggono spesso da `.env`.

---

### 3. Il codice dovrebbe leggere da `config()`

Esempio:

```php
config('app.name')
```

meglio di:

```php
env('APP_NAME')
```

---

### 4. La dot notation identifica file e chiavi config

```php
config('app.name')
```

vuol dire:

```text
config/app.php → name
```

---

### 5. Blade può usare helper Laravel

Dentro Blade possiamo scrivere:

```blade
{{ config('app.name') }}
```

---

### 6. I valori globali non vanno passati manualmente a ogni view

Per il titolo dell’app è più comodo usare `config()` nella view.

---

### 7. `env()` nei file config può avere fallback

Esempio:

```php
env('APP_NAME', 'Laravel')
```

Se `APP_NAME` manca, usa `Laravel`.

---

### 8. Non tutto deve stare in `.env`

Solo valori che cambiano tra ambienti o sono sensibili.

---

### 9. `.env` non va committato

Il file `.env` può contenere segreti.

Va tenuto locale.

---

### 10. La gerarchia corretta è `.env → config → codice`

Regola da ricordare:

```text
.env sotto
config in mezzo
codice sopra
```

Il codice legge da `config()`.

---

## 20. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Leggere variabili principali `.env` senza stampare tutto il file:

```bash
grep -E '^(APP_NAME|APP_ENV|APP_DEBUG|APP_URL|DB_CONNECTION|DB_DATABASE)=' .env
```

Leggere configurazione app:

```bash
sed -n '1,120p' config/app.php
```

Cercare `APP_NAME` nella config:

```bash
grep -n "APP_NAME\|name" config/app.php
```

Controllare uso di `config()` nella view:

```bash
grep -n "config('app.name')" resources/views/pages/home.blade.php
```

Controllare che `.env` non sia tracciato da Git:

```bash
git ls-files | grep -E '(^|/)\.env$' || true
```

---

## 21. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- cosa contiene `.env`
- che rapporto c’è tra `.env` e `config/*.php`
- come leggere configurazioni con `config()`
- come usare `config()` dentro Blade
- perché evitare `env()` direttamente nel codice applicativo
- quando mettere un valore in `.env`
- quando non serve mettere un valore in `.env`
- perché `.env` non va su GitHub

Obiettivo raggiunto:

> abbiamo capito come Laravel gestisce configurazioni e valori d’ambiente.

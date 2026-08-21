# Getting Started with Laravel — Lezione 23
## JavaScript and CSS with Vite

Data laboratorio: 2026-08-21  
Corso: Getting Started with Laravel  
Episodio: 23 — JavaScript and CSS with Vite  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è introdurre il modo standard con cui Laravel include JavaScript e CSS in una pagina Blade.

Laravel usa Vite come asset builder.

In questa lezione vediamo:

- dove stanno i file CSS e JavaScript
- come Laravel configura Vite
- come installare le dipendenze npm
- come avviare Vite in sviluppo
- come includere CSS e JS in una view Blade con `@vite`
- come verificare che Tailwind e JavaScript funzionino

---

## 2. Punto di partenza

Finora le view sono volutamente molto semplici.

Esempio:

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

Questa struttura funziona per imparare, ma non è sostenibile.

Se ripetiamo tutto l’HTML in ogni pagina, dovremo duplicare:

- `<html>`
- `<head>`
- `<title>`
- inclusione CSS
- inclusione JavaScript
- struttura comune della pagina

Il corso anticipa che la prossima lezione parlerà di Blade components per risolvere questo problema.

---

## 3. File frontend in Laravel

Nel progetto Laravel sono già presenti file frontend dentro:

```text
resources/
```

In particolare:

```text
resources/css/app.css
resources/js/app.js
resources/js/bootstrap.js
```

Significato:

- `resources/css/app.css`: CSS principale dell’applicazione
- `resources/js/app.js`: JavaScript principale dell’applicazione
- `resources/js/bootstrap.js`: file importato da `app.js`, usato per inizializzare parti comuni

---

## 4. Tailwind già configurato

Nel progetto Laravel moderno, Tailwind è già predisposto.

Il file CSS principale contiene o richiama la configurazione necessaria per generare gli stili in base alle classi usate nelle view.

L’idea è:

```text
scrivo classi Tailwind nelle view
Vite/Tailwind analizzano i file sorgente
viene generato il CSS finale
```

Il corso non entra ancora nel dettaglio completo di Tailwind.

Mostra solo che, una volta collegato `app.css`, lo stile della pagina cambia.

---

## 5. Configurazione Vite

La configurazione di Vite si trova in:

```text
vite.config.js
```

Dentro questo file Laravel indica a Vite quali asset prendere in input.

In genere troviamo qualcosa di questo tipo:

```js
laravel({
    input: [
        'resources/css/app.css',
        'resources/js/app.js',
    ],
    refresh: true,
})
```

Questi sono i file che Vite compila e rende disponibili alla pagina.

---

## 6. npm dependencies

Per usare Vite servono le dipendenze Node/npm.

Se non sono ancora installate, `npm run dev` fallisce perché mancano i pacchetti.

Comando:

```bash
npm install
```

oppure forma abbreviata:

```bash
npm i
```

Questo legge `package.json` e installa le dipendenze in:

```text
node_modules/
```

Nota importante per Git:

```text
node_modules/ non deve essere committata
```

---

## 7. Avviare Vite in sviluppo

Dopo aver installato le dipendenze:

```bash
npm run dev
```

Questo avvia il dev server di Vite.

Durante lo sviluppo va lasciato in esecuzione.

Vite compila e aggiorna gli asset mentre lavoriamo.

Nel workflow Laravel locale, spesso servono due terminali:

Terminale 1:

```bash
php artisan serve
```

Terminale 2:

```bash
npm run dev
```

---

## 8. Includere CSS e JS con `@vite`

Per collegare gli asset a una view Blade, Laravel usa la direttiva:

```blade
@vite([
    'resources/css/app.css',
    'resources/js/app.js',
])
```

Questa direttiva genera automaticamente i tag necessari per caricare CSS e JavaScript.

In sviluppo punta al dev server Vite.

In produzione userà gli asset compilati da build.

---

## 9. Modifica alla homepage

Nel corso la demo viene fatta sulla homepage.

File:

```text
resources/views/pages/home.blade.php
```

Versione aggiornata:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>
<body>
    @if ($showGreeting)
        <h1>{{ $greeting }}</h1>
    @endif
</body>
</html>
```

Questo collega `app.css` e `app.js` alla pagina.

---

## 10. Verifica nel browser

Dopo aver avviato:

```bash
npm run dev
```

e il server Laravel:

```bash
php artisan serve
```

apri:

```text
http://127.0.0.1:8000/
```

Risultato atteso:

- la pagina continua a funzionare
- lo stile cambia perché Tailwind/CSS viene caricato
- il sorgente pagina mostra asset serviti tramite Vite

---

## 11. Verifica JavaScript

Per verificare che `app.js` venga caricato, si può aggiungere temporaneamente:

```js
console.log('It works');
```

in:

```text
resources/js/app.js
```

Poi si apre la console del browser.

Risultato atteso:

```text
It works
```

Questa modifica è didattica.

Può essere mantenuta se il corso la lascia, oppure rimossa più avanti quando non serve più.

---

## 12. `npm run dev` vs `npm run build`

La lezione cita due comandi distinti.

Sviluppo:

```bash
npm run dev
```

Produzione:

```bash
npm run build
```

`npm run dev` serve mentre sviluppiamo.

`npm run build` crea gli asset ottimizzati per produzione.

Il corso non approfondisce ancora la build di produzione.

---

## 13. Dove mettere JavaScript futuro

Il file principale è:

```text
resources/js/app.js
```

Qui si può aggiungere JavaScript leggero oppure importare librerie.

La lezione cita esempi futuri come:

- Alpine.js per interazioni leggere
- Inertia per applicazioni frontend più strutturate

Per ora l’obiettivo è solo capire come collegare JavaScript e CSS.

---

## 14. Problema ancora aperto: duplicazione layout

La lezione collega asset alla homepage, ma segnala subito un limite:

> non vogliamo ripetere tutta la struttura HTML in ogni view.

Se dovessimo aggiungere `@vite` manualmente in tutte le view, duplicheremmo codice.

La prossima lezione affronterà questo problema con Blade components.

---

## 15. Codice finale consigliato

`resources/views/pages/home.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>
<body>
    @if ($showGreeting)
        <h1>{{ $greeting }}</h1>
    @endif
</body>
</html>
```

---

## 16. Lesson Learned

### 1. Laravel usa Vite per compilare asset frontend

Vite gestisce CSS e JavaScript dell’applicazione.

---

### 2. Gli asset sorgente stanno in `resources/`

File principali:

```text
resources/css/app.css
resources/js/app.js
```

---

### 3. `vite.config.js` definisce gli input

Laravel comunica a Vite quali file compilare.

---

### 4. Prima di usare Vite servono le dipendenze npm

Comando:

```bash
npm install
```

---

### 5. Durante lo sviluppo si usa `npm run dev`

Va lasciato in esecuzione mentre si lavora sugli asset.

---

### 6. Blade include gli asset con `@vite`

Esempio:

```blade
@vite([
    'resources/css/app.css',
    'resources/js/app.js',
])
```

---

### 7. `npm run build` serve per produzione

Genera asset compilati e ottimizzati.

---

### 8. La struttura HTML non va duplicata in ogni view

La prossima lezione introdurrà Blade components per risolvere questo problema.

---

## 17. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Installare dipendenze frontend:

```bash
npm install
```

Avviare Vite:

```bash
npm run dev
```

In un altro terminale, avviare Laravel:

```bash
php artisan serve
```

Aprire homepage:

```text
http://127.0.0.1:8000/
```

Controllare la view homepage:

```bash
sed -n '1,160p' resources/views/pages/home.blade.php
```

Controllare file JS:

```bash
sed -n '1,120p' resources/js/app.js
```

Controllare configurazione Vite:

```bash
sed -n '1,160p' vite.config.js
```

Build produzione:

```bash
npm run build
```

---

## 18. Stato finale della lezione

Alla fine della lezione sappiamo:

- dove Laravel tiene CSS e JavaScript sorgenti
- che Vite compila gli asset frontend
- che `npm install` installa le dipendenze Node
- che `npm run dev` avvia il processo di sviluppo
- che `@vite` collega CSS e JS a Blade
- che `app.js` può contenere codice JavaScript dell’app
- che `app.css` viene caricato nella pagina
- che la duplicazione dell’HTML verrà affrontata con Blade components

Obiettivo raggiunto:

> la homepage Laravel carica JavaScript e CSS tramite Vite.

# Getting Started with Laravel — Lezione 08
## Meet the Artisan command again

Data laboratorio: 2026-06-05  
Corso: Getting Started with Laravel  
Episodio: 08 — Meet the Artisan command again  
Durata video: circa 5 minuti  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo di questa lezione è:

> ripassare Artisan e capire perché lo useremo continuamente durante lo sviluppo Laravel.

Artisan è il comando da terminale di Laravel.

Lo abbiamo già usato per:

- avviare il server locale
- creare view
- creare controller
- mostrare la lista delle rotte

In questa lezione il docente si ferma un momento per dire:

> Artisan non serve solo per quei comandi già visti; è uno strumento centrale per velocizzare tantissime attività quotidiane.

---

## 2. Cos’è Artisan

Artisan è la CLI ufficiale di Laravel.

CLI significa:

```text
Command Line Interface
```

cioè interfaccia da riga di comando.

Il comando base è:

```bash
php artisan
```

Va eseguito dalla root del progetto Laravel, cioè nella cartella dove esiste il file:

```text
artisan
```

Nel nostro laboratorio:

```bash
cd ~/Progetti/web/laravel-lab/first-project
php artisan
```

Se lo lanci dalla root sbagliata, per esempio da `~/Progetti/web`, riceverai un errore tipo:

```text
Could not open input file: artisan
```

Questo succede perché lì il file `artisan` non esiste.

---

## 3. A cosa serve Artisan

Il docente riassume il punto:

> Artisan serve a velocizzare le attività quotidiane.

In pratica, evita di creare molti file a mano.

Esempi già visti:

```bash
php artisan make:view pages.home
```

crea una view Blade.

```bash
php artisan make:controller HomeController
```

crea un controller.

```bash
php artisan route:list
```

mostra le rotte registrate.

```bash
php artisan serve
```

avvia il server locale.

Artisan riduce:

- lavoro ripetitivo
- rischio di errori
- file creati nella cartella sbagliata
- namespace scritti male
- struttura incoerente del progetto

---

## 4. Come vedere tutti i comandi disponibili

Per vedere cosa offre Artisan basta eseguire:

```bash
php artisan
```

oppure:

```bash
php artisan list
```

Laravel mostrerà l’elenco dei comandi disponibili.

Il docente non li analizza tutti, perché sono molti.

L’idea importante è:

> quando impari un concetto Laravel, spesso esiste anche un comando Artisan per creare o gestire quel concetto.

---

## 5. Comando `help`

Artisan permette anche di chiedere aiuto su un comando specifico.

Esempio:

```bash
php artisan help make:controller
```

oppure:

```bash
php artisan make:controller --help
```

Questo mostra opzioni, argomenti e uso del comando.

È molto utile quando non ricordiamo esattamente la sintassi.

---

## 6. Comandi base citati nella lezione

Il docente cita vari comandi utili.

### `about`

```bash
php artisan about
```

Mostra informazioni sull’applicazione Laravel.

Può includere dettagli su:

- ambiente
- versione Laravel
- PHP
- cache
- driver configurati
- stato generale dell’app

È utile per farsi un’idea rapida del progetto.

---

### `env`

```bash
php artisan env
```

Mostra l’ambiente corrente dell’applicazione.

Esempio:

```text
local
```

È collegato a:

```env
APP_ENV=local
```

nel file `.env`.

---

### `list`

```bash
php artisan list
```

Mostra la lista dei comandi Artisan disponibili.

È simile a lanciare semplicemente:

```bash
php artisan
```

---

### `serve`

```bash
php artisan serve
```

Avvia il server locale di sviluppo.

Di solito rende l’app disponibile su:

```text
http://127.0.0.1:8000
```

---

### `test`

```bash
php artisan test
```

Esegue i test dell’applicazione.

Più avanti sarà importante per controllare che il codice continui a funzionare.

---

### `tinker`

```bash
php artisan tinker
```

Apre una sessione interattiva dentro l’app Laravel.

Serve per provare codice PHP usando il contesto dell’applicazione.

Il docente lo cita come qualcosa che potremmo vedere più avanti.

---

## 7. I comandi `make:*`

Una categoria molto importante è:

```text
make
```

Questi comandi creano file e classi Laravel.

Esempi già visti:

```bash
php artisan make:view home
php artisan make:view pages.home
php artisan make:controller HomeController
```

Il docente spiega che molti concetti Laravel hanno probabilmente un comando `make:*`.

Esempi citati o anticipati:

```bash
php artisan make:command
php artisan make:component
php artisan make:config
php artisan make:controller
php artisan make:view
```

Non dobbiamo conoscerli tutti ora.

La cosa da capire è:

> quando devi creare qualcosa in Laravel, prima chiediti se esiste un comando `make:*`.

---

## 8. Creare comandi Artisan personalizzati

Il docente cita anche:

```bash
php artisan make:command
```

Questo comando permette di creare un comando Artisan personalizzato.

Esempio concettuale:

```bash
php artisan make:command SendDailyReport
```

Non lo facciamo ora.

Ma è importante sapere che Artisan non è solo uno strumento di Laravel già pronto: possiamo anche estenderlo con comandi nostri.

---

## 9. Blade component

Il docente cita:

```bash
php artisan make:component
```

Questo crea un componente Blade.

I componenti Blade verranno trattati più avanti.

Per ora basta sapere:

> Artisan può creare anche pezzi di interfaccia riutilizzabili.

---

## 10. Comandi database e migration

Il docente cita anche i comandi legati alle migration.

Ne abbiamo già visto uno nella fase di setup:

```bash
php artisan migrate
```

In futuro useremo Artisan anche per:

- creare migration
- eseguire migration
- resettare o aggiornare il database
- popolare dati di esempio

Per ora il concetto è:

> Artisan è lo strumento principale anche per lavorare con il database in Laravel.

---

## 11. Comandi di pulizia cache

Il docente cita un esempio molto pratico:

```bash
php artisan view:clear
```

Quando Laravel renderizza le view, può compilare/cacheare file Blade.

Se una view o un componente non sembra aggiornarsi, può essere utile pulire la cache delle view.

Comando:

```bash
php artisan view:clear
```

Questo cancella le view compilate.

Laravel le rigenererà alla richiesta successiva.

---

## 12. Perché usare Artisan invece di creare file a mano

Il docente è abbastanza netto:

> raramente conviene creare tutto a mano.

Motivi:

- ci vuole più tempo
- è più facile sbagliare cartella
- è più facile sbagliare namespace
- si rischia di non rispettare la struttura Laravel
- si perde il vantaggio del framework

Artisan crea file nel posto giusto e con la struttura giusta.

Quindi non è pigrizia: è usare lo strumento pensato dal framework.

---

## 13. Artisan e documentazione Laravel

Il docente spiega che, leggendo la documentazione Laravel, incontreremo spesso comandi Artisan.

La documentazione di solito dice cose come:

```text
Run the following Artisan command...
```

Oppure:

```text
You may generate this class using...
```

Quindi dobbiamo abituarci a vedere Artisan come parte normale del flusso di lavoro.

---

## 14. Pratica fatta nel nostro laboratorio

In questa lezione non è necessario modificare codice applicativo.

La pratica utile è esplorare Artisan.

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Mostrare lista comandi:

```bash
php artisan
```

oppure:

```bash
php artisan list
```

Vedere ambiente corrente:

```bash
php artisan env
```

Mostrare informazioni sull’app:

```bash
php artisan about
```

Vedere aiuto su un comando:

```bash
php artisan help make:controller
```

oppure:

```bash
php artisan make:controller --help
```

Vedere comandi `make` disponibili:

```bash
php artisan list make
```

Pulire view compilate:

```bash
php artisan view:clear
```

Mostrare rotte:

```bash
php artisan route:list
```

---

## 15. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare già:

- tutti i comandi Artisan
- creare comandi custom
- usare Tinker in dettaglio
- creare componenti Blade
- gestire migration avanzate
- capire tutte le cache Laravel
- scrivere test
- gestire deployment

Il focus era:

> capire che Artisan è uno strumento quotidiano e imparare come esplorare i comandi disponibili.

---

## 16. Lesson Learned

### 1. Artisan è la CLI di Laravel

Si usa con:

```bash
php artisan
```

dalla root del progetto Laravel.

---

### 2. Artisan velocizza attività ripetitive

Serve per creare file, avviare server, vedere rotte, eseguire test, gestire database e molto altro.

---

### 3. `php artisan` mostra i comandi disponibili

È il primo comando da usare quando vogliamo esplorare cosa possiamo fare.

---

### 4. `php artisan list` mostra la lista dei comandi

È equivalente come idea alla lista principale dei comandi Artisan.

---

### 5. `php artisan help <comando>` spiega un comando specifico

Esempio:

```bash
php artisan help make:controller
```

---

### 6. I comandi `make:*` creano file Laravel

Esempi:

```bash
php artisan make:view pages.home
php artisan make:controller HomeController
```

---

### 7. Artisan riduce gli errori

Creare file a mano è possibile, ma Artisan li crea nella posizione giusta e con struttura corretta.

---

### 8. `php artisan view:clear` pulisce le view compilate

È utile se Blade o componenti sembrano non aggiornarsi.

---

### 9. Artisan compare spesso nella documentazione Laravel

Quando leggiamo la documentazione, troveremo spesso comandi Artisan suggeriti.

---

### 10. Non bisogna imparare tutti i comandi a memoria

Basta sapere come scoprirli:

```bash
php artisan
php artisan list
php artisan help nome:comando
```

---

## 17. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Lista comandi:

```bash
php artisan
```

Lista comandi esplicita:

```bash
php artisan list
```

Aiuto su un comando:

```bash
php artisan help make:controller
```

Informazioni ambiente:

```bash
php artisan env
```

Informazioni applicazione:

```bash
php artisan about
```

Lista rotte:

```bash
php artisan route:list
```

Pulizia view compilate:

```bash
php artisan view:clear
```

---

## 18. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- cos’è Artisan
- perché si usa continuamente in Laravel
- come vedere i comandi disponibili
- come chiedere aiuto su un comando
- quali comandi abbiamo già usato
- cosa sono i comandi `make:*`
- perché Artisan riduce lavoro manuale ed errori
- a cosa serve `view:clear`
- perché la documentazione Laravel richiama spesso Artisan

Obiettivo raggiunto:

> abbiamo consolidato Artisan come strumento quotidiano del laboratorio Laravel.

# PHP — Lezione 2
## Layout statico del catalogo come ponte verso il rendering dinamico

[English](lesson-02-learned.md) | [Italiano](lesson-02-learned.it.md)

## 1. Obiettivo della lezione

Ricostruire la seconda lezione PHP recuperata a partire dall'artifact disponibile del catalogo statico e riprodurne autonomamente i concetti strutturali.

Il file `prodotti.html` recuperato costituisce evidence dell'artifact disponibile della lezione. Il file sotto `php-lab/exercises/lesson-02/` costituisce invece la nostra riproduzione e verifica.

## 2. Confine dell'evidence

L'artifact recuperato mostrava un catalogo HTML completamente statico basato su Bootstrap 5.3.3.

La struttura osservata comprendeva:

- un container Bootstrap principale;
- un header con logo e navigazione orizzontale;
- cinque voci di navigazione orizzontale;
- una sidebar sinistra con cinque categorie;
- un'area principale dedicata ai prodotti;
- dodici card di prodotto statiche;
- tre colonne di prodotti per riga al breakpoint Bootstrap `md`;
- paginazione statica con le pagine `1`, `2` e `3`;
- un footer;
- CSS e JavaScript di Bootstrap caricati da CDN.

L'artifact non conteneva tag PHP, `$_GET`, `$_POST`, `foreach`, `include`, `require`, PDO o SQL.

L'artifact supporta quindi la ricostruzione della struttura del catalogo statico. Non dimostra tutto ciò che potrebbe essere stato spiegato o svolto in aula.

## 3. Riproduzione locale

È stato creato un catalogo statico indipendente:

1. `catalog.html` — layout Bootstrap con header/navigazione, sidebar delle categorie, dodici card di prodotto, paginazione statica e footer.

La riproduzione rimane intenzionalmente statica.

Non introduce PHP, accesso al database, cicli dinamici, include o paginazione dinamica prima che tali concetti siano supportati dall'evidence recuperata delle lezioni successive.

L'implementazione utilizza contenuti differenti rispetto all'artifact del docente, preservandone il target didattico strutturale.

## 4. Verifica

Il file generato è stato verificato strutturalmente.

Risultati locali osservati:

- `CARD_COUNT=12`;
- `PRODUCT_COLUMN_COUNT=12`;
- `CATEGORY_COUNT=5`;
- `PAGE_ITEM_COUNT=3`;
- `PHP_2_STATIC_BOUNDARY=PASS`.

Il catalogo è stato quindi servito tramite il development server PHP 8.3.6 in ascolto su `127.0.0.1:18082`.

Una richiesta HTTP reale a `/catalog.html` ha restituito `HTTP_CODE=200`.

La risposta HTTP conteneva:

- dodici card di prodotto;
- dodici titoli di prodotto;
- cinque voci di categoria;
- tre voci di paginazione;
- sia `Product 1` sia `Product 12`.

I gate risultanti sono stati:

- `PHP_2_PRODUCT_RANGE=PASS`;
- `PHP_2_HTTP_RENDERING=PASS`;
- `PHP_2_SERVER_CLEANUP=PASS`.

Il development server è stato arrestato dopo la verifica.

## 5. Lesson Learned

### 1. Un'applicazione dinamica può partire dal contratto di una pagina statica

Prima di introdurre PHP o un database, la struttura della pagina può stabilire dove collocare navigazione, categorie, prodotti, paginazione e footer.

### 2. Bootstrap fornisce una griglia responsive riutilizzabile

Il catalogo utilizza le colonne Bootstrap per separare la sidebar delle categorie dall'area prodotti e colonne prodotto `col-md-4` per ottenere tre card per riga dal breakpoint `md` in su.

### 3. Il markup statico ripetuto rende visibile la successiva opportunità di refactoring

Dodici card di prodotto rappresentate manualmente rendono esplicita la struttura ripetuta. Una lezione successiva può sostituire questa ripetizione con rendering guidato dai dati senza cambiare il ruolo concettuale di ogni card.

### 4. La paginazione statica è presentazione, non logica di paginazione

I link alle pagine `1`, `2` e `3` stabiliscono il componente visuale, ma non selezionano ancora record né calcolano offset.

### 5. L'assenza di codice dinamico fa parte dell'evidence

L'artifact recuperato non contiene logica PHP o database. Aggiungere tali concetti a questa riproduzione renderebbe meno netto il confine tra PHP 2 e le successive lezioni basate sul database.

### 6. La ricostruzione resta limitata dall'artifact disponibile

Il file disponibile supporta una ricostruzione puntuale del catalogo statico. Non può stabilire spiegazioni o attività d'aula non registrate.

### 7. La riproduzione richiede esecuzione e verifica

Il catalogo creato localmente non è stato considerato completo per il solo fatto che l'HTML esistesse. È stato servito via HTTP e la struttura attesa è stata verificata nella risposta effettiva.

## 6. Stato finale della lezione

PHP 2 è ricostruita fino all'artifact disponibile, riprodotta autonomamente, servita e verificata localmente.

`PHP_2_LOCAL_REPRODUCTION=PASS`

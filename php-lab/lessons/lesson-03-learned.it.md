# PHP — Lezione 3
## Catalogo basato su database con PDO, filtro e paginazione

[English](lesson-03-learned.md) | [Italiano](lesson-03-learned.it.md)

## 1. Obiettivo della lezione

Ricostruire la terza lezione PHP recuperata a partire dallo snapshot disponibile del docente e dall'evidence del database del corso, quindi riprodurre e verificare autonomamente il passaggio dal catalogo statico di PHP 2 al rendering basato su database.

Lo snapshot recuperato del docente costituisce evidence dell'implementazione disponibile della lezione. I file sotto `php-lab/exercises/lesson-03/` costituiscono invece la nostra riproduzione e verifica.

## 2. Confine dell'evidence

Lo snapshot recuperato del docente conteneva:

- `prodotti.php`;
- `include/data.php`;
- `include/db.php`;
- `include/footer.php`;
- `include/header.php`;
- `include/menusx.php`;
- `include/prodotto.php`.

Lo snapshot mostrava:

- composizione della pagina tramite include PHP;
- connessione PDO a un database MySQL chiamato `shop`;
- gestione degli errori PDO basata su eccezioni;
- modalità di fetch tramite array associativi;
- prepared statement nativi;
- un filtro `GET` chiamato `filtro`;
- un parametro di pagina `GET` chiamato `pagina`;
- dodici record per pagina;
- calcolo dell'offset a partire dalla pagina richiesta;
- una `SELECT` preparata;
- join tra `brani`, `autori` e `generi`;
- filtro del titolo tramite `LIKE`;
- ordinamento per titolo;
- `LIMIT` e `OFFSET`;
- iterazione sui prodotti risultanti tramite `foreach`;
- rendering dinamico dei prodotti;
- link di paginazione statici per le pagine `1`, `2` e `3`.

Il contratto normalizzato del database è stato corroborato dal materiale del corso recuperato che utilizza:

- `autori(autore_id, nome)`;
- `generi(genere_id, nome)`;
- `brani(id, titolo, autore_id, genere_id, durata_minuti, anno)`.

Il dump normalizzato originale con `CREATE TABLE` e il dataset originale non sono stati recuperati.

Un diverso artifact precedente del corso, `musica.sql`, utilizzava uno schema differente e pertanto non è stato trattato come database di PHP 3.

La fixture locale del database è quindi una ricostruzione deterministica per la verifica, non una copia recuperata del database del docente.

## 3. Riproduzione locale

La riproduzione contiene:

1. `database/schema.sql` — schema normalizzato ricostruito per `autori`, `generi` e `brani`;
2. `database/seed.sql` — dataset locale deterministico con quattro autori, tre generi e quindici brani;
3. `include/db.php` — configurazione della connessione PDO/MySQL;
4. `include/data.php` — normalizzazione della richiesta e query preparata del catalogo;
5. `include/header.php` e `include/footer.php` — struttura condivisa della pagina;
6. `include/menusx.php` — form `GET` per il filtro sul titolo;
7. `include/prodotto.php` — rendering di una riga del database;
8. `prodotti.php` — composizione della pagina, rendering tramite `foreach` e link di paginazione statici.

Il database è stato deliberatamente chiamato `php3_shop_lab` invece di `shop` per preservare il confine tra l'evidence del docente e la nostra implementazione.

A un'identità applicativa locale dedicata, `php3_lab@localhost`, è stato concesso esclusivamente accesso `SELECT`.

La fixture contiene quindici brani in modo che la paginazione da dodici record produca dodici record nella pagina 1 e tre record nella pagina 2. Contiene inoltre brani con genere `NULL` per poter esercitare il comportamento della `LEFT JOIN`.

`durata_minuti` preserva la rappresentazione `DECIMAL(4,2)` del corso. È allineata all'evidence per l'esercizio, ma non deve essere interpretata come modello generale per rappresentare una durata.

## 4. Differenze intenzionali dell'implementazione

La riproduzione preserva i concetti della lezione senza riprodurre debolezze evitabili osservate nello snapshot disponibile.

### Binding intero per la paginazione

Lo snapshot del docente forniva i valori di paginazione tramite l'array di parametri dell'esecuzione.

La riproduzione locale effettua esplicitamente il binding di `LIMIT` e `OFFSET` con `PDO::PARAM_INT`.

### Output HTML con escaping

Il form del filtro recuperato rifletteva il proprio valore senza `htmlspecialchars()`.

La riproduzione locale effettua l'escaping dei dati riflessi della richiesta e dei valori dei prodotti provenienti dal database tramite `htmlspecialchars()`.

### Credenziale database a runtime

Nei file della lezione non viene memorizzata alcuna password del database.

`PHP3_DB_PASSWORD` deve essere fornita tramite l'ambiente di runtime. Host del database, nome del database e utente applicativo hanno inoltre default locali espliciti adatti al database isolato della lezione.

Queste differenze appartengono alla nostra implementazione. Non riscrivono ciò che è stato osservato nello snapshot del docente.

## 5. Verifica

Il gate di readiness MySQL/PDO ha stabilito:

- PHP 8.3.6;
- MySQL 8.0.46;
- `pdo_mysql` disponibile;
- driver PDO `mysql` e `sqlite`;
- servizio MySQL attivo.

Lo schema ricostruito è stato importato in `php3_shop_lab`.

MySQL ha materializzato:

- `autori`;
- `generi`;
- `brani`;
- la foreign key da `brani.autore_id` a `autori.autore_id`;
- la foreign key da `brani.genere_id` a `generi.genere_id`.

Il seed deterministico ha prodotto:

- `AUTORI_ROWS=4`;
- `GENERI_ROWS=3`;
- `BRANI_ROWS=15`.

L'identità applicativa `php3_lab@localhost` poteva leggere il catalogo e non poteva eseguire un `INSERT`.

Il gate dei privilegi risultante è stato:

- `PHP_3_APP_WRITE_GATE=PASS`.

Il data layer è stato eseguito direttamente contro MySQL.

I risultati osservati comprendevano:

- pagina 1: `PAGE=1`, `OFFSET=0`, `RESULTS=12`;
- pagina 2: `PAGE=2`, `OFFSET=12`, `RESULTS=3`;
- filtro `Road`: `Dream Road` e `Open Road`;
- pagina non valida `-8`: normalizzata alla pagina `1`, offset `0`.

Tutti e sette i file PHP hanno superato `php -l`.

La lezione completa è stata quindi servita tramite il development server PHP su `127.0.0.1:18083`.

Richieste HTTP reali hanno verificato:

- dodici record renderizzati nella pagina 1;
- tre record renderizzati nella pagina 2;
- due record per il filtro sul titolo `Road`;
- `Hidden Moon` renderizzato tramite la `LEFT JOIN` con genere mancante;
- markup ostile riflesso sottoposto a escaping HTML;
- il frammento script raw iniettato non renderizzato come markup.

I gate risultanti comprendevano:

- `PHP_3_HTTP_ESCAPE=PASS`;
- `PHP_3_HTTP_RAW_SCRIPT=PASS`;
- `PHP_3_HTTP_CLEANUP=PASS`;
- `PHP_3_SECRET_GATE=PASS`;
- `PHP_3_READ_ONLY_BOUNDARY=PASS`;
- `PHP_3_END_TO_END_RUNTIME=PASS`.

Il development server è stato arrestato dopo la verifica.

## 6. Lesson Learned

### 1. PHP 3 trasforma il catalogo statico in una pagina guidata dai dati

PHP 2 ha stabilito la struttura del catalogo. PHP 3 sostituisce il contenuto statico ripetuto dei prodotti con record selezionati da MySQL e renderizzati tramite PHP.

### 2. Gli include separano le responsabilità della pagina

Configurazione del database, recupero dei dati, struttura della pagina, filtro e rendering dei prodotti possono risiedere in file separati mentre `prodotti.php` compone la risposta finale.

### 3. PDO fornisce un confine controllato verso il database

L'applicazione si connette tramite PDO con errori basati su eccezioni, fetch associativi e prepared statement nativi invece di incorporare direttamente i risultati delle query nella logica della pagina.

### 4. I parametri della richiesta devono essere normalizzati prima di influenzare le query

Il filtro del titolo ha un default definito e il numero di pagina viene vincolato a un minimo di uno prima di calcolarne l'offset.

### 5. La paginazione riguarda sia la presentazione sia la selezione dei dati

A differenza della paginazione puramente statica di PHP 2, PHP 3 utilizza `LIMIT` e `OFFSET` per selezionare un sottoinsieme di righe del database della dimensione di una pagina. I link visibili delle pagine rimangono intenzionalmente statici entro il confine di questa lezione.

### 6. Una LEFT JOIN preserva le righe prive di dati correlati opzionali

I brani privi di genere rimangono nel catalogo perché i generi vengono collegati tramite `LEFT JOIN`. La riproduzione verifica questo comportamento tramite casi `NULL` espliciti nella fixture.

### 7. L'escaping dell'output è distinto dalla parametrizzazione SQL

I prepared statement proteggono il confine della query. `htmlspecialchars()` protegge il confine del rendering HTML. Entrambi sono necessari per ragioni differenti.

### 8. La ricostruzione deve distinguere i dati recuperati dalle fixture sostitutive

Il contratto dello schema normalizzato era recuperabile dall'evidence del corso, ma DDL e dataset originali non lo erano. Una fixture deterministica rende eseguibile la lezione senza presentare falsamente dati ricostruiti come evidence originale.

### 9. Il principio del minimo privilegio può essere verificato, non soltanto dichiarato

L'identità applicativa della lezione dispone esclusivamente dell'accesso `SELECT` e un tentativo di scrittura è stato utilizzato come gate negativo per confermare tale confine.

### 10. La riproduzione è completa soltanto quando viene eseguito l'intero percorso

La lezione è stata verificata dall'input HTTP reale, attraverso PDO/MySQL, fino all'HTML renderizzato. I soli controlli sintattici dei singoli file non avrebbero stabilito lo stesso risultato.

## 7. Stato finale della lezione

PHP 3 è ricostruita fino allo snapshot disponibile del docente e all'evidence del database, riprodotta autonomamente contro MySQL, servita e verificata localmente.

Il DDL normalizzato originale e il dataset originale restano non recuperati e non vengono rappresentati come artifact recuperati.

`PHP_3_LOCAL_REPRODUCTION=PASS`

# PHP — Lezione 1
## Fondamenti del linguaggio e prima pagina dinamica

[English](lesson-01-learned.md) | [Italiano](lesson-01-learned.it.md)

## 1. Obiettivo della lezione

Ricostruire la prima lezione PHP recuperata e riprodurne autonomamente i concetti fondamentali con PHP 8.3.6.

Lo snapshot recuperato dal docente costituisce evidence storica di ciò che è stato mostrato in aula. I file sotto `php-lab/exercises/lesson-01/` costituiscono invece la nostra riproduzione e verifica.

## 2. Confine dell'evidence

Lo snapshot recuperato della lezione mostrava esempi relativi a:

- variabili e assegnazione;
- scambio di due valori tramite una variabile temporanea;
- aritmetica e `number_format()`;
- array associativi;
- costanti dichiarate con `define()` e `const`;
- `PHP_VERSION` e `__DIR__`;
- array di prodotti renderizzati con `foreach`;
- PHP incorporato nell'HTML e `<?= ?>`;
- un ciclo `for` che salta i multipli di tre;
- array associativi annidati;
- iterazione chiave/valore con `foreach`;
- un form GET;
- `$_GET`;
- un controllo della maggiore età con `if`/`else`.

Queste osservazioni stabiliscono il target della ricostruzione. Da sole non dimostrano la riproduzione locale.

## 3. Riproduzione locale

Sono stati creati cinque esercizi indipendenti:

1. `01-variables.php` — variabili, scambio temporaneo, aritmetica e formattazione numerica.
2. `02-array-constants.php` — array associativi, valori derivati, costanti, `PHP_VERSION` e `__DIR__`.
3. `03-products-table.php` — un array di prodotti renderizzato come tabella HTML con `foreach` e sintassi echo breve.
4. `04-loops-nested-arrays.php` — `for`, modulo, `continue`, array associativi annidati e iterazione chiave/valore.
5. `05-age-form.php` — un form GET, `$_GET`, null coalescing e rendering condizionale.

La riproduzione dimostra deliberatamente i concetti recuperati senza trattare lo snapshot del docente come nostra implementazione.

## 4. Verifica

Tutti e cinque i file PHP hanno superato `php -l`.

Gli esercizi CLI sono stati eseguiti e verificati rispetto al comportamento atteso:

- le due variabili numeriche sono state scambiate correttamente;
- il totale calcolato è risultato `37.50`;
- il totale del prodotto associativo è risultato `99.80`;
- la tabella dei prodotti ha renderizzato i prodotti e i totali attesi;
- i multipli di tre sono stati esclusi dal ciclo numerico;
- i dati annidati delle città sono stati renderizzati correttamente.

L'esercizio GET è stato inoltre verificato tramite il development server PHP in ascolto su `127.0.0.1`.

Comportamento HTTP osservato:

- una richiesta senza `age` ha renderizzato il form senza alcun risultato;
- `?age=17` ha renderizzato `Result: minor`;
- `?age=18` ha renderizzato `Result: adult`;
- durante la verifica il log del server PHP non conteneva warning PHP, fatal error, parse error o deprecation.

Il development server è stato arrestato dopo il test e non risultava più raggiungibile sulla porta di verifica.

## 5. Lesson Learned

### 1. Le variabili PHP utilizzano il prefisso `$`

I valori possono essere assegnati e successivamente modificati durante l'esecuzione.

### 2. Gli array possono modellare dati strutturati

Gli array associativi associano chiavi significative ai valori e gli array annidati possono rappresentare record più complessi.

### 3. PHP fornisce due forme di costanti definite dall'utente

Sono stati riprodotti sia `define()` sia `const`, insieme a valori predefiniti/runtime come `PHP_VERSION` e alla costante magica `__DIR__`.

### 4. Cicli e condizioni controllano l'esecuzione

`for`, `foreach`, `if`, modulo e `continue` sono stati esercitati con verifiche deterministiche del comportamento.

### 5. PHP può generare HTML dinamicamente

Espressioni e strutture di controllo PHP possono essere incorporate in un documento HTML. La sintassi echo breve `<?= ?>` è stata utilizzata per renderizzare valori nelle celle della tabella.

### 6. I dati della query string sono disponibili tramite `$_GET`

Una richiesta HTTP reale al development server PHP locale ha dimostrato che la pagina riceve il parametro query `age` e seleziona il ramo condizionale appropriato.

### 7. Ricostruzione e riproduzione sono affermazioni differenti

Il materiale recuperato dal docente ci dice cosa era presente nello snapshot della lezione. Il completamento nel laboratorio richiede nostra implementazione, esecuzione e verifica.

## 6. Stato finale della lezione

PHP 1 è ricostruita, riprodotta localmente, eseguita e verificata.

`PHP_1_LOCAL_REPRODUCTION=PASS`

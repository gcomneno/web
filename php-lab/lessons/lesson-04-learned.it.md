# PHP — Lezione 4
## Paginazione dinamica, form di amministrazione e confine POST

[English](lesson-04-learned.md) | [Italiano](lesson-04-learned.it.md)

Questo documento registra la ricostruzione e la riproduzione locale indipendente della quarta lezione PHP recuperata.

## 1. Obiettivo della lezione

PHP 4 estende il catalogo basato su database di PHP 3 con:

- un campo prezzo nel database del catalogo;
- paginazione dinamica;
- un form di amministrazione per i dati del prodotto;
- opzioni autore dinamiche caricate da MySQL;
- opzioni genere statiche;
- un endpoint POST che riceve i campi inviati del prodotto.

Lo snapshot recuperato raggiunge il confine POST, ma non implementa la persistenza nel database.

## 2. Confine dell'evidence

Lo snapshot recuperato dal docente è:

`20260908_prodottiadmin.zip`

SHA-256:

`f5245d47cd04938b846b0303f625f4948714ec38adfa3159fda0a3120a72282f`

L'archivio ha superato il controllo di integrità ZIP e contiene dieci file PHP:

- `adminprodotti.php`
- `prodotti.php`
- `salva.php`
- `include/data.php`
- `include/db.php`
- `include/footer.php`
- `include/header.php`
- `include/menusx.php`
- `include/pager.php`
- `include/prodotto.php`

Lo snapshot mostra un pager dinamico. La sua implementazione seleziona tutti i `brani` corrispondenti, esegue `fetchAll()`, conta le righe restituite e genera i link delle pagine sottraendo ripetutamente la dimensione della pagina.

Il form di amministrazione invia questi campi a `salva.php` tramite POST:

- `titolo`
- `autore`
- `genere`
- `durata`
- `anno`
- `prezzo`
- `descrizione`

Le opzioni autore vengono caricate dinamicamente da `autori`. Le opzioni genere `1`, `2` e `3` sono hard-coded.

`salva.php` contiene soltanto un esempio commentato dei dati `$_POST` ricevuti. Non vi è implementato alcun `INSERT`, `UPDATE` o `DELETE`.

Lo snapshot contiene anche un link a `dettaglioprodotto.php`, ma tale file non è presente nell'archivio recuperato. Il comportamento del dettaglio prodotto non fa quindi parte di questa riproduzione.

Lo snapshot legge `brani.prezzo`. Il materiale SQL del corso corrobora indipendentemente la modifica dello schema:

`prezzo DECIMAL(6,2) NOT NULL DEFAULT 0.99`

Lo snapshot PHP 4 non prova l'esistenza di una colonna `brani.descrizione`. Il form contiene un campo `descrizione`, ma la query del catalogo assegna a `autori.nome` l'alias `descrizione` e `salva.php` non persiste il valore inviato.

Il dump originale del database PHP 4 e il dataset originale non sono stati recuperati.

## 3. Riproduzione locale

La riproduzione indipendente si trova in:

`php-lab/exercises/lesson-04/`

Contiene dieci file PHP più fixture deterministiche per schema e seed.

Il contratto dello schema PHP 3 viene esteso soltanto con la colonna corroborata:

`prezzo DECIMAL(6,2) NOT NULL DEFAULT 0.99`

Non viene aggiunta alcuna colonna `brani.descrizione`, perché tale colonna non è provata dall'evidence PHP 4 recuperata.

Il database locale è deliberatamente isolato come:

`php4_shop_lab`

L'identità applicativa è:

`php4_lab@localhost`

e dispone esclusivamente di accesso SELECT.

La fixture deterministica contiene:

- 4 autori;
- 3 generi;
- 15 brani;
- due brani con genere NULL;
- prezzi deterministici per esercitare il rendering del prezzo.

Il catalogo mantiene 12 record per pagina.

Il form di amministrazione riproduce il confine osservato:

- sette campi inviati;
- autori letti dinamicamente dal database;
- tre opzioni genere statiche;
- invio POST a `salva.php`;
- nessuna persistenza nel database.

## 4. Differenze implementative intenzionali

### Conteggio efficiente per la paginazione

Lo snapshot recuperato esegue:

`SELECT * -> fetchAll() -> count()`

La riproduzione locale usa invece una query parametrizzata `COUNT(*)`.

Questo modifica la strategia implementativa, non il contratto osservabile della paginazione.

### Binding esplicito degli interi

I valori `LIMIT` e `OFFSET` della paginazione vengono associati esplicitamente con `PDO::PARAM_INT`.

### Normalizzazione della pagina

La pagina richiesta viene normalizzata a un valore minimo di `1`.

### Escaping dell'output HTML

I valori riflessi dalla richiesta, il testo proveniente dal database, i nomi degli autori, le query string e i valori POST vengono sottoposti a escaping prima del rendering HTML.

La parametrizzazione SQL e l'escaping HTML vengono trattati come confini di sicurezza distinti.

### Credenziale database a runtime

Nessuna password del database viene committata.

`PHP4_DB_PASSWORD` fornisce la credenziale del runtime locale.

### Minimo privilegio e assenza di persistenza

L'identità applicativa rimane SELECT-only.

È una scelta deliberata: lo snapshot PHP 4 recuperato contiene un form di amministrazione e un endpoint POST, ma nessuna operazione di scrittura sul database.

La riproduzione quindi non inventa una capacità `INSERT` che l'artifact recuperato non dimostra.

## 5. Verifica

La verifica runtime ha utilizzato:

- PHP 8.3.6;
- PDO con `pdo_mysql`;
- MySQL 8.0.46;
- il database isolato `php4_shop_lab`.

La verifica del database ha confermato:

- `brani.prezzo` è `DECIMAL(6,2) NOT NULL DEFAULT 0.99`;
- 4 autori;
- 3 generi;
- 15 brani;
- l'identità applicativa dispone esclusivamente di accesso SELECT;
- un tentativo di scrittura dell'applicazione viene negato.

La verifica HTTP del catalogo ha confermato:

- pagina 1 mostra 12 record su 15;
- pagina 2 mostra i 3 rimanenti;
- la paginazione dinamica riporta 15 record;
- il filtro `Road` restituisce `Dream Road` e `Open Road`;
- un genere NULL viene mostrato come `Non specificato`;
- una pagina negativa viene normalizzata alla pagina 1;
- i prezzi vengono letti da MySQL e mostrati;
- il markup ostile riflesso viene sottoposto a escaping HTML;
- il markup script grezzo iniettato non viene renderizzato.

La verifica HTTP dell'amministrazione ha confermato:

- `adminprodotti.php` risponde correttamente;
- sono presenti tutti e sette i campi attesi;
- tutti i 4 autori della fixture vengono mostrati dinamicamente;
- le 3 opzioni genere osservate rimangono statiche.

La verifica POST ha confermato:

- tutti i valori inviati raggiungono `salva.php`;
- il markup POST ostile viene sottoposto a escaping HTML;
- il markup script grezzo iniettato non viene renderizzato;
- `brani` contiene 15 righe prima del POST;
- `brani` contiene ancora 15 righe dopo il POST;
- l'identità applicativa non può scrivere nel database.

Tutti i dieci file PHP superano `php -l`.

Il server di sviluppo PHP è stato arrestato dopo la verifica.

`PHP_4_END_TO_END_RUNTIME=PASS`

## 6. Lesson Learned

### 1. PHP 4 estende un catalogo data-driven senza completare ancora la persistenza

Un form che invia dati del prodotto non costituisce evidence che l'applicazione possa creare record nel database.

### 2. Ricezione POST e persistenza nel database sono confini distinti

Ricevere valori `$_POST` ed eseguire un `INSERT` sono capacità applicative differenti e devono essere verificate indipendentemente.

### 3. La paginazione dinamica dipende sia dalla selezione sia dall'informazione sul totale

Il catalogo necessita della pagina corrente di record e di informazioni sufficienti per determinare quante pagine esistono.

### 4. Un comportamento equivalente può avere costi di query differenti

Recuperare tutte le righe corrispondenti soltanto per contarle funziona su un dataset piccolo, mentre `COUNT(*)` esprime direttamente l'operazione di conteggio.

### 5. Le opzioni di un form possono combinare dati dal database e dati statici

Il form recuperato lo dimostra esplicitamente: gli autori provengono da MySQL mentre i generi rimangono hard-coded.

### 6. Le affermazioni sullo schema richiedono evidence

La presenza di un campo form chiamato `descrizione` non prova l'esistenza di una corrispondente colonna nel database.

### 7. Il materiale successivo non deve essere proiettato all'indietro

Un modello mini-ecommerce successivo contiene il concetto di descrizione, ma questo non stabilisce che `brani.descrizione` appartenesse allo snapshot PHP 4.

### 8. Il minimo privilegio deve seguire le capacità implementate

Poiché questa riproduzione non persiste i dati POST, la sua identità applicativa non necessita di privilegi di scrittura.

### 9. L'escaping dell'output rimane necessario sia sul percorso GET sia sul percorso POST

Le prepared statement SQL non proteggono l'output HTML. I valori della richiesta e del database devono comunque essere codificati per il loro contesto di output.

### 10. La ricostruzione conserva anche i confini incompleti

Una lezione recuperata può legittimamente terminare in uno stadio intermedio. La riproduzione deve verificare quello stadio invece di completare silenziosamente l'applicazione.

## 7. Stato finale della lezione

`PHP_4_RECONSTRUCTION=RECONSTRUCTED_TO_AVAILABLE_SNAPSHOT`

`PHP_4_LOCAL_REPRODUCTION=PASS`

`PHP_4_END_TO_END_RUNTIME=PASS`

Lo snapshot recuperato raggiunge il confine POST ma non implementa la persistenza nel database.

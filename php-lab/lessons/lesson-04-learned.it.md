# PHP — Lezione 4
## Paginazione dinamica, amministrazione prodotti e CRUD completo

[English](lesson-04-learned.md) | [Italiano](lesson-04-learned.it.md)

Questo documento registra sia l'evidence recuperata della quarta lezione PHP sia il completamento operativo richiesto per la consegna finale della lezione.

## 1. Obiettivo finale della lezione

PHP 4 porta il catalogo basato su database di PHP 3 a una gestione completa dei prodotti:

- campo `prezzo` nel database;
- paginazione dinamica;
- form di amministrazione;
- **Create**: inserimento di nuovi prodotti;
- **Read**: catalogo e dettaglio del singolo prodotto;
- **Update**: modifica di prodotti esistenti;
- **Delete**: eliminazione di prodotti esistenti.

Nelle card prodotto le azioni richieste sono link testuali semplici:

- `Modifica`;
- `Elimina`.

## 2. Confine dell'evidence recuperata

Lo snapshot docente recuperato è:

`20260908_prodottiadmin.zip`

SHA-256:

`f5245d47cd04938b846b0303f625f4948714ec38adfa3159fda0a3120a72282f`

L'archivio contiene dieci file PHP:

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

Lo snapshot mostra:

- paginazione dinamica;
- form amministrativo;
- autori caricati dinamicamente da MySQL;
- opzioni genere statiche `1`, `2`, `3`;
- invio POST a `salva.php`;
- lettura di `brani.prezzo`.

Lo snapshot recuperato si ferma però al **confine POST**: non dimostra `INSERT`, `UPDATE` o `DELETE`.

Contiene inoltre un link a `dettaglioprodotto.php`, ma il file non è presente nell'archivio recuperato.

Questa distinzione resta importante: lo snapshot documenta il punto intermedio osservato; il requisito finale della lezione richiede invece CRUD completo.

## 3. Contratto dello schema

Il materiale SQL del corso corrobora:

`prezzo DECIMAL(6,2) NOT NULL DEFAULT 0.99`

Lo schema locale di `brani` persiste:

- `id`;
- `titolo`;
- `autore_id`;
- `genere_id`;
- `durata_minuti`;
- `anno`;
- `prezzo`.

Non viene aggiunta una colonna `brani.descrizione`: il form recuperato contiene un campo `descrizione`, ma l'evidence disponibile non dimostra una colonna corrispondente nel database.

## 4. Implementazione locale finale

La lezione è implementata in:

`php-lab/exercises/lesson-04/`

Il database locale resta isolato come:

`php4_shop_lab`

La fixture deterministica contiene:

- 4 autori;
- 3 generi;
- 15 brani;
- 2 brani con genere `NULL`;
- prezzi deterministici.

Il catalogo mantiene 12 record per pagina.

### Create

`adminprodotti.php` espone il form di creazione e `salva.php` esegue un `INSERT` parametrizzato.

Dopo la creazione viene eseguito un redirect verso il dettaglio del nuovo prodotto.

### Read

`prodotti.php` mostra il catalogo paginato e filtrabile.

`dettaglioprodotto.php` legge un singolo prodotto per `id` tramite prepared statement.

### Update

Il link testuale `Modifica` nella card apre `modificaprodotto.php`.

Il form viene precompilato con i dati esistenti e `aggiorna.php` esegue un `UPDATE` parametrizzato.

### Delete

Il link testuale `Elimina` nella card apre `eliminaprodotto.php`.

Il GET mostra soltanto la conferma; la cancellazione effettiva avviene con POST tramite `DELETE FROM brani WHERE id = :id`.

In questo modo un semplice caricamento URL non elimina dati.

## 5. Validazione e sicurezza

L'implementazione finale usa:

- PDO;
- prepared statements per `INSERT`, `SELECT` singolo, `UPDATE` e `DELETE`;
- binding intero esplicito dove appropriato;
- validazione server-side;
- verifica dell'esistenza di autore e genere;
- genere nullable;
- prezzo numerico non negativo;
- escaping HTML nell'output;
- credenziali database fornite a runtime;
- redirect dopo Create e Update.

Poiché il requisito finale comprende scritture, l'identità applicativa CRUD necessita di:

`SELECT, INSERT, UPDATE, DELETE`

La precedente configurazione SELECT-only resta significativa soltanto come evidence del checkpoint pre-CRUD.

## 6. Paginazione

La riproduzione usa una query parametrizzata `COUNT(*)` per determinare il numero totale di record.

`LIMIT` e `OFFSET` vengono associati come interi e la pagina richiesta viene normalizzata ad almeno `1`.

Questo mantiene il comportamento osservabile della lezione evitando di recuperare tutte le righe solo per contarle.

## 7. Verifica end-to-end

La road test isolata è:

`php-lab/exercises/lesson-04/road-test-crud.sh`

Crea un database e un utente MySQL temporanei, importa schema e fixture, avvia il server PHP locale e verifica realmente l'intero ciclo.

Esito verificato:

```text
CREATE=PASS
READ_DETAIL=PASS
UPDATE=PASS
DELETE=PASS
PHP_SYNTAX=PASS
PHP_SERVER_ERROR_GATE=PASS
PHP_4_CREATE=PASS
PHP_4_READ=PASS
PHP_4_UPDATE=PASS
PHP_4_DELETE=PASS
PHP_4_CRUD=COMPLETE
PHP_4_END_TO_END_RUNTIME=PASS
```

## 8. Lesson Learned

### 1. CRUD descrive quattro capacità distinte

Create, Read, Update e Delete devono essere implementate e verificate separatamente. La presenza di un form non implica persistenza.

### 2. POST e persistenza non sono la stessa cosa

Ricevere `$_POST` significa soltanto ricevere dati. Persistenza significa eseguire una mutation SQL verificabile.

### 3. Ogni mutation deve essere parametrizzata

I valori provenienti dall'utente non vengono concatenati direttamente nelle query SQL.

### 4. UPDATE richiede prima l'identificazione del record

Per modificare un prodotto occorre caricare il record corrente, precompilare il form e aggiornare esattamente l'`id` richiesto.

### 5. DELETE non dovrebbe essere una mutation GET

Il link `Elimina` può essere un semplice link testuale, ma deve condurre a una conferma. La cancellazione reale viene eseguita via POST.

### 6. Vincoli del database e validazione applicativa si completano

L'applicazione verifica autore, genere, prezzo e valori principali prima della mutation; il database conserva i propri vincoli strutturali.

### 7. Il minimo privilegio segue le capacità reali

Una versione read-only richiede solo `SELECT`; una versione CRUD necessita anche di `INSERT`, `UPDATE` e `DELETE`.

### 8. Prepared statements ed escaping HTML proteggono confini diversi

Le prepared statements proteggono il confine SQL. `htmlspecialchars()` protegge il rendering HTML. Sono entrambe necessarie.

### 9. Lo schema va dedotto dall'evidence, non dal nome dei campi del form

Il campo `descrizione` del form recuperato non basta a dimostrare `brani.descrizione`; per questo non è stato inventato nello schema.

### 10. Una ricostruzione può avere due livelli di verità

Il materiale recuperato documenta ciò che era osservabile nello snapshot. Il progetto finale documenta ciò che la lezione richiede realmente alla consegna. Tenere distinti questi due livelli evita di falsificare la provenienza.

## 9. Stato finale della lezione

```text
PHP_4_SNAPSHOT_RECONSTRUCTION=POST_BOUNDARY_VERIFIED
PHP_4_FINAL_REQUIREMENT=FULL_CRUD
PHP_4_CREATE=PASS
PHP_4_READ=PASS
PHP_4_UPDATE=PASS
PHP_4_DELETE=PASS
PHP_4_CRUD=COMPLETE
PHP_4_END_TO_END_RUNTIME=PASS
```

PHP 4 è quindi pronto come materiale di ripasso per la lezione: il confine storico dello snapshot resta documentato, ma lo stato operativo finale è CRUD completo.

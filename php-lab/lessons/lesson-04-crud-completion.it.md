# PHP — Lezione 4 — completamento CRUD

[English](lesson-04-crud-completion.md) | [Italiano](lesson-04-crud-completion.it.md)

Questo documento registra il completamento operativo richiesto per PHP 4 dopo la ricostruzione iniziale dello snapshot docente.

## Requisito corretto

PHP 4 deve includere gestione CRUD completa entro la consegna serale:

- **Create**: creazione di un nuovo record in `brani`;
- **Read**: catalogo e dettaglio del singolo prodotto;
- **Update**: modifica di un record esistente;
- **Delete**: eliminazione di un record esistente.

Lo snapshot recuperato rimane evidence storica del punto intermedio raggiunto dal docente, ma non rappresenta più il requisito operativo finale della lezione.

## Interfaccia

Ogni card prodotto espone link testuali semplici:

- `Dettaglio`;
- `Modifica`;
- `Elimina`.

`Modifica` apre il form di aggiornamento. `Elimina` apre una pagina di conferma; la cancellazione effettiva avviene tramite POST e non tramite GET.

## Persistenza

Il CRUD persiste esclusivamente colonne provate dallo schema locale:

- `titolo`;
- `autore_id`;
- `genere_id`;
- `durata_minuti`;
- `anno`;
- `prezzo`.

Non viene introdotta una colonna `descrizione`, perché lo schema PHP 4 disponibile non la dimostra.

## Sicurezza e correttezza

- PDO con prepared statements;
- validazione server-side degli input principali;
- verifica dell'esistenza di autore e genere;
- redirect dopo Create e Update;
- Delete confermata e inviata via POST;
- escaping HTML nel rendering;
- credenziale database esclusivamente a runtime.

L'identità applicativa CRUD necessita dei privilegi:

`SELECT, INSERT, UPDATE, DELETE`

sul database PHP 4 isolato.

## Gate finale

La road test isolata `php-lab/exercises/lesson-04/road-test-crud.sh` deve produrre:

```text
PHP_4_CREATE=PASS
PHP_4_READ=PASS
PHP_4_UPDATE=PASS
PHP_4_DELETE=PASS
PHP_4_CRUD=COMPLETE
PHP_4_END_TO_END_RUNTIME=PASS
```

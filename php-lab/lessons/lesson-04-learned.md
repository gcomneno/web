# PHP — Lesson 4
## Dynamic pagination, product administration, and complete CRUD

[English](lesson-04-learned.md) | [Italiano](lesson-04-learned.it.md)

This document records both the recovered evidence for the fourth PHP lesson and the operational completion required for the lesson's final delivery.

## 1. Final lesson objective

PHP 4 brings the database-backed catalog from PHP 3 to complete product management:

- a `prezzo` field in the database;
- dynamic pagination;
- an administration form;
- **Create**: insert new products;
- **Read**: catalog and single-product detail;
- **Update**: modify existing products;
- **Delete**: remove existing products.

Each product card exposes the required simple text actions:

- `Modifica`;
- `Elimina`.

## 2. Recovered evidence boundary

The recovered teacher snapshot is:

`20260908_prodottiadmin.zip`

SHA-256:

`f5245d47cd04938b846b0303f625f4948714ec38adfa3159fda0a3120a72282f`

The archive contains ten PHP files:

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

The snapshot shows:

- dynamic pagination;
- an administration form;
- authors loaded dynamically from MySQL;
- static genre options `1`, `2`, `3`;
- POST submission to `salva.php`;
- reading `brani.prezzo`.

The recovered snapshot stops at the **POST boundary**: it does not establish `INSERT`, `UPDATE`, or `DELETE`.

It also contains a link to `dettaglioprodotto.php`, but that file is absent from the recovered archive.

This distinction matters: the snapshot documents the observed intermediate state, while the final lesson requirement is complete CRUD.

## 3. Schema contract

Course SQL material corroborates:

`prezzo DECIMAL(6,2) NOT NULL DEFAULT 0.99`

The local `brani` schema persists:

- `id`;
- `titolo`;
- `autore_id`;
- `genere_id`;
- `durata_minuti`;
- `anno`;
- `prezzo`.

No `brani.descrizione` column is added: the recovered form contains a `descrizione` field, but the available evidence does not establish a matching database column.

## 4. Final local implementation

The lesson implementation is under:

`php-lab/exercises/lesson-04/`

The local database remains isolated as:

`php4_shop_lab`

The deterministic fixture contains:

- 4 authors;
- 3 genres;
- 15 tracks;
- 2 tracks with a `NULL` genre;
- deterministic prices.

The catalog keeps 12 records per page.

### Create

`adminprodotti.php` exposes the creation form and `salva.php` performs a parameterized `INSERT`.

After creation, the application redirects to the new product detail.

### Read

`prodotti.php` renders the paginated, filterable catalog.

`dettaglioprodotto.php` reads one product by `id` through a prepared statement.

### Update

The text `Modifica` link on each card opens `modificaprodotto.php`.

The form is pre-filled with existing data and `aggiorna.php` performs a parameterized `UPDATE`.

### Delete

The text `Elimina` link on each card opens `eliminaprodotto.php`.

GET only shows the confirmation page; the actual deletion is performed with POST through `DELETE FROM brani WHERE id = :id`.

Therefore, simply loading a URL does not delete data.

## 5. Validation and safety

The final implementation uses:

- PDO;
- prepared statements for `INSERT`, single-record `SELECT`, `UPDATE`, and `DELETE`;
- explicit integer binding where appropriate;
- server-side validation;
- author and genre existence checks;
- nullable genre;
- non-negative numeric price;
- HTML escaping during rendering;
- database credentials supplied at runtime;
- redirects after Create and Update.

Because the final requirement includes writes, the CRUD application identity needs:

`SELECT, INSERT, UPDATE, DELETE`

The earlier SELECT-only configuration remains meaningful only as evidence for the pre-CRUD checkpoint.

## 6. Pagination

The reproduction uses a parameterized `COUNT(*)` query to determine the total record count.

`LIMIT` and `OFFSET` are bound as integers and the requested page is normalized to at least `1`.

This preserves the observable lesson behavior without fetching all rows merely to count them.

## 7. End-to-end verification

The isolated road test is:

`php-lab/exercises/lesson-04/road-test-crud.sh`

It creates a temporary MySQL database and user, imports schema and fixture data, starts the local PHP server, and exercises the complete cycle for real.

Verified result:

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

### 1. CRUD describes four distinct capabilities

Create, Read, Update, and Delete must be implemented and verified separately. The presence of a form does not imply persistence.

### 2. POST reception and persistence are not the same thing

Receiving `$_POST` only means receiving data. Persistence means executing a verifiable SQL mutation.

### 3. Every mutation must be parameterized

User-provided values are never concatenated directly into SQL queries.

### 4. UPDATE first requires identifying the record

To modify a product, the application must load the current record, pre-fill the form, and update exactly the requested `id`.

### 5. DELETE should not be a GET mutation

`Elimina` may be a simple text link, but it should lead to confirmation. The actual deletion is executed via POST.

### 6. Database constraints and application validation complement each other

The application validates author, genre, price, and primary inputs before mutation; the database retains its structural constraints.

### 7. Least privilege follows real capabilities

A read-only version needs only `SELECT`; a CRUD version also requires `INSERT`, `UPDATE`, and `DELETE`.

### 8. Prepared statements and HTML escaping protect different boundaries

Prepared statements protect the SQL boundary. `htmlspecialchars()` protects HTML rendering. Both remain necessary.

### 9. Schema must be inferred from evidence, not from form field names

The recovered `descrizione` form field is not sufficient to establish `brani.descrizione`; therefore that column was not invented.

### 10. Reconstruction can have two levels of truth

Recovered material documents what was observable in the snapshot. The final project documents what the lesson actually requires at delivery. Keeping these levels separate avoids falsifying provenance.

## 9. Final lesson state

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

PHP 4 is therefore ready as study material: the historical snapshot boundary remains documented, while the final operational state is complete CRUD.

# PHP — Lesson 4
## Dynamic pagination, administration form, and the POST boundary

[English](lesson-04-learned.md) | [Italiano](lesson-04-learned.it.md)

This document records the reconstruction and independent local reproduction of the fourth recovered PHP lesson.

## 1. Lesson objective

PHP 4 extends the database-backed catalog from PHP 3 with:

- a price field in the catalog database;
- dynamic pagination;
- an administration form for product data;
- dynamic author options loaded from MySQL;
- static genre options;
- a POST endpoint receiving the submitted product fields.

The recovered snapshot reaches the POST boundary, but it does not implement database persistence.

## 2. Evidence boundary

The recovered teacher snapshot is:

`20260908_prodottiadmin.zip`

SHA-256:

`f5245d47cd04938b846b0303f625f4948714ec38adfa3159fda0a3120a72282f`

The archive passed its ZIP integrity check and contains ten PHP files:

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

The snapshot shows a dynamic pager. Its implementation selects all matching `brani`, calls `fetchAll()`, counts the returned rows, and generates page links by repeatedly subtracting the page size.

The administration form submits these fields to `salva.php` using POST:

- `titolo`
- `autore`
- `genere`
- `durata`
- `anno`
- `prezzo`
- `descrizione`

Author options are loaded dynamically from `autori`. Genre options `1`, `2`, and `3` are hard-coded.

`salva.php` contains only a commented example of the received `$_POST` data. No `INSERT`, `UPDATE`, or `DELETE` is implemented there.

The snapshot also contains a link to `dettaglioprodotto.php`, but that file is not present in the recovered archive. Product-detail behavior is therefore not part of this reproduction.

The snapshot reads `brani.prezzo`. Course SQL material independently corroborates the schema change:

`prezzo DECIMAL(6,2) NOT NULL DEFAULT 0.99`

The PHP 4 snapshot does not prove the existence of a `brani.descrizione` column. The form contains a `descrizione` field, but the catalog query aliases `autori.nome` as `descrizione`, and `salva.php` does not persist the submitted value.

The original PHP 4 database dump and original dataset were not recovered.

## 3. Local reproduction

The independent reproduction is under:

`php-lab/exercises/lesson-04/`

It contains ten PHP files plus deterministic schema and seed fixtures.

The PHP 3 schema contract is extended only with the corroborated column:

`prezzo DECIMAL(6,2) NOT NULL DEFAULT 0.99`

No `brani.descrizione` column is added because that column is not proven by the recovered PHP 4 evidence.

The local database is deliberately isolated as:

`php4_shop_lab`

The application identity is:

`php4_lab@localhost`

and has SELECT-only access.

The deterministic fixture contains:

- 4 authors;
- 3 genres;
- 15 tracks;
- two tracks with a NULL genre;
- deterministic prices for exercising price rendering.

The catalog keeps 12 records per page.

The administration form reproduces the observed boundary:

- seven submitted fields;
- authors read dynamically from the database;
- three static genre options;
- POST submission to `salva.php`;
- no database persistence.

## 4. Intentional implementation differences

### Efficient pagination count

The recovered snapshot performs:

`SELECT * -> fetchAll() -> count()`

The local reproduction instead performs a parameterized `COUNT(*)` query.

This changes the implementation strategy, not the observable pagination contract.

### Explicit integer binding

Pagination `LIMIT` and `OFFSET` values are explicitly bound with `PDO::PARAM_INT`.

### Page normalization

The requested page is normalized to a minimum value of `1`.

### Escaped HTML output

Reflected request values, database-backed text, author names, query strings, and POST values are escaped before HTML rendering.

SQL parameterization and HTML escaping are treated as separate security boundaries.

### Runtime database credential

No database password is committed.

`PHP4_DB_PASSWORD` supplies the local runtime credential.

### Least privilege and no persistence

The application identity remains SELECT-only.

This is deliberate: the recovered PHP 4 snapshot contains an administration form and a POST endpoint, but no database write operation.

The reproduction therefore does not invent an `INSERT` capability that the recovered artifact does not demonstrate.

## 5. Verification

Runtime verification used:

- PHP 8.3.6;
- PDO with `pdo_mysql`;
- MySQL 8.0.46;
- the isolated `php4_shop_lab` database.

Database verification confirmed:

- `brani.prezzo` is `DECIMAL(6,2) NOT NULL DEFAULT 0.99`;
- 4 authors;
- 3 genres;
- 15 tracks;
- the application identity has SELECT-only access;
- an attempted application write is denied.

Catalog HTTP verification confirmed:

- page 1 renders 12 records from 15;
- page 2 renders the remaining 3;
- dynamic pagination reports 15 records;
- filtering for `Road` returns `Dream Road` and `Open Road`;
- a NULL genre renders as `Non specificato`;
- negative page input is normalized to page 1;
- prices are read from MySQL and rendered;
- hostile reflected markup is HTML-escaped;
- raw injected script markup is not rendered.

Administration HTTP verification confirmed:

- `adminprodotti.php` responds successfully;
- all seven expected fields are present;
- all 4 fixture authors are rendered dynamically;
- the 3 observed genre options remain static.

POST verification confirmed:

- all submitted values reach `salva.php`;
- hostile POST markup is HTML-escaped;
- raw injected script markup is not rendered;
- `brani` contains 15 rows before the POST;
- `brani` still contains 15 rows after the POST;
- the application identity cannot write to the database.

All ten PHP files pass `php -l`.

The PHP development server was stopped after verification.

`PHP_4_END_TO_END_RUNTIME=PASS`

## 6. Lesson Learned

### 1. PHP 4 extends a data-driven catalog without yet completing persistence

A form that submits product data is not evidence that the application can create database records.

### 2. POST reception and database persistence are separate boundaries

Receiving `$_POST` values and executing an `INSERT` are distinct application capabilities and must be verified independently.

### 3. Dynamic pagination depends on both selection and total-count information

The catalog needs the current page of records and enough information to determine how many pages exist.

### 4. Equivalent behavior can have different query costs

Fetching every matching row merely to count it works on a small dataset, while `COUNT(*)` expresses the counting operation directly.

### 5. Form options can combine database-backed and static data

The recovered form demonstrates this explicitly: authors come from MySQL while genres remain hard-coded.

### 6. Schema claims require evidence

The presence of a form field named `descrizione` does not prove that a matching database column exists.

### 7. Later material must not be projected backward

A later mini-ecommerce model contains a description concept, but that does not establish that `brani.descrizione` belonged to this PHP 4 snapshot.

### 8. Least privilege should follow implemented capabilities

Because this reproduction does not persist POST data, its application identity does not need write privileges.

### 9. Output escaping remains necessary on both GET and POST paths

Prepared SQL statements do not protect HTML output. Request and database values must still be encoded for their output context.

### 10. Reconstruction preserves incomplete boundaries

A recovered lesson can legitimately end at an intermediate stage. Reproduction should verify that stage rather than silently completing the application.

## 7. Final lesson state

`PHP_4_RECONSTRUCTION=RECONSTRUCTED_TO_AVAILABLE_SNAPSHOT`

`PHP_4_LOCAL_REPRODUCTION=PASS`

`PHP_4_END_TO_END_RUNTIME=PASS`

The recovered snapshot reaches the POST boundary but does not implement database persistence.

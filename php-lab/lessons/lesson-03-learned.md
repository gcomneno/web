# PHP — Lesson 3
## Database-backed catalog with PDO, filtering, and pagination

[English](lesson-03-learned.md) | [Italiano](lesson-03-learned.it.md)

## 1. Lesson objective

Reconstruct the third recovered PHP lesson from the available teacher snapshot and course database evidence, then independently reproduce and verify the transition from the static PHP 2 catalog to database-backed rendering.

The recovered teacher snapshot is evidence of the available lesson implementation. The files under `php-lab/exercises/lesson-03/` are our own reproduction and verification.

## 2. Evidence boundary

The recovered teacher snapshot contained:

- `prodotti.php`;
- `include/data.php`;
- `include/db.php`;
- `include/footer.php`;
- `include/header.php`;
- `include/menusx.php`;
- `include/prodotto.php`.

The snapshot showed:

- page composition through PHP includes;
- a PDO connection to a MySQL database named `shop`;
- exception-based PDO error handling;
- associative-array fetch mode;
- native prepared statements;
- a `GET` filter named `filtro`;
- a `GET` page parameter named `pagina`;
- twelve records per page;
- offset calculation from the requested page;
- a prepared `SELECT`;
- joins between `brani`, `autori`, and `generi`;
- title filtering with `LIKE`;
- ordering by title;
- `LIMIT` and `OFFSET`;
- iteration over the resulting products with `foreach`;
- dynamic product rendering;
- static pagination links for pages `1`, `2`, and `3`.

The normalized database contract was corroborated by recovered course material using:

- `autori(autore_id, nome)`;
- `generi(genere_id, nome)`;
- `brani(id, titolo, autore_id, genere_id, durata_minuti, anno)`.

The original normalized `CREATE TABLE` dump and original dataset were not recovered.

A different earlier course artifact, `musica.sql`, used a different schema and was therefore not treated as the PHP 3 database.

The local database fixture is consequently a deterministic reconstruction for verification, not a recovered copy of the teacher database.

## 3. Local reproduction

The reproduction contains:

1. `database/schema.sql` — reconstructed normalized schema for `autori`, `generi`, and `brani`;
2. `database/seed.sql` — deterministic local dataset with four authors, three genres, and fifteen tracks;
3. `include/db.php` — PDO/MySQL connection setup;
4. `include/data.php` — request normalization and prepared catalog query;
5. `include/header.php` and `include/footer.php` — shared page structure;
6. `include/menusx.php` — `GET` title-filter form;
7. `include/prodotto.php` — rendering of one database row;
8. `prodotti.php` — page composition, `foreach` rendering, and static pagination links.

The database was deliberately named `php3_shop_lab` rather than `shop` to preserve the boundary between teacher evidence and our implementation.

A dedicated local application identity, `php3_lab@localhost`, was granted `SELECT` access only.

The fixture contains fifteen tracks so that twelve-record pagination produces twelve records on page 1 and three records on page 2. It also contains tracks with a `NULL` genre so that the `LEFT JOIN` behavior can be exercised.

`durata_minuti` preserves the course's `DECIMAL(4,2)` representation. This is evidence-aligned for the exercise but should not be interpreted as a general-purpose duration model.

## 4. Intentional implementation differences

The reproduction preserves the lesson concepts without reproducing avoidable weaknesses observed in the available snapshot.

### Integer binding for pagination

The teacher snapshot supplied pagination values through the execution parameter array.

The local reproduction explicitly binds `LIMIT` and `OFFSET` with `PDO::PARAM_INT`.

### Escaped HTML output

The recovered filter form reflected its value without `htmlspecialchars()`.

The local reproduction escapes reflected request data and database-backed product values with `htmlspecialchars()`.

### Runtime database credential

No database password is stored in the lesson files.

`PHP3_DB_PASSWORD` must be supplied through the runtime environment. Database host, database name, and application user also have explicit local defaults suitable for the isolated lesson database.

These differences belong to our implementation. They do not rewrite what was observed in the teacher snapshot.

## 5. Verification

The MySQL/PDO readiness gate established:

- PHP 8.3.6;
- MySQL 8.0.46;
- `pdo_mysql` available;
- PDO drivers `mysql` and `sqlite`;
- MySQL service active.

The reconstructed schema was imported into `php3_shop_lab`.

MySQL materialized:

- `autori`;
- `generi`;
- `brani`;
- the foreign key from `brani.autore_id` to `autori.autore_id`;
- the foreign key from `brani.genere_id` to `generi.genere_id`.

The deterministic seed produced:

- `AUTORI_ROWS=4`;
- `GENERI_ROWS=3`;
- `BRANI_ROWS=15`.

The `php3_lab@localhost` application identity could read the catalog and could not perform an `INSERT`.

The resulting privilege gate was:

- `PHP_3_APP_WRITE_GATE=PASS`.

The data layer was executed directly against MySQL.

Observed results included:

- page 1: `PAGE=1`, `OFFSET=0`, `RESULTS=12`;
- page 2: `PAGE=2`, `OFFSET=12`, `RESULTS=3`;
- filter `Road`: `Dream Road` and `Open Road`;
- invalid page `-8`: normalized to page `1`, offset `0`.

All seven PHP files passed `php -l`.

The complete lesson was then served through the PHP development server on `127.0.0.1:18083`.

Real HTTP requests verified:

- twelve rendered records on page 1;
- three rendered records on page 2;
- two records for the `Road` title filter;
- `Hidden Moon` rendered through the `LEFT JOIN` with a missing genre;
- reflected hostile markup was HTML-escaped;
- the raw injected script fragment was not rendered as markup.

The resulting gates included:

- `PHP_3_HTTP_ESCAPE=PASS`;
- `PHP_3_HTTP_RAW_SCRIPT=PASS`;
- `PHP_3_HTTP_CLEANUP=PASS`;
- `PHP_3_SECRET_GATE=PASS`;
- `PHP_3_READ_ONLY_BOUNDARY=PASS`;
- `PHP_3_END_TO_END_RUNTIME=PASS`.

The development server was stopped after verification.

## 6. Lesson Learned

### 1. PHP 3 turns the static catalog into a data-driven page

PHP 2 established the catalog structure. PHP 3 replaces repeated static product content with records selected from MySQL and rendered through PHP.

### 2. Includes separate page responsibilities

Database setup, data retrieval, page structure, filtering, and product rendering can live in separate files while `prodotti.php` composes the final response.

### 3. PDO provides a controlled database boundary

The application connects through PDO with exception-based errors, associative fetches, and native prepared statements rather than embedding query results directly into page logic.

### 4. Request parameters must be normalized before they affect queries

The title filter has a defined default and the page number is constrained to a minimum of one before its offset is calculated.

### 5. Pagination is both presentation and data selection

Unlike PHP 2's purely static pagination, PHP 3 uses `LIMIT` and `OFFSET` to select a page-sized subset of database rows. The visible page links remain intentionally static at this lesson boundary.

### 6. A LEFT JOIN preserves rows without optional related data

Tracks without a genre remain in the catalog because genres are joined with `LEFT JOIN`. The reproduction verifies this with explicit `NULL` fixture cases.

### 7. Output escaping is separate from SQL parameterization

Prepared statements protect the query boundary. `htmlspecialchars()` protects the HTML rendering boundary. Both are required for different reasons.

### 8. Reconstruction must distinguish recovered data from replacement fixtures

The normalized schema contract was recoverable from course evidence, but the original DDL and dataset were not. A deterministic fixture makes the lesson executable without falsely presenting reconstructed data as original evidence.

### 9. Least privilege can be verified, not merely claimed

The lesson application identity has `SELECT` access only, and an attempted write was used as a negative gate to confirm that boundary.

### 10. Reproduction is complete only after the full path runs

The lesson was verified from real HTTP input through PDO/MySQL and back to rendered HTML. Individual syntax checks alone would not establish the same result.

## 7. Final lesson state

PHP 3 is reconstructed to the available teacher snapshot and database evidence, independently reproduced against MySQL, served, and verified locally.

The original normalized DDL and original dataset remain unrecovered and are not represented as recovered artifacts.

`PHP_3_LOCAL_REPRODUCTION=PASS`

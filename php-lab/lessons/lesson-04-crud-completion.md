# PHP — Lesson 4 — CRUD completion

[English](lesson-04-crud-completion.md) | [Italiano](lesson-04-crud-completion.it.md)

This document records the operational completion required for PHP 4 after the initial reconstruction of the recovered teacher snapshot.

## Correct requirement

PHP 4 must include complete CRUD management for the evening delivery:

- **Create**: insert a new record into `brani`;
- **Read**: catalog and single-product detail;
- **Update**: modify an existing record;
- **Delete**: remove an existing record.

The recovered snapshot remains historical evidence of the intermediate point reached by the teacher, but it no longer represents the final operational requirement of the lesson.

## Interface

Each product card exposes simple text links:

- `Dettaglio`;
- `Modifica`;
- `Elimina`.

`Modifica` opens the update form. `Elimina` opens a confirmation page; the actual deletion is performed through POST rather than GET.

## Persistence

CRUD persists only columns established by the local schema:

- `titolo`;
- `autore_id`;
- `genere_id`;
- `durata_minuti`;
- `anno`;
- `prezzo`.

No `descrizione` column is introduced because the available PHP 4 schema does not establish it.

## Safety and correctness

- PDO with prepared statements;
- server-side validation of primary inputs;
- author and genre existence checks;
- redirect after Create and Update;
- confirmed Delete submitted through POST;
- HTML escaping during rendering;
- database credentials supplied only at runtime.

The CRUD application identity requires:

`SELECT, INSERT, UPDATE, DELETE`

on the isolated PHP 4 database.

## Final gate

The isolated road test `php-lab/exercises/lesson-04/road-test-crud.sh` must produce:

```text
PHP_4_CREATE=PASS
PHP_4_READ=PASS
PHP_4_UPDATE=PASS
PHP_4_DELETE=PASS
PHP_4_CRUD=COMPLETE
PHP_4_END_TO_END_RUNTIME=PASS
```

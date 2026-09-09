# PHP Lab

[English](README.md) | [Italiano](README.it.md)

Questo laboratorio ricostruisce e riproduce il modulo PHP del corso Sviluppatore Software Kleis.

Il materiale recuperato dal docente costituisce evidence del corso, non prova che un argomento sia stato appreso o riprodotto localmente.

## Workflow di recupero

source evidence -> reconstruct -> understand -> reproduce -> run -> verify -> document

## Checkpoint di recupero

| Lezione | Stato ricostruzione | Riproduzione locale |
| --- | --- | --- |
| PHP 1 | RECONSTRUCTED | REPRODUCED AND VERIFIED |
| PHP 2 | RECONSTRUCTED TO AVAILABLE ARTIFACT | REPRODUCED AND VERIFIED |
| PHP 3 | RECONSTRUCTED | NOT STARTED |
| PHP 4 | RECONSTRUCTED TO AVAILABLE SNAPSHOT | NOT STARTED |

## Lezioni

- [Lezione 1 — Fondamenti del linguaggio e prima pagina dinamica](lessons/lesson-01-learned.it.md)
- [Lezione 2 — Layout statico del catalogo come ponte verso il rendering dinamico](lessons/lesson-02-learned.it.md)

## Confine dell'evidence

HANDOUT CONTENT != EVIDENCE OF CLASSROOM COMPLETION

TEACHER SNAPSHOT != OUR IMPLEMENTATION

Solo il lavoro riprodotto e verificato localmente viene marcato come completato in questo laboratorio.

## Readiness del runtime

Baseline locale attuale:

- PHP 8.3.6
- PDO disponibile
- pdo_sqlite disponibile
- pdo_mysql attualmente non disponibile

Il supporto PDO per MySQL non è necessario per PHP 1. Diventa invece un gate di readiness prima di riprodurre le lezioni che utilizzano il database.

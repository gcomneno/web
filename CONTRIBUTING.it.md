# Contribuire

[English](CONTRIBUTING.md) | [Italiano](CONTRIBUTING.it.md)

I contributi devono mantenere il repository chiaro, riproducibile e accessibile sia in inglese sia in italiano.

## Lingua canonica e nomi dei file

L'inglese è la lingua canonica della documentazione pubblica scritta per lettori e contributori.

Il documento canonico inglese utilizza il nome di base:

- `README.md`
- `CONTRIBUTING.md`
- `lesson-01-learned.md`

La corrispondente traduzione italiana utilizza il suffisso `.it.md`:

- `README.it.md`
- `CONTRIBUTING.it.md`
- `lesson-01-learned.it.md`

Quando le due versioni non coincidono, il documento inglese definisce il significato tecnico previsto.

## Ambito della documentazione

La policy bilingue si applica alla documentazione pubblica scritta manualmente e destinata a lettori e contributori.

Include:

- README del repository e dei sottoprogetti
- policy di contribuzione
- glossari e riepiloghi
- lesson learned e altra documentazione didattica

Normalmente non include:

- documentazione di dipendenze o vendor
- file generati
- scaffolding del framework mantenuto invariato rispetto all'origine
- fixture e dati di test
- trascrizioni integrali, materiale importato, audio o video
- file destinati alle macchine come `robots.txt`

`laravel-lab/first-project/README.md` è attualmente considerato documentazione dello scaffolding del framework ed è escluso, salvo una sua riscrittura sostanziale specifica per questo repository.

## Requisiti delle coppie documentali

Ogni documento bilingue migrato deve avere sia una versione canonica inglese sia una traduzione italiana.

Ogni coppia deve conservare:

- la stessa gerarchia degli heading
- un significato tecnico equivalente
- gli stessi esempi di codice
- una navigazione interna equivalente
- link reciproci per la lingua vicino all'inizio
- link relativi validi in entrambi i file

Il testo non deve essere tradotto parola per parola, ma nessuna versione può omettere requisiti tecnici, avvisi o passaggi operativi.

## Migrazione progressiva

Durante la migrazione, i documenti esistenti disponibili soltanto in italiano possono conservare temporaneamente il nome attuale senza suffisso.

Il manifest registra i file temporanei in `legacy_unpaired_documents` e le esclusioni deliberate in `excluded_documents`. Ogni file Markdown pubblico deve appartenere a uno di questi elenchi oppure a una coppia canonica registrata; altrimenti la validazione fallisce.

La migrazione viene eseguita una coppia documentale alla volta:

1. rinominare con `git mv` il documento italiano esistente usando il suffisso `.it.md`
2. creare il documento canonico inglese con il nome originale
3. aggiungere i link reciproci per la lingua
4. allineare heading, esempi di codice e navigazione
5. registrare e validare la nuova coppia
6. revisionare il diff limitato alla documentazione

La nuova documentazione pubblica non deve introdurre ulteriori file storici disponibili soltanto in italiano.

## Aggiornamento della documentazione

Quando si modifica un documento già migrato:

1. aggiornare la versione canonica inglese
2. applicare la stessa modifica tecnica alla versione italiana
3. eseguire il validatore della documentazione bilingue
4. controllare che il diff non contenga modifiche estranee
5. includere entrambi i file nella stessa pull request

Identificatori del codice, comandi, percorsi, nomi di file, API e output letterali devono normalmente rimanere invariati nelle traduzioni.

## Validazione automatica

Eseguire il validatore della documentazione bilingue dalla radice del repository:

```bash
python3 scripts/check-bilingual-docs.py
```

Il validatore controlla le coppie documentali registrate, la gerarchia degli heading, i blocchi di codice, i riferimenti tecnici inline, i link reciproci per la lingua, le destinazioni dei file e degli anchor locali e la classificazione completa dei file Markdown pubblici.

## Checklist di revisione

Prima di richiedere la revisione, verificare che:

- [ ] esista il documento canonico inglese
- [ ] esista la traduzione italiana `.it.md`
- [ ] i link reciproci per la lingua funzionino
- [ ] i livelli degli heading siano sincronizzati
- [ ] esempi di codice e sequenze di comandi coincidano
- [ ] i link relativi siano validi
- [ ] entrambe le versioni descrivano lo stesso comportamento
- [ ] il diff contenga soltanto i file previsti
- [ ] i controlli automatici sulla documentazione siano superati

## Workflow delle pull request

Le migrazioni documentali devono utilizzare un branch dedicato e una Draft pull request.

Ogni migrazione deve essere abbastanza piccola da poter essere revisionata accuratamente. La pull request può passare a Ready for review soltanto dopo il superamento dei controlli bilingui e il confronto tra le due versioni.

Dopo il merge, sincronizzare il branch principale e rimuovere worktree e branch temporanei.

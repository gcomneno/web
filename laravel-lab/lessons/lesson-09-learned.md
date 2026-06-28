# Getting Started with Laravel — Lezione 09
## Database migration primer

Data laboratorio: 2026-06-18  
Corso: Getting Started with Laravel  
Episodio: 09 — Database migration primer  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è introdurre le **migration** Laravel.

Il docente prepara il terreno per i prossimi argomenti:

- migration
- model
- Eloquent
- tabelle database
- inserimento dati
- lettura dati

La lezione 09 isola il concetto di migration.

La lezione successiva passerà ai model.

Poi arriverà Eloquent.

Schema mentale:

```text
Migration → struttura della tabella
Model     → classe PHP collegata alla tabella
Eloquent  → ORM per leggere/scrivere dati
```

Per ora ci concentriamo solo sul primo pezzo:

> come Laravel costruisce e modifica la struttura del database.

---

## 2. Perché quasi tutte le app usano un database

Il docente parte da un punto pratico:

> quasi ogni applicazione tocca un database in qualche modo.

Una web app tipica deve:

- salvare utenti
- salvare contenuti
- leggere dati
- modificare dati
- cancellare dati
- cercare informazioni

Quindi prima o poi bisogna capire come Laravel organizza il database.

---

## 3. Cos’è una migration

Una migration è un file PHP che descrive una modifica allo schema del database.

Esempi di modifiche:

- creare una tabella
- aggiungere una colonna
- eliminare una colonna
- modificare una tabella esistente
- creare indici
- creare vincoli

Una migration non è un dato.

Una migration descrive la **struttura** del database.

Esempio:

```text
crea la tabella projects
aggiungi una colonna name
aggiungi created_at e updated_at
```

---

## 4. Migration già presenti nel progetto Laravel

Laravel crea già alcune migration iniziali.

Nel progetto si trovano in:

```text
database/migrations
```

Esempi tipici:

```text
0001_01_01_000000_create_users_table.php
0001_01_01_000001_create_cache_table.php
0001_01_01_000002_create_jobs_table.php
```

Queste migration creano tabelle iniziali come:

- `users`
- `cache`
- `jobs`

Nel nostro progetto, dopo il ripristino e il comando:

```bash
php artisan migrate
```

sono state eseguite proprio queste migration iniziali.

---

## 5. Perché usare migration invece di modificare il database a mano

La domanda naturale è:

> perché non apriamo il database e creiamo le tabelle manualmente?

Si può fare, ma non è il modo giusto in un progetto Laravel.

Le migration servono come **traccia storica** dello schema.

Il docente usa l’idea della “breadcrumb trail”:

> una scia di briciole che racconta come il database è stato costruito.

Vantaggi:

- il database è ricostruibile da zero
- lo schema è versionato nel repository
- altri sviluppatori possono ottenere lo stesso schema
- la produzione può essere aggiornata con gli stessi passaggi
- non bisogna ricordarsi modifiche fatte manualmente
- Git può tracciare l’evoluzione dello schema

Senza migration, il database diventa una creatura locale non riproducibile.

Con le migration, invece, il progetto sa ricostruire la sua struttura.

---

## 6. Migration e ambienti diversi

Le migration sono utili perché permettono di applicare lo stesso schema in ambienti diversi.

Esempi di ambienti:

- locale
- test
- staging
- produzione

Scenario:

1. creo una migration in locale
2. la commetto nel repository
3. la porto su un altro ambiente
4. eseguo `php artisan migrate`
5. quell’ambiente ottiene la stessa modifica al database

Questo evita di ricreare a mano tabelle e colonne.

---

## 7. La tabella `migrations`

Laravel mantiene una tabella speciale chiamata:

```text
migrations
```

Questa tabella registra quali migration sono già state eseguite.

Per questo, se lanci:

```bash
php artisan migrate
```

Laravel non riesegue ogni volta tutte le migration.

Controlla la tabella `migrations` e applica solo quelle non ancora eseguite.

Esempio:

```text
Migration A già eseguita → non viene rilanciata
Migration B già eseguita → non viene rilanciata
Migration C nuova        → viene eseguita
```

Questo è fondamentale.

Senza questo controllo, Laravel proverebbe a ricreare tabelle già esistenti.

---

## 8. Comando per creare una migration

Si usa Artisan:

```bash
php artisan make:migration nome_della_migration
```

Esempio generico:

```bash
php artisan make:migration test
```

Laravel crea un file dentro:

```text
database/migrations
```

Il nome del file contiene un timestamp.

Esempio:

```text
2026_06_18_123456_test.php
```

Il timestamp è importante perché determina l’ordine di esecuzione delle migration.

---

## 9. Perché il nome della migration è importante

Il docente mostra che un nome generico come:

```bash
php artisan make:migration test
```

funziona, ma produce una migration quasi vuota.

Laravel non può capire cosa vogliamo fare.

Se invece usiamo un nome descrittivo, Laravel prova a generare codice di partenza più utile.

Esempio:

```bash
php artisan make:migration create_projects_table
```

Laravel capisce che vogliamo creare una tabella chiamata:

```text
projects
```

e prepara già una struttura con:

```php
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->timestamps();
});
```

Regola pratica:

> dai nomi chiari alle migration, perché Laravel può aiutarti con il boilerplate.

---

## 10. Struttura base di una migration

Una migration ha due metodi principali:

```php
public function up(): void
{
    //
}

public function down(): void
{
    //
}
```

### `up()`

Contiene ciò che deve succedere quando applichiamo la migration.

Esempi:

- creare una tabella
- aggiungere una colonna
- creare un indice

### `down()`

Contiene l’operazione inversa.

Esempi:

- eliminare una tabella
- rimuovere una colonna
- rimuovere un indice

---

## 11. Creare una tabella

Per creare una tabella, Laravel usa:

```php
Schema::create(...)
```

Esempio:

```php
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

Questo crea una tabella `projects` con:

- `id`
- `name`
- `created_at`
- `updated_at`

---

## 12. `$table->id()`

Questa istruzione crea una colonna identificativa primaria.

```php
$table->id();
```

Di solito corrisponde a un campo:

```text
id
```

con comportamento:

- chiave primaria
- valore numerico
- auto-increment

È lo standard per identificare ogni riga della tabella.

---

## 13. `$table->string(...)`

Questa istruzione crea una colonna testuale.

Esempio:

```php
$table->string('name');
```

Nel database corrisponde di solito a qualcosa come `VARCHAR`.

Serve per campi come:

- nome
- titolo
- email
- slug

---

## 14. `$table->timestamps()`

Questa istruzione crea due colonne:

```text
created_at
updated_at
```

Esempio:

```php
$table->timestamps();
```

Laravel usa spesso queste colonne per sapere:

- quando una riga è stata creata
- quando una riga è stata aggiornata

Il docente dice che più avanti vedremo perché sono utili.

---

## 15. Colonne uniche

Laravel permette di rendere una colonna unica.

Esempio:

```php
$table->string('slug')->unique();
```

Questo significa:

> due righe non possono avere lo stesso valore di `slug`.

Nel caso della tabella `users`, l’email è un esempio classico di valore unico.

Due utenti non dovrebbero potersi registrare con la stessa email.

---

## 16. Eseguire le migration

Per applicare le migration:

```bash
php artisan migrate
```

Laravel cerca le migration non ancora eseguite e le applica.

Se abbiamo appena creato:

```text
create_projects_table
```

e lanciamo:

```bash
php artisan migrate
```

Laravel crea la tabella `projects`.

---

## 17. Rollback

Per annullare l’ultimo gruppo di migration eseguite:

```bash
php artisan migrate:rollback
```

Il docente spiega che `rollback` annulla l’ultimo **batch**.

Se nell’ultimo batch c’era una sola migration, annulla quella.

Se nell’ultimo batch c’erano più migration, annulla tutte quelle del batch.

Nel laboratorio, spesso lavorando una migration alla volta, il rollback rimuove solo l’ultima modifica.

---

## 18. Attenzione alle `down()` migration

Il docente dà un avvertimento importante.

Una `down()` migration può cancellare dati.

Esempio:

```php
Schema::dropIfExists('projects');
```

Se la tabella `projects` contiene dati reali, un rollback che la droppa elimina tutto.

Per questo il docente dice di non amare le migration inverse in certi contesti, specialmente in produzione.

Idea chiave:

> rollback e `down()` sono utili, ma vanno usati con consapevolezza.

Nel laboratorio locale possiamo sperimentare.

In produzione bisogna essere molto più prudenti.

---

## 19. Primo scenario: migration appena creata in locale

Scenario:

1. creo `projects`
2. eseguo `php artisan migrate`
3. mi accorgo subito che manca una colonna
4. non ci sono dati importanti

In questo caso posso:

```bash
php artisan migrate:rollback
```

modificare la migration originale, poi rilanciare:

```bash
php artisan migrate
```

È una forma di lavoro iterativo locale.

Esempio:

prima:

```php
$table->string('name');
```

poi aggiungo:

```php
$table->string('slug')->unique();
```

e rilancio la migration.

---

## 20. Secondo scenario: tabella già in uso

Scenario diverso:

1. la tabella `projects` esiste già
2. è già stata migrata
3. magari è già in produzione
4. contiene dati
5. voglio aggiungere una colonna `slug`

In questo caso non modifico la vecchia migration.

Creo una nuova migration:

```bash
php artisan make:migration add_slug_to_projects_table
```

Laravel capisce che voglio modificare la tabella `projects`.

Genera una struttura con:

```php
Schema::table('projects', function (Blueprint $table) {
    //
});
```

Poi aggiungo:

```php
$table->string('slug')->unique();
```

Questa è la strada corretta per modificare una tabella già esistente.

---

## 21. `Schema::create` vs `Schema::table`

Differenza importante.

### `Schema::create`

Si usa per creare una nuova tabella.

```php
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

### `Schema::table`

Si usa per modificare una tabella esistente.

```php
Schema::table('projects', function (Blueprint $table) {
    $table->string('slug')->unique();
});
```

Regola semplice:

```text
nuova tabella       → Schema::create
tabella già esiste  → Schema::table
```

---

## 22. Rimuovere una colonna nella `down()`

Quando aggiungiamo una colonna in `up()`:

```php
$table->string('slug')->unique();
```

l’operazione inversa in `down()` può essere:

```php
$table->dropColumn('slug');
```

Esempio completo:

```php
public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn('slug');
    });
}
```

Il docente lo mostra, ma ribadisce prudenza sull’uso delle migration inverse.

---

## 23. Campi e helper citati

La lezione cita o anticipa diversi tipi di colonne e helper:

```php
$table->id();
$table->string('name');
$table->timestamps();
$table->foreignId(...);
$table->date(...);
$table->time(...);
$table->timestamp(...);
$table->uuid(...);
$table->unique(...);
```

Non serve impararli tutti ora.

L’idea è che `Blueprint` offre metodi per descrivere quasi tutto ciò che normalmente faremmo nella struttura di una tabella.

---

## 24. Blueprint

Nelle migration compare spesso:

```php
use Illuminate\Database\Schema\Blueprint;
```

`Blueprint` rappresenta la struttura della tabella che stiamo creando o modificando.

Quando scriviamo:

```php
function (Blueprint $table) {
    $table->string('name');
}
```

stiamo dicendo:

> su questa tabella, aggiungi una colonna string chiamata `name`.

---

## 25. Migration e Git

Le migration sono file PHP.

Quindi vanno committate.

Il database locale invece no.

Nel nostro laboratorio:

### Da committare

```text
database/migrations/*.php
```

### Da non committare

```text
database/database.sqlite
.env
vendor/
node_modules/
```

Dopo il disastro della cartella cancellata e il ripristino da GitHub, abbiamo ricreato localmente:

- `.env`
- `APP_KEY`
- `database/database.sqlite`
- `vendor/`

Questi file/cartelle sono locali e non devono entrare nel repository.

---

## 26. Pratica della lezione

Nel progetto Laravel:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Creare migration:

```bash
php artisan make:migration create_projects_table
```

Aprire il file generato in:

```text
database/migrations
```

Contenuto atteso, da adattare al timestamp reale del file:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
```

Eseguire:

```bash
php artisan migrate
```

Verificare stato migration:

```bash
php artisan migrate:status
```

Rollback locale:

```bash
php artisan migrate:rollback
```

Rieseguire:

```bash
php artisan migrate
```

---

## 27. Variante: aggiungere `slug`

Se la tabella è appena nata e non contiene dati importanti, si può fare rollback, modificare la migration originale e rimigrare.

Esempio:

```php
$table->string('slug')->unique();
```

Se invece la tabella è già in uso, si crea una nuova migration:

```bash
php artisan make:migration add_slug_to_projects_table
```

Dentro `up()`:

```php
Schema::table('projects', function (Blueprint $table) {
    $table->string('slug')->unique();
});
```

Dentro `down()`:

```php
Schema::table('projects', function (Blueprint $table) {
    $table->dropColumn('slug');
});
```

Poi:

```bash
php artisan migrate
```

---

## 28. Cosa NON era obiettivo di questa lezione

Questa lezione non aveva come obiettivo imparare già:

- model Laravel
- Eloquent
- relazioni tra tabelle
- query al database
- factory
- seeder
- foreign key in dettaglio
- validazione dei dati
- form HTML
- CRUD completo

Il focus era:

> capire cosa sono le migration e come usarle per creare o modificare tabelle.

---

## 29. Lesson Learned

### 1. Le migration descrivono lo schema del database

Non contengono dati applicativi.

Descrivono tabelle, colonne, indici e modifiche strutturali.

---

### 2. Le migration sono una cronologia riproducibile

Permettono di ricostruire il database in locale, staging o produzione.

---

### 3. Laravel tiene traccia delle migration già eseguite

La tabella `migrations` registra cosa è già stato applicato.

---

### 4. `php artisan migrate` applica solo le migration nuove

Non rilancia tutto da capo ogni volta.

---

### 5. Il nome della migration aiuta Laravel

`create_projects_table` genera più boilerplate utile di un nome generico come `test`.

---

### 6. `Schema::create` crea una nuova tabella

Esempio:

```php
Schema::create('projects', function (Blueprint $table) {
    //
});
```

---

### 7. `Schema::table` modifica una tabella esistente

Esempio:

```php
Schema::table('projects', function (Blueprint $table) {
    //
});
```

---

### 8. `up()` applica la modifica

È il metodo eseguito quando lanciamo:

```bash
php artisan migrate
```

---

### 9. `down()` descrive l’operazione inversa

È usato da:

```bash
php artisan migrate:rollback
```

ma può essere pericoloso se cancella dati.

---

### 10. Rollback è comodo in locale

In locale possiamo fare rollback e rimigrare per correggere una migration appena creata.

---

### 11. Su tabelle già in uso si crea una nuova migration

Non si modifica una vecchia migration già eseguita in produzione.

---

### 12. Le migration vanno committate

Sono codice di progetto.

Il database SQLite locale invece non va committato.

---

## 30. Comandi riassuntivi

Entrare nel progetto:

```bash
cd ~/Progetti/web/laravel-lab/first-project
```

Creare migration:

```bash
php artisan make:migration create_projects_table
```

Eseguire migration:

```bash
php artisan migrate
```

Vedere stato migration:

```bash
php artisan migrate:status
```

Rollback ultimo batch:

```bash
php artisan migrate:rollback
```

Creare migration di modifica:

```bash
php artisan make:migration add_slug_to_projects_table
```

Controllare route:

```bash
php artisan route:list
```

Controllare file modificati:

```bash
cd ~/Progetti/web
git status --short
```

---

## 31. Stato finale della lezione

Alla fine della lezione abbiamo chiarito:

- perché le app usano quasi sempre un database
- cosa sono le migration
- perché non conviene modificare il database solo a mano
- come Laravel tiene traccia delle migration eseguite
- come creare una migration con Artisan
- perché il nome della migration è importante
- cosa fanno `up()` e `down()`
- come creare una tabella
- come aggiungere colonne
- come eseguire `php artisan migrate`
- come usare `php artisan migrate:rollback`
- quando modificare una migration appena creata
- quando creare una nuova migration per modificare una tabella esistente
- perché bisogna stare attenti ai rollback in produzione

Obiettivo raggiunto:

> abbiamo introdotto le migration come strumento Laravel per costruire e modificare lo schema del database in modo riproducibile.

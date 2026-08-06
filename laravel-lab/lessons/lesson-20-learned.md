# Getting Started with Laravel — Lezione 20
## Ordering with Eloquent

Data laboratorio: 2026-08-06  
Corso: Getting Started with Laravel  
Episodio: 20 — Ordering with Eloquent  
Framework usato nel laboratorio: Laravel Framework 13.7.0

---

## 1. Obiettivo della lezione

L’obiettivo della lezione è ordinare i record recuperati dal database con Eloquent.

Nella lezione 19 abbiamo creato la pagina indice dei progetti:

```text
GET /projects
```

Nel controller avevamo:

```php
'projects' => Project::get(),
```

Questo recupera i progetti, ma senza un ordine esplicito deciso da noi.

Ora vogliamo mostrare in alto i progetti più recenti.

---

## 2. Il problema dell’ordine implicito

Quando scriviamo:

```php
Project::get()
```

Laravel recupera i record, ma non stiamo specificando l’ordinamento.

Il database può restituire i record in un ordine che sembra naturale, ma non è una regola affidabile da usare nell’applicazione.

Regola pratica:

> se l’ordine è importante per la pagina, dichiaralo nella query.

---

## 3. Colonne utili per ordinare

La tabella `projects` contiene già:

```text
created_at
updated_at
```

Queste colonne sono state create dalla migration con:

```php
$table->timestamps();
```

Significato:

- `created_at`: quando il record è stato creato
- `updated_at`: quando il record è stato aggiornato

Per una lista di progetti, ha senso ordinare per `created_at`.

---

## 4. Ordinare con `orderBy()`

Il modo base per ordinare una query Eloquent è:

```php
Project::orderBy('created_at', 'desc')->get()
```

Significato:

```text
ordina per created_at in ordine discendente
```

Quindi:

```text
record più recente → in alto
record più vecchio → in basso
```

Nel controller:

```php
public function index()
{
    return view('projects.index', [
        'projects' => Project::orderBy('created_at', 'desc')->get(),
    ]);
}
```

---

## 5. Query chain

`orderBy()` si usa prima di `get()`.

Schema:

```php
Project::orderBy(...)->get()
```

Questo è simile a quanto già visto con `where()`:

```php
Project::where(...)->get()
```

Eloquent permette di concatenare più metodi per costruire una query.

Esempio concettuale:

```php
Project::where('name', 'A project')
    ->orderBy('created_at', 'desc')
    ->get();
```

La query viene eseguita quando chiamiamo `get()`.

---

## 6. Ascendente e discendente

`orderBy()` riceve:

```text
colonna
direzione
```

Esempio discendente:

```php
Project::orderBy('created_at', 'desc')->get();
```

Esempio ascendente:

```php
Project::orderBy('created_at', 'asc')->get();
```

Direzioni:

| Direzione | Significato |
|---|---|
| `asc` | dal più piccolo/vecchio al più grande/recente |
| `desc` | dal più grande/recente al più piccolo/vecchio |

---

## 7. `latest()`

Laravel offre una scorciatoia molto comoda:

```php
Project::latest()->get()
```

Per default, `latest()` ordina per:

```text
created_at desc
```

Quindi è equivalente, nel nostro caso, a:

```php
Project::orderBy('created_at', 'desc')->get()
```

Codice consigliato nel laboratorio:

```php
public function index()
{
    return view('projects.index', [
        'projects' => Project::latest()->get(),
    ]);
}
```

---

## 8. `latest()` con colonna personalizzata

`latest()` può anche ricevere una colonna.

Esempio:

```php
Project::latest('updated_at')->get()
```

Significato:

```text
ordina per updated_at dal più recente al più vecchio
```

Quindi `latest()` non è limitato per forza a `created_at`, anche se quello è il default.

---

## 9. `oldest()`

Laravel offre anche l’opposto:

```php
Project::oldest()->get()
```

Per default, `oldest()` ordina per:

```text
created_at asc
```

Quindi mostra prima i record più vecchi.

Esempio:

```php
Project::oldest()->get()
```

equivale a:

```php
Project::orderBy('created_at', 'asc')->get()
```

---

## 10. Scope Eloquent

La lezione introduce molto rapidamente il concetto di scope.

Uno scope è un metodo che incapsula una parte di query.

`latest()` e `oldest()` possono essere pensati come scorciatoie leggibili per ordinamenti comuni.

In questa fase non creiamo scope personalizzati.

Ci limitiamo a usare gli scope/metodi già disponibili.

---

## 11. Codice finale consigliato

`app/Http/Controllers/ProjectController.php`:

```php
public function index()
{
    return view('projects.index', [
        'projects' => Project::latest()->get(),
    ]);
}
```

Questo mostra i progetti più recenti in alto.

---

## 12. Prima e dopo

Prima:

```php
'projects' => Project::get(),
```

Dopo:

```php
'projects' => Project::latest()->get(),
```

La view non cambia.

Cambia solo il modo in cui il controller recupera i dati.

---

## 13. Test manuale

Apri:

```text
http://127.0.0.1:8000/projects
```

Crea alcuni progetti dalla pagina:

```text
http://127.0.0.1:8000/projects/create
```

Torna alla lista.

Risultato atteso:

```text
il progetto creato più recentemente appare in alto
```

---

## 14. Lesson Learned

### 1. `get()` senza ordine esplicito non basta se l’ordine conta

Meglio dichiarare l’ordinamento nella query.

---

### 2. `orderBy()` ordina per una colonna

Esempio:

```php
Project::orderBy('created_at', 'desc')->get()
```

---

### 3. L’ordinamento va prima di `get()`

`get()` esegue la query.

Prima di `get()` possiamo concatenare condizioni e ordinamenti.

---

### 4. `latest()` è una scorciatoia utile

Esempio:

```php
Project::latest()->get()
```

Per default ordina per `created_at` discendente.

---

### 5. `oldest()` fa l’opposto

Esempio:

```php
Project::oldest()->get()
```

Per default ordina per `created_at` ascendente.

---

### 6. La view non deve sapere come sono ordinati i dati

L’ordinamento appartiene alla query nel controller.

La view si limita a iterare `$projects`.

---

## 15. Comandi utili

Entrare nel progetto Laravel:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Controllare il controller:

```bash
sed -n '1,160p' app/Http/Controllers/ProjectController.php
```

Avviare server:

```bash
php artisan serve
```

Aprire lista progetti:

```text
http://127.0.0.1:8000/projects
```

Controllare in Tinker:

```bash
php artisan tinker
```

Dentro Tinker:

```php
App\Models\Project::latest()->get();
App\Models\Project::oldest()->get();
exit
```

---

## 16. Stato finale della lezione

Alla fine della lezione sappiamo:

- ordinare record Eloquent con `orderBy()`
- ordinare in modo discendente con `desc`
- ordinare in modo ascendente con `asc`
- usare `latest()` come scorciatoia per i record più recenti
- usare `oldest()` come scorciatoia opposta
- concatenare metodi di query prima di `get()`
- mostrare nella lista i progetti più recenti in alto

Obiettivo raggiunto:

> la pagina `/projects` mostra i progetti ordinati dal più recente al più vecchio.

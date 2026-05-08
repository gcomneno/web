# Lezione 02 — spiegazione fedele

## 1. Ricrea un progetto Laravel

Il docente riparte da:

```bash
laravel new my-project
```

Sceglie:

| Prompt            | Scelta nel video                             |
| ----------------- | -------------------------------------------- |
| Starter kit       | `none`                                       |
| Testing framework | Pest o PHPUnit, dice che per ora non importa |
| Database          | MySQL                                        |
| npm install/build | No                                           |

Punto importante: nella lezione 01 aveva scelto SQLite per semplicità; qui sceglie **MySQL**, ma dice chiaramente di non preoccuparsi se non lo stiamo usando. MySQL può essere configurato tramite Laravel Herd a pagamento o installato separatamente. 

Per noi, ora, **non serve rifare il progetto con MySQL**. Stiamo seguendo il concetto, non dobbiamo complicare il setup.

---

## 2. La frase più importante della lezione

Questa è da scolpire:

> La struttura del progetto Laravel **non è il framework Laravel**.

Tradotto:

```text
first-project/
```

è lo **scheletro della tua applicazione**.

Il framework vero sta dietro le quinte, installato da Composer, dentro:

```text
vendor/laravel/framework
```

Quindi:

* tu lavori principalmente nella struttura del progetto
* Composer gestisce il framework
* non si modifica mai direttamente `vendor/`

Questa distinzione è fondamentale, perché evita il classico errore da apprendista stregone: “vado dentro il framework e cambio roba lì”. No. Male. Bastone sulle dita 🪄

---

## 3. `app/`

Il docente dice che `app/` è dove passerai gran parte del tempo.

Contiene il codice principale della tua applicazione:

```text
app/
├── Http/
├── Models/
└── Providers/
```

### `app/Models`

Qui andranno i **modelli**, cioè classi PHP che rappresentano dati/tabelle.

Esempio futuro:

```text
User
Post
Article
Product
```

Per ora li nomina soltanto.

### `app/Http/Controllers`

Qui andranno i **controller**, cioè classi che gestiscono richieste web più strutturate.

Per ora abbiamo visto solo una route con una funzione anonima, ma più avanti invece di scrivere tutto in `routes/web.php`, useremo controller.

### `app/Providers`

Contiene service provider, cioè classi che configurano o registrano parti dell’applicazione.

Il docente dice: non serve capirli ora in profondità. Basta sapere che esistono.

---

## 4. `bootstrap/`

Questa parte è interessante perché in Laravel moderno è diventata più importante.

Il docente cita in particolare:

```text
bootstrap/app.php
```

Qui Laravel configura varie parti del framework, per esempio:

* routing
* middleware
* exceptions
* broadcasting / realtime

Non dobbiamo padroneggiarlo ora, ma dobbiamo sapere che è un punto di configurazione iniziale dell’app.

---

## 5. `config/`

Questa è una cartella importante.

Contiene file come:

```text
config/app.php
config/database.php
config/cache.php
config/mail.php
config/session.php
```

Qui stanno molte impostazioni Laravel.

Il docente però collega subito `config/` al file `.env`.

---

## 6. `.env`

Il file `.env` contiene configurazioni diverse per ambiente.

Esempi:

| Ambiente   | Esempio valori                   |
| ---------- | -------------------------------- |
| locale     | debug attivo, database locale    |
| produzione | debug disattivato, database vero |
| staging    | configurazione intermedia        |
| testing    | database/test separati           |

Esempi di variabili:

```text
APP_NAME
APP_ENV
APP_DEBUG
DB_CONNECTION
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```

Punto importantissimo:

```text
.env NON si committa su GitHub
```

Perché può contenere password, chiavi, credenziali e configurazioni sensibili.

Nel nostro `.gitignore` lo abbiamo già escluso. Bene così.

---

## 7. Configurazione tramite `env()`

Il docente fa l’esempio del nome applicazione.

In Laravel spesso nei file `config/*.php` trovi cose tipo:

```php
'name' => env('APP_NAME', 'Laravel'),
```

Significa:

> usa `APP_NAME` dal file `.env`; se manca, usa `Laravel` come fallback.

Quindi non conviene cambiare direttamente il valore dentro `config/app.php`, se quel valore dipende dall’ambiente.

Meglio cambiare:

```text
APP_NAME=...
```

nel `.env`.

---

## 8. `database/`

Il docente introduce tre concetti, senza approfondirli ancora:

```text
database/
├── migrations/
├── factories/
└── seeders/
```

### Migrations

Le migrations definiscono lo **schema del database**.

Cioè:

* quali tabelle esistono
* quali colonne hanno
* quali indici/relazioni ci sono

Esempio: creare la tabella `users`.

### Factories

Le factories generano dati finti per i modelli.

Utili per:

* test
* sviluppo
* riempire velocemente il database con dati realistici

### Seeders

I seeders inseriscono dati iniziali nel database.

Esempio:

* creare utenti demo
* creare categorie iniziali
* preparare dati per un nuovo sviluppatore nel team

---

# Pratica sul nostro progetto

Facciamola sul progetto esistente:

```text
~/Progetti/web/laravel-lab/first-project
```

## 1. Entra nel progetto

```bash
cd ~/Progetti/web/laravel-lab/first-project

pwd
```

## 2. Guarda la struttura principale

```bash
tree -L 2 app bootstrap config database routes resources
```

## 3. Controlla dove sta Laravel framework

```bash
composer show laravel/framework | sed -n '1,40p'

ls -ld vendor/laravel/framework
```

## 4. Guarda `bootstrap/app.php`

```bash
sed -n '1,200p' bootstrap/app.php
```

## 5. Guarda `config/app.php`

```bash
sed -n '1,160p' config/app.php
```

## 6. Guarda `.env`, senza pubblicarlo mai

```bash
grep -E '^(APP_NAME|APP_ENV|APP_DEBUG|DB_CONNECTION|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=' .env
```

## 7. Guarda le migrations iniziali

```bash
ls -lh database/migrations

sed -n '1,160p' database/migrations/0001_01_01_000000_create_users_table.php
```

## 8. Guarda factory e seeder

```bash
sed -n '1,160p' database/factories/UserFactory.php

echo "----------------------------------------"

sed -n '1,160p' database/seeders/DatabaseSeeder.php
```

# Lesson Learned provvisorie

Da questa lezione dobbiamo portarci via:

1. Il progetto Laravel generato è lo **scheletro dell’app**, non il framework.
2. Il framework vero sta in `vendor/`, installato da Composer.
3. Non si modifica mai `vendor/`.
4. `app/` è la casa principale del codice applicativo.
5. `bootstrap/app.php` configura parti importanti del framework moderno.
6. `config/` contiene configurazioni Laravel.
7. `.env` contiene valori specifici dell’ambiente e **non va committato**.
8. `database/migrations` definisce lo schema del database.
9. `database/factories` genera dati finti.
10. `database/seeders` popola il database con dati iniziali.

Esegui i blocchi di pratica sopra; poi con i tuoi output preparo il Markdown finale scaricabile della **Lezione 02** 📘

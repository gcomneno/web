# Getting Started with Laravel — Lesson 20
## Ordering with Eloquent

[English](lesson-20-learned.md) | [Italiano](lesson-20-learned.it.md)

Lab date: 2026-08-06
Course: Getting Started with Laravel
Episode: 20 — Ordering with Eloquent
Framework used in the lab: Laravel Framework 13.7.0

---

## 1. Lesson objective

The objective of this lesson is to order records retrieved from the database with Eloquent.

In lesson 19, we created the project index page:

```text
GET /projects
```

In the controller, we had:

```php
'projects' => Project::get(),
```

This retrieves the projects, but without an explicit order chosen by us.

Now we want to display the most recent projects at the top.

---

## 2. The problem with implicit ordering

When we write:

```php
Project::get()
```

Laravel retrieves the records, but we are not specifying their order.

The database may return records in an order that appears natural, but this is not a reliable rule for the application.

Practical rule:

> if the order matters for the page, declare it in the query.

---

## 3. Useful columns for ordering

The `projects` table already contains:

```text
created_at
updated_at
```

These columns were created by the migration with:

```php
$table->timestamps();
```

Meaning:

- `created_at`: when the record was created
- `updated_at`: when the record was updated

For a project list, it makes sense to order by `created_at`.

---

## 4. Ordering with `orderBy()`

The basic way to order an Eloquent query is:

```php
Project::orderBy('created_at', 'desc')->get()
```

Meaning:

```text
ordina per created_at in ordine discendente
```

Therefore:

```text
record più recente → in alto
record più vecchio → in basso
```

In the controller:

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

`orderBy()` is used before `get()`.

Pattern:

```php
Project::orderBy(...)->get()
```

This is similar to what we have already seen with `where()`:

```php
Project::where(...)->get()
```

Eloquent lets us chain multiple methods to build a query.

Conceptual example:

```php
Project::where('name', 'A project')
    ->orderBy('created_at', 'desc')
    ->get();
```

The query is executed when we call `get()`.

---

## 6. Ascending and descending

`orderBy()` receives:

```text
colonna
direzione
```

Descending example:

```php
Project::orderBy('created_at', 'desc')->get();
```

Ascending example:

```php
Project::orderBy('created_at', 'asc')->get();
```

Directions:

| Direction | Meaning |
|---|---|
| `asc` | from the smallest/oldest to the largest/most recent |
| `desc` | from the largest/most recent to the smallest/oldest |

---

## 7. `latest()`

Laravel provides a very convenient shortcut:

```php
Project::latest()->get()
```

By default, `latest()` orders by:

```text
created_at desc
```

In our case, it is therefore equivalent to:

```php
Project::orderBy('created_at', 'desc')->get()
```

Recommended code for the lab:

```php
public function index()
{
    return view('projects.index', [
        'projects' => Project::latest()->get(),
    ]);
}
```

---

## 8. `latest()` with a custom column

`latest()` can also receive a column.

Example:

```php
Project::latest('updated_at')->get()
```

Meaning:

```text
ordina per updated_at dal più recente al più vecchio
```

Therefore, `latest()` is not necessarily limited to `created_at`, although that is the default.

---

## 9. `oldest()`

Laravel also provides the opposite:

```php
Project::oldest()->get()
```

By default, `oldest()` orders by:

```text
created_at asc
```

It therefore displays the oldest records first.

Example:

```php
Project::oldest()->get()
```

is equivalent to:

```php
Project::orderBy('created_at', 'asc')->get()
```

---

## 10. Eloquent scopes

The lesson introduces the concept of scopes very briefly.

A scope is a method that encapsulates part of a query.

`latest()` and `oldest()` can be thought of as readable shortcuts for common ordering operations.

At this stage, we do not create custom scopes.

We only use the scopes and methods that are already available.

---

## 11. Recommended final code

`app/Http/Controllers/ProjectController.php`:

```php
public function index()
{
    return view('projects.index', [
        'projects' => Project::latest()->get(),
    ]);
}
```

This displays the most recent projects at the top.

---

## 12. Before and after

Before:

```php
'projects' => Project::get(),
```

After:

```php
'projects' => Project::latest()->get(),
```

The view does not change.

Only the way the controller retrieves the data changes.

---

## 13. Manual test

Open:

```text
http://127.0.0.1:8000/projects
```

Create some projects from the page:

```text
http://127.0.0.1:8000/projects/create
```

Return to the list.

Expected result:

```text
il progetto creato più recentemente appare in alto
```

---

## 14. Lessons learned

### 1. `get()` without explicit ordering is not enough when order matters

It is better to declare the order in the query.

---

### 2. `orderBy()` orders by a column

Example:

```php
Project::orderBy('created_at', 'desc')->get()
```

---

### 3. Ordering comes before `get()`

`get()` executes the query.

Before `get()`, we can chain conditions and ordering operations.

---

### 4. `latest()` is a useful shortcut

Example:

```php
Project::latest()->get()
```

By default, it orders by `created_at` in descending order.

---

### 5. `oldest()` does the opposite

Example:

```php
Project::oldest()->get()
```

By default, it orders by `created_at` in ascending order.

---

### 6. The view should not know how the data is ordered

Ordering belongs in the controller query.

The view only iterates over `$projects`.

---

## 15. Useful commands

Enter the Laravel project:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Inspect the controller:

```bash
sed -n '1,160p' app/Http/Controllers/ProjectController.php
```

Start the server:

```bash
php artisan serve
```

Open the project list:

```text
http://127.0.0.1:8000/projects
```

Inspect the data in Tinker:

```bash
php artisan tinker
```

Inside Tinker:

```php
App\Models\Project::latest()->get();
App\Models\Project::oldest()->get();
exit
```

---

## 16. Final lesson state

At the end of the lesson, we know how to:

- order Eloquent records with `orderBy()`
- sort in descending order with `desc`
- sort in ascending order with `asc`
- use `latest()` as a shortcut for the most recent records
- use `oldest()` as the opposite shortcut
- chain query methods before `get()`
- display the most recent projects at the top of the list

Objective achieved:

> the `/projects` page displays projects ordered from the most recent to the oldest.

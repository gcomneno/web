# Getting Started with Laravel — Lesson 21
## Other HTTP verbs

[English](lesson-21-learned.md) | [Italiano](lesson-21-learned.it.md)

Lab date: 2026-08-06
Course: Getting Started with Laravel
Episode: 21 — Other HTTP verbs
Framework used in the lab: Laravel Framework 13.7.0

---

## 1. Lesson objective

The objective of this lesson is to use HTTP verbs other than `GET` and `POST` inside a traditional Blade application.

The practical case chosen is deleting a project.

So far, we have:

```text
GET  /projects          → lista progetti
GET  /projects/create   → form creazione
POST /projects          → salva progetto
GET  /projects/{slug}   → dettaglio progetto
```

Now we add:

```text
DELETE /projects/{slug} → elimina progetto
```

---

## 2. Why a GET link should not be used for deletion

A wrong temptation would be to create a route such as:

```php
Route::get('/projects/{project:slug}/delete', ...);
```

or a link:

```blade
<a href="/projects/some-project/delete">Delete</a>
```

This is wrong because a `GET` request should not change the state of the application.

A deletion changes data.

It must therefore not be performed with `GET`.

---

## 3. Security problem

If deletion were a simple `GET` route, merely visiting a URL would be enough to delete a project.

Example:

```text
/projects/a-first-project/delete
```

This would be dangerous.

An attacker could trick the user into visiting that URL, for example through a link or hidden image.

Destructive operations require the correct HTTP verb and CSRF protection.

---

## 4. Correct HTTP verb: `DELETE`

To delete a resource, the correct HTTP verb is:

```text
DELETE
```

In Laravel:

```php
Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

This route means:

```text
DELETE /projects/{project:slug} → ProjectController@destroy
```

---

## 5. The `destroy()` method

In a RESTful controller, the conventional method for deleting a resource is:

```php
destroy()
```

In our case:

```php
public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

The `Project $project` parameter uses route model binding.

Laravel automatically loads the project using the slug defined in the route:

```text
{project:slug}
```

---

## 6. Route model binding for DELETE as well

The route:

```php
Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

and the method:

```php
public function destroy(Project $project)
```

work together.

Laravel:

1. reads the slug from the URL
2. looks for the `Project`
3. passes it to the `destroy()` method
4. returns a 404 if it cannot be found

---

## 7. HTML forms do not directly support DELETE

A traditional HTML form directly supports mainly:

```text
GET
POST
```

We cannot simply use:

```blade
<form method="DELETE">
```

reliably in browsers.

Laravel therefore uses method spoofing.

---

## 8. Method spoofing

Method spoofing means:

> actually sending a POST request while telling Laravel to treat it as DELETE.

The form uses:

```blade
method="POST"
```

but contains:

```blade
@method('DELETE')
```

Laravel generates a hidden field similar to:

```html
<input type="hidden" name="_method" value="DELETE">
```

When the request arrives, Laravel understands that it must treat it as `DELETE`.

---

## 9. Deletion form

In the project list, we can add a form for each project:

```blade
<form action="{{ route('projects.destroy', $project) }}" method="POST">
    @csrf
    @method('DELETE')

    <button type="submit">Delete</button>
</form>
```

Important points:

- `action` uses the `projects.destroy` named route
- we pass `$project` because the route requires `{project:slug}`
- the HTML method is `POST`
- `@method('DELETE')` tells Laravel to use `DELETE`
- `@csrf` protects the request

---

## 10. Why `@csrf` is still required

Even though we are simulating `DELETE`, the form still sends a request that changes data.

Therefore, it requires:

```blade
@csrf
```

Practical rule:

> every form that changes data must include `@csrf`.

This applies to:

```text
POST
PUT
PATCH
DELETE
```

---

## 11. Error without `@method('DELETE')`

If the form only uses:

```blade
<form method="POST">
```

but the route is:

```php
Route::delete(...)
```

Laravel cannot find a compatible `POST` route.

Typical error:

```text
The POST method is not supported for route ...
Supported methods: DELETE
```

The fix is to add:

```blade
@method('DELETE')
```

---

## 12. Deleting with Eloquent

We have already seen that an Eloquent model can be deleted with:

```php
$project->delete();
```

Inside the `destroy()` method:

```php
public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

Because `$project` already comes from route model binding, we do not need to perform a manual query.

---

## 13. Redirect after deletion

After deletion, we can use:

```php
return back();
```

or an explicit redirect:

```php
return redirect()->route('projects.index');
```

The lesson also shows the shortcut:

```php
return to_route('projects.index');
```

All these options can work.

For a destructive action performed from the project list, returning to the list makes sense.

---

## 14. Redirect after creation

The lesson also improves the behavior after creation.

Before:

```php
return back()->with('status', 'Your project was created.');
```

that is, after creating the project, the user returned to the form.

It now makes more sense to return to the list:

```php
return redirect()
    ->route('projects.index')
    ->with('status', 'Your project was created.');
```

This lets the user immediately see the newly created project in the list.

---

## 15. Moving the flash message to the list

If, after creation, we redirect to:

```text
/projects
```

the flash message must be displayed in the `projects.index` view, not only in `projects.create`.

Therefore, the block:

```blade
@session('status')
    <p>{{ $value }}</p>
@endsession
```

must be placed in:

```text
resources/views/projects/index.blade.php
```

We could also keep it in both views, but at this stage it is more consistent to place it where we redirect.

---

## 16. Updated index view

`resources/views/projects/index.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects</title>
</head>
<body>
    <h1>Projects</h1>

    @session('status')
        <p>{{ $value }}</p>
    @endsession

    <p>
        <a href="{{ route('projects.create') }}">New project</a>
    </p>

    <ul>
        @foreach ($projects as $project)
            <li>
                <a href="{{ route('projects.show', $project) }}">
                    {{ $project->name }}
                </a>

                <form action="{{ route('projects.destroy', $project) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
```

The HTML is intentionally simple and not yet styled.

---

## 17. Updated controller

`app/Http/Controllers/ProjectController.php`:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'max:255'],
    ]);

    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return redirect()
        ->route('projects.index')
        ->with('status', 'Your project was created.');
}

public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

---

## 18. Updated route

`routes/web.php`:

```php
Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

This route belongs together with the other project routes.

Recommended order:

```php
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/create', [ProjectController::class, 'create'])
    ->name('projects.create');

Route::post('/projects', [ProjectController::class, 'store'])
    ->name('projects.store');

Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');

Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');
```

---

## 19. `redirect()->route()` and `to_route()`

The lesson mentions two ways to redirect to a named route.

Explicit form:

```php
return redirect()->route('projects.index');
```

Shortcut:

```php
return to_route('projects.index');
```

At this stage, we use `redirect()->route()` because it is more descriptive for learning purposes.

---

## 20. Flash messages with explicit redirects as well

We can chain `with()` to a redirect to a route as well:

```php
return redirect()
    ->route('projects.index')
    ->with('status', 'Your project was created.');
```

Therefore, `with()` does not work only with `back()`.

It also works with redirects to named routes.

---

## 21. Problem not yet solved: duplicate slugs

The duplicate slug problem appears again during the lesson.

If we create two projects with the same name:

```text
Great project
Great project
```

both generate:

```text
great-project
```

The `slug` column is unique, so the second insert may fail.

This will need to be handled later with validation or dedicated logic.

---

## 22. What is not handled yet

This lesson does not yet introduce:

- a deletion confirmation page
- a JavaScript confirmation dialog
- authorization
- policies
- soft deletes
- a flash message after deletion
- graceful handling of duplicate slugs
- a shared layout for flash messages

---

## 23. Lessons learned

### 1. Do not use GET for destructive actions

A deletion must not be a GET link.

---

### 2. DELETE is used for deletion

In Laravel:

```php
Route::delete(...)
```

---

### 3. Blade forms use POST plus method spoofing

Example:

```blade
<form method="POST">
    @csrf
    @method('DELETE')
</form>
```

---

### 4. `@method('DELETE')` generates a hidden `_method` field

Laravel uses it to treat the request as DELETE.

---

### 5. `@csrf` is also required for DELETE

Every form that changes data must be protected.

---

### 6. `destroy()` is the RESTful method for deletion

Example:

```php
public function destroy(Project $project)
{
    $project->delete();

    return back();
}
```

---

### 7. After creation, returning to the list is often better

Example:

```php
return redirect()
    ->route('projects.index')
    ->with('status', 'Your project was created.');
```

---

### 8. `with()` also works with redirects to named routes

Not only with `back()`.

---

### 9. The flash message must be displayed on the destination page

If you redirect to `projects.index`, display the message in `projects.index`.

---

## 24. Useful commands

Enter the Laravel project:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Inspect the routes:

```bash
php artisan route:list
```

Inspect the controller:

```bash
sed -n '1,260p' app/Http/Controllers/ProjectController.php
```

Inspect the index view:

```bash
sed -n '1,260p' resources/views/projects/index.blade.php
```

Start the server:

```bash
php artisan serve
```

Open the project list:

```text
http://127.0.0.1:8000/projects
```

---

## 25. Final lesson state

At the end of the lesson, we know how to:

- use a `DELETE` route
- create a `destroy()` method
- delete a model with `$project->delete()`
- use a Blade form to submit a deletion
- use `@method('DELETE')`
- protect the form with `@csrf`
- redirect to `projects.index`
- flash a message on an explicit redirect as well
- move the flash message to the destination view

Objective achieved:

> the project list now allows a project to be deleted using the correct HTTP verb.

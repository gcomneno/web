# Getting Started with Laravel — Lesson 19
## Listing in Blade

[English](lesson-19-learned.md) | [Italiano](lesson-19-learned.it.md)

Lab date: 2026-08-06
Course: Getting Started with Laravel
Episode: 19 — Listing in Blade
Framework used in the lab: Laravel Framework 13.7.0

---

## 1. Lesson objective

The objective of this lesson is to display a list of projects in a Blade view.

In the previous lessons, we already learned how to:

- create a project through a form
- validate the form
- display errors
- display a flash message
- view the details of a single project through its slug

Now we add the index page:

```text
GET /projects
```

This page must display all projects stored in the database.

---

## 2. New `index()` method

In a RESTful Laravel controller, the conventional method for displaying a list of resources is:

```php
index()
```

In our case, we add it to:

```text
app/Http/Controllers/ProjectController.php
```

Initial method:

```php
public function index()
{
    return view('projects.index');
}
```

For now, it only displays the view.

---

## 3. New `projects.index` view

Artisan command:

```bash
php artisan make:view projects.index
```

This creates:

```text
resources/views/projects/index.blade.php
```

The dot notation:

```php
view('projects.index')
```

corresponds to:

```text
resources/views/projects/index.blade.php
```

---

## 4. New `GET /projects` route

In `routes/web.php`, we add:

```php
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');
```

This route connects:

```text
GET /projects
```

to the method:

```text
ProjectController@index
```

---

## 5. Naming routes

The lesson reinforces the use of named routes.

Instead of writing URLs directly in views:

```blade
<a href="/projects/create">New project</a>
```

it is better to use:

```blade
<a href="{{ route('projects.create') }}">New project</a>
```

Why?

If the URL changes in the future, for example from:

```text
/projects/create
```

to:

```text
/project/new
```

we do not need to modify every view.

We only need to update the route.

The name remains stable.

---

## 6. Route naming convention

The lesson uses names consistent with the RESTful structure:

```text
projects.index
projects.create
projects.store
projects.show
```

This form is clear because it follows the pattern:

```text
risorsa.azione
```

Examples:

| Route name | Meaning |
|---|---|
| `projects.index` | project list |
| `projects.create` | project creation form |
| `projects.store` | POST endpoint for saving |
| `projects.show` | details of a single project |

---

## 7. Updating the form with `route()`

In the `projects.create` view, the form can change from:

```blade
<form action="/projects" method="POST">
```

to:

```blade
<form action="{{ route('projects.store') }}" method="POST">
```

This avoids hardcoding the URL.

Practical rule:

> in views, prefer `route('nome.route')` over manually written URLs.

---

## 8. Passing data to the view

To display all projects, the controller must retrieve them and pass them to the view.

We have already seen that `view()` accepts a second argument:

```php
return view('projects.index', [
    'projects' => Project::get(),
]);
```

This makes the following variable available in the view:

```php
$projects
```

---

## 9. Retrieving projects with Eloquent

The lesson uses:

```php
Project::get()
```

`get()` executes the query and returns a collection of models.

In our case:

```php
$projects = Project::get();
```

returns a collection of `Project` objects.

Code:

```php
public function index()
{
    return view('projects.index', [
        'projects' => Project::get(),
    ]);
}
```

---

## 10. Collection

A Laravel collection is a container of items.

In the case of Eloquent:

```php
Project::get()
```

returns a collection of models.

However, the concept of a collection is not limited to the database.

A collection can contain:

- Eloquent models
- strings
- objects
- arrays
- data received from APIs
- manually created items

Collections provide many useful methods for manipulating data.

In this lesson, we are mainly interested in the fact that they are iterable.

---

## 11. Iterating over a collection in Blade

To loop through all projects in the view, we use the Blade directive:

```blade
@foreach ($projects as $project)
    {{ $project->name }}
@endforeach
```

It is similar to a regular PHP `foreach`.

Inside the loop:

```php
$project
```

represents a single `Project` model.

---

## 12. First index view

Minimal version:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects</title>
</head>
<body>
    <h1>Projects</h1>

    <p>
        <a href="{{ route('projects.create') }}">New project</a>
    </p>

    <ul>
        @foreach ($projects as $project)
            <li>{{ $project->name }}</li>
        @endforeach
    </ul>
</body>
</html>
```

This view displays a title, a link to the creation form, and a list of project names.

---

## 13. Linking each project to its detail page

Now that we have a detail page:

```text
GET /projects/{project:slug}
```

each project in the list can become a link.

Route:

```php
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');
```

In the view:

```blade
<a href="{{ route('projects.show', $project) }}">
    {{ $project->name }}
</a>
```

Laravel receives the `$project` model and builds the correct URL.

---

## 14. Why the model can be passed to `route()`

The `projects.show` route requires a parameter:

```text
{project:slug}
```

If we call:

```blade
route('projects.show', $project)
```

Laravel knows how to use the model to generate the required parameter.

With slug binding, the URL becomes something like:

```text
/projects/a-first-project
```

Alternatively, we could be explicit:

```blade
route('projects.show', $project->slug)
```

However, passing the model is more flexible.

If the key used by the route changes in the future, less code needs to be modified.

---

## 15. Error: missing parameter

If we try to generate the route without passing the project:

```blade
route('projects.show')
```

Laravel does not know which project to insert into the URL.

Typical error:

```text
Missing required parameter
```

This makes sense because the route needs to know which value to use for:

```text
{project:slug}
```

---

## 16. Passing multiple parameters to `route()`

The lesson also briefly mentions the case of multiple parameters.

If a route requires multiple values, an array can be passed:

```blade
route('some.route', [$first, $second])
```

In our case, only one parameter is required, so this is enough:

```blade
route('projects.show', $project)
```

---

## 17. Recommended final view

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

    <p>
        <a href="{{ route('projects.create') }}">New project</a>
    </p>

    <ul>
        @foreach ($projects as $project)
            <li>
                <a href="{{ route('projects.show', $project) }}">
                    {{ $project->name }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
```

---

## 18. Updated `ProjectController`

`app/Http/Controllers/ProjectController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::get(),
        ]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        Project::create([
            'name' => $request->name,
            'slug' => str($request->name)->slug(),
        ]);

        return back()->with('status', 'Your project was created.');
    }

    public function show(Project $project)
    {
        return view('projects.show', [
            'project' => $project,
        ]);
    }
}
```

---

## 19. Updated `routes/web.php`

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
```

The order matters.

`/projects/create` must remain before `/projects/{project:slug}`, otherwise Laravel might interpret `create` as a slug.

---

## 20. What comes next

The lesson ends by anticipating ordering.

At the moment:

```php
Project::get()
```

retrieves projects without an explicit order.

The next lesson will probably introduce:

```php
Project::orderBy(...)->get()
```

or equivalent Eloquent methods.

---

## 21. Lessons learned

### 1. `index()` displays a resource list

In a RESTful controller, `index()` is the conventional method for the listing page.

### 2. `projects.index` is the list view

It corresponds to:

```text
resources/views/projects/index.blade.php
```

### 3. `Project::get()` returns a collection

The collection contains multiple `Project` models.

### 4. A collection can be iterated over in Blade

Example:

```blade
@foreach ($projects as $project)
    {{ $project->name }}
@endforeach
```

### 5. Named routes avoid hardcoded URLs

Better:

```blade
route('projects.create')
```

than:

```blade
/projects/create
```

### 6. `route()` can also generate URLs with parameters

Example:

```blade
route('projects.show', $project)
```

### 7. With `{project:slug}`, Laravel can use the model to generate the URL

Passing `$project` to `route()` produces a URL with the correct slug.

### 8. Laravel reports an error when a parameter is missing

Example:

```blade
route('projects.show')
```

can generate:

```text
Missing required parameter
```

### 9. Route order matters

`/projects/create` must appear before `/projects/{project:slug}`.

---

## 22. Useful commands

Enter the Laravel project:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Create the view:

```bash
php artisan make:view projects.index
```

Inspect routes:

```bash
php artisan route:list
```

Start the server:

```bash
php artisan serve
```

Open the project list:

```text
http://127.0.0.1:8000/projects
```

Open the creation form:

```text
http://127.0.0.1:8000/projects/create
```

Inspect the controller:

```bash
sed -n '1,240p' app/Http/Controllers/ProjectController.php
```

Inspect the index view:

```bash
sed -n '1,220p' resources/views/projects/index.blade.php
```

---

## 23. Final lesson state

At the end of the lesson, we know how to:

- create an index page for a resource
- add `ProjectController@index`
- create `projects.index`
- retrieve multiple models with `Project::get()`
- pass a collection to Blade
- iterate with `@foreach`
- create links with named routes
- use `route('projects.show', $project)`
- avoid hardcoded URLs in views

Objective achieved:

> the application displays a list of projects, and each project links to its detail page.

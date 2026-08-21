# Getting Started with Laravel — Lesson 22
## Eloquent events

[English](lesson-22-learned.md) | [Italiano](lesson-22-learned.it.md)

Lab date: 2026-08-21  
Course: Getting Started with Laravel  
Episode: 22 — Eloquent events  
Framework used in the lab: Laravel Framework 13.7.0

---

## 1. Lesson objective

The goal of this lesson is to introduce Eloquent events and use them to move logic that previously lived in the controller into the model.

The practical problem is duplicate slugs.

In previous lessons, when creating a project, we used:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

This generates the slug from the name.

However, if we create two projects with the same name, we get the same slug.

Example:

```text
A new project
A new project
```

produces twice:

```text
a-new-project
```

Because the `slug` column is unique, Laravel/the database returns a constraint error.

---

## 2. The duplicate slug problem

The `projects` table has a `slug` column with a `unique` constraint.

This makes sense because the slug is used in the URL:

```text
/projects/{project:slug}
```

If two projects had the same slug, Laravel would not know which project to load from that URL.

Therefore, the `unique` constraint is correct.

The constraint is not the problem.

The problem is that slug generation is too simple.

---

## 3. Quick solution: add a timestamp

A quick solution is to add a value that makes the slug different.

The lesson demonstrates concatenating a timestamp.

Conceptual example:

```php
'slug' => str($request->name . '-' . now()->getTimestamp())->slug(),
```

This way:

```text
A new project
```

can become:

```text
a-new-project-1787313600
```

The timestamp reduces the risk of duplicates.

It is not the most elegant solution possible, but it is sufficient for the lab.

---

## 4. Why move the logic out of the controller

Until now, the slug has been created inside `ProjectController@store`.

This works only when the project is created through that controller.

But in a real application we might create projects from an admin area, API, seeder, background job, Artisan command, another controller, or automated tests.

If slug logic remains in the controller, we have to remember to duplicate it everywhere.

It is better to move it into the `Project` model.

---

## 5. Eloquent events

Eloquent lets us hook into events in a model's lifecycle.

Examples of events:

```text
creating
created
updating
updated
deleting
deleted
```

These events let us execute code at specific moments.

In our case, we want to set the slug before the record is saved.

Therefore, we use:

```text
creating
```

---

## 6. `creating` vs `created`

An important difference:

```text
creating → before the record is inserted into the database
created  → after the record has been inserted into the database
```

To set the slug, we need to act before insertion.

Therefore, the correct event is:

```php
creating
```

If we used `created`, the record would already have been saved and it would be too late to populate the slug for the initial insert.

---

## 7. The `booted()` method

In the model we can define:

```php
protected static function booted()
{
    //
}
```

This method is called when the model is booted by Eloquent.

Inside `booted()` we can register model events.

The course uses this approach:

```php
static::creating(function (Project $project) {
    //
});
```

---

## 8. Moving slug generation into the model

In the `Project` model we add:

```php
protected static function booted()
{
    static::creating(function (Project $project) {
        $project->slug = str($project->name . '-' . now()->getTimestamp())->slug();
    });
}
```

When we call:

```php
Project::create([
    'name' => $request->name,
]);
```

Eloquent runs the `creating` event.

Before saving the record, it automatically assigns:

```php
$project->slug
```

---

## 9. A cleaner controller

After this change, the controller no longer needs to handle the slug.

Before:

```php
Project::create([
    'name' => $request->name,
    'slug' => str($request->name)->slug(),
]);
```

After:

```php
Project::create([
    'name' => $request->name,
]);
```

The controller handles the request.

The model handles an internal rule of the model.

---

## 10. Why this choice is useful

Moving slug generation into the model means that wherever a `Project` is created, its slug is generated automatically.

Example:

```php
Project::create([
    'name' => 'A new project',
]);
```

works from a controller, Tinker, seeder, test, job, and API.

This makes the behavior more consistent.

---

## 11. Caution: hidden logic

The lesson also points out an important aspect.

Eloquent events can hide behavior.

When we read the controller:

```php
Project::create([
    'name' => $request->name,
]);
```

we cannot immediately see where the slug comes from.

The logic lives in the model.

This can be convenient, but we need to know that it exists.

Practical rule:

> Eloquent events are powerful, but they should not be used to hide too much logic in a way that makes the code hard to understand.

---

## 12. Still a temporary solution

The lesson does not implement sophisticated logic to check whether a slug already exists.

It uses a timestamp to avoid the conflict.

This is an acceptable teaching solution at this stage.

It does not yet cover:

- incremental checks such as `a-new-project-2`
- duplicate-name validation
- more readable slug generation
- retries in case of collision
- a dedicated slug service
- a separate observer

---

## 13. Recommended final model

`app/Models/Project.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted()
    {
        static::creating(function (Project $project) {
            $project->slug = str($project->name . '-' . now()->getTimestamp())->slug();
        });
    }
}
```

Note: even though the slug is now generated automatically, we leave `slug` in `$fillable` for consistency with the previous teaching state of the course, unless the course evolves differently later.

---

## 14. Recommended final controller

In the controller, `store()` becomes:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'max:255'],
    ]);

    Project::create([
        'name' => $request->name,
    ]);

    return redirect()
        ->route('projects.index')
        ->with('status', 'Your project was created.');
}
```

---

## 15. Manual test

Open:

```text
http://127.0.0.1:8000/projects/create
```

Create a project with a name that has already been used, for example:

```text
A new project
```

Expected result:

```text
no unique constraint error
redirect to /projects
flash message visible
new project in the list
URL with a slug that also contains a timestamp
```

Example slug:

```text
a-new-project-1787313600
```

---

## 16. Test with Tinker

Enter Tinker:

```bash
php artisan tinker
```

Create a project:

```php
App\Models\Project::create([
    'name' => 'A new project',
]);
```

Check the slug:

```php
App\Models\Project::latest()->first();
```

Exit:

```php
exit
```

The important point is that the slug is created from Tinker too, not only from the controller.

---

## 17. Lesson Learned

### 1. Eloquent events let us hook into the model lifecycle

Examples:

```text
creating
created
updating
updated
deleting
deleted
```

### 2. `creating` happens before saving

It is the right place to set data required before the insert.

### 3. `created` happens after saving

It is not suitable for populating a column required before the insert.

### 4. `booted()` registers model events

Example:

```php
protected static function booted()
{
    static::creating(function (Project $project) {
        //
    });
}
```

### 5. The slug can be generated automatically by the model

This way, the controller no longer needs to handle it.

### 6. Moving logic into the model makes the behavior global

Every `Project::create()` generates the slug, regardless of where it is called.

### 7. Eloquent events should be used carefully

They can make the controller cleaner, but they can also hide behavior.

### 8. The timestamp avoids the teaching example's duplicate-slug conflict

It is not a perfect solution, but it solves the problem encountered in the lesson.

---

## 18. Useful commands

Enter the Laravel project:

```bash
cd ~/Progetti/labs/web/laravel-lab/first-project
```

Inspect the model:

```bash
sed -n '1,220p' app/Models/Project.php
```

Inspect the controller:

```bash
sed -n '1,220p' app/Http/Controllers/ProjectController.php
```

Start the server:

```bash
php artisan serve
```

Test with Tinker:

```bash
php artisan tinker
```

Inside Tinker:

```php
App\Models\Project::create(['name' => 'A new project']);
App\Models\Project::latest()->first();
exit
```

---

## 19. Final lesson state

At the end of the lesson we know:

- what Eloquent events are
- how to use `booted()` in the model
- how to hook into the `creating` event
- how to modify the model before saving
- how to move slug generation from the controller to the model
- how to avoid the duplicate-slug problem by adding a timestamp
- how to make slug creation consistent wherever a project is created

Objective achieved:

> every new project automatically generates its slug through an Eloquent model event.

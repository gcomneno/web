# Laravel Lab

[English](README.md) | [Italiano](README.it.md)

A learning lab for mastering Laravel from scratch.

The path follows video lessons analyzed one at a time:

1. local transcription of the lesson
2. reasoned explanation
3. hands-on practice in the local project
4. collection of lessons learned in Markdown

## Structure

- `first-project/` — the first Laravel project created during the lab
- `lessons/` — notes and lessons learned
- `scripts/` — supporting scripts, such as video transcription
- `GLOSSARY.md` — a minimal glossary of the Laravel/PHP terms encountered

## Review summary

- [Summary of the fundamental concepts — lessons 01-15](lessons/summary-lessons-01-15.md)

## Lessons

| Lesson | Topic | File |
|---|---|---|
| 01 | Installing Laravel | `lessons/lesson-01-learned.md` |
| 02 | First tour of the project skeleton | `lessons/lesson-02-learned.md` |
| 03 | First Laravel routes | `lessons/lesson-03-learned.md` |
| 04 | First Blade views | `lessons/lesson-04-learned.md` |
| 05 | Blade and data passed to the view | `lessons/lesson-05-learned.md` |
| 06 | `.env` configuration and `config()` | `lessons/lesson-06-learned.md` |
| 07 | Moving from closures to controllers | `lessons/lesson-07-learned.md` |
| 08 | Review of Artisan and its main commands | `lessons/lesson-08-learned.md` |
| 09 | Introduction to database migrations | `lessons/lesson-09-learned.md` |
| 10 | Creating Laravel models | `lessons/lesson-10-learned.md` |
| 11 | First CRUD operations with Eloquent | `lessons/lesson-11-learned.md` |
| 12 | Route parameters, request data, and dynamic views | `lessons/lesson-12-learned.md` |
| 13 | Route model binding and slugs | `lessons/lesson-13-learned.md` |
| 14 | Form submission, CSRF, and project creation | `lessons/lesson-14-learned.md` |
| 15 | ProjectController and route cleanup | `lessons/lesson-15-learned.md` |
| 16 | Form validation with `$request->validate()` | `lessons/lesson-16-learned.md` |
| 17 | Displaying errors and using `old()` | `lessons/lesson-17-learned.md` |
| 18 | Success flash messages | `lessons/lesson-18-learned.md` |
| 19 | Listing projects in Blade | `lessons/lesson-19-learned.md` |
| 20 | Ordering records with Eloquent | `lessons/lesson-20-learned.md` |
| 21 | Other HTTP verbs and project deletion | `lessons/lesson-21-learned.md` |

## Example project status

The `first-project/` project follows the practical lessons step by step.

It currently contains:

- a Laravel project installed and runnable locally
- initial routes defined in `routes/web.php`
- a homepage served through a Blade view
- a view organized in `resources/views/pages/home.blade.php`
- data passed from application logic to the view
- the page title read through `config('app.name')`
- a homepage handled by `HomeController`
- practical use of Artisan to explore commands, routes, environment, view cache, and generation tools
- a `projects` table defined through a migration
- a `slug` column added to the `projects` table
- a `Project` model created in `app/Models/Project.php`
- mass-assignment protection through `$fillable`
- an educational `/eloquent` route for observing `Project::all()`
- basic CRUD examples with Eloquent
- a dynamic `/projects/{project:slug}` route
- project retrieval through route model binding
- a `resources/views/projects/show.blade.php` view
- a project detail page populated with database data
- use of `created_at` and Carbon in the view
- a `resources/views/projects/create.blade.php` view
- a `POST /projects` form protected by `@csrf`
- project creation from request data
- automatic slug generation with `str()->slug()`
- a `ProjectController` controller with `create`, `store`, and `show` methods
- validation of the `name` field with `$request->validate()`
- display of validation errors in the form view
- preservation of the entered value with `old('name')`
- a project index view in `resources/views/projects/index.blade.php`
- an `index` method in `ProjectController`
- projects ordered with `Project::latest()->get()`
- project detail links generated with `route('projects.show', $project)`
- a `DELETE /projects/{project:slug}` route handled by `ProjectController@destroy`
- a deletion form protected by `@csrf` and `@method('DELETE')`
- project deletion through `$project->delete()`
- a redirect after creation to `projects.index`
- a flash message displayed in the project index view

## Starting again from scratch

This section is for anyone cloning the repository and wanting to run the Laravel project locally.

### 1. Clone the repository

    git clone https://github.com/gcomneno/web.git
    cd web/laravel-lab/first-project

### 2. Install PHP dependencies

    composer install

### 3. Create the `.env` file

    cp .env.example .env

### 4. Generate the Laravel key

    php artisan key:generate

### 5. Prepare SQLite

    touch database/database.sqlite

### 6. Run the migrations

    php artisan migrate

### 7. Start the local server

    php artisan serve

Then open this address in the browser:

    http://127.0.0.1:8000

## Useful lab pages

Homepage:

    http://127.0.0.1:8000

Project list:

    http://127.0.0.1:8000/projects

Project creation form:

    http://127.0.0.1:8000/projects/create

Project detail page using its slug:

    http://127.0.0.1:8000/projects/a-first-project

Educational Eloquent route:

    http://127.0.0.1:8000/eloquent

## Useful commands during the lab

Show all available Artisan commands:

    php artisan

or:

    php artisan list

Show application information:

    php artisan about

Show the current environment:

    php artisan env

Request help for a command:

    php artisan help make:controller

Show registered routes:

    php artisan route:list

Create a simple Blade view:

    php artisan make:view home

Create a Blade view in a subdirectory:

    php artisan make:view pages.home

Create the project detail view:

    php artisan make:view projects.show

Create the project form view:

    php artisan make:view projects.create

Create the project index view:

    php artisan make:view projects.index

Create a controller:

    php artisan make:controller HomeController

Create the project controller:

    php artisan make:controller ProjectController

Create a migration:

    php artisan make:migration create_projects_table

Create a migration for adding a column:

    php artisan make:migration add_slug_to_projects_table

Run the migrations:

    php artisan migrate

Show migration status:

    php artisan migrate:status

Roll back the latest migration batch locally:

    php artisan migrate:rollback

Create a model:

    php artisan make:model Project

Create a model and migration together:

    php artisan make:model Project -m

Clear compiled views:

    php artisan view:clear

List the view files that have been created:

    find resources/views -maxdepth 3 -type f | sort

List the lesson notes:

    find laravel-lab/lessons -maxdepth 1 -type f | sort

Check that `.env` is not tracked by Git:

    git ls-files | grep -E '(^|/)\.env$' || true

Check that heavy or sensitive local files are not tracked:

    git ls-files | grep -E '(^|/)\.env$|database/database\.sqlite|vendor/|node_modules/|_work/|transcript\.txt$|\.mp4$|\.mp3$' || true

## Local requirements

The project requires:

- PHP 8.3+
- Composer
- Node.js
- npm

For the minimal first run, `vendor/` and `node_modules/` do not need to be stored in the repository: Composer/npm generate them locally.

## Note about video content and transcripts

Course videos, audio files, and full transcripts are not included in the public repository.

Only notes, lessons learned, code produced in the lab, and supporting scripts are included.

# Web-based Lab

[English](README.md) | [Italiano](README.it.md)

Personal laboratory for projects related to the web and the Internet.

This repository contains experiments, notes, and small educational projects related to web development.

## Subprojects

| Directory | Description |
|---|---|
| `laravel-lab/` | Step-by-step Laravel learning laboratory for beginners. |

## Laravel learning path

The first active subproject is `laravel-lab/`.

Start here to follow the Laravel laboratory from scratch:

1. read `laravel-lab/README.md`
2. consult `laravel-lab/GLOSSARY.md` when you encounter unfamiliar terms
3. follow the lessons in order inside `laravel-lab/lessons/`
4. run the local project in `laravel-lab/first-project/`

## Current Laravel laboratory status

The learning path has already covered:

- local installation and setup
- an initial tour of the project skeleton
- basic routes
- basic Blade views
- passing data from routes to views
- configuration through `.env` and `config()`
- moving from closures to controllers
- practical review of Artisan and its main commands
- introduction to database migrations
- creation of the first Laravel model
- initial use of Eloquent
- basic CRUD operations with Eloquent
- mass-assignment protection through `$fillable`
- dynamic route parameters
- retrieving URL-driven data with `findOrFail()`
- passing Eloquent models to Blade views
- basic Carbon date formatting
- route model binding by ID and slug
- addition of the `slug` column to the `projects` table
- the first POST form with `@csrf`
- reading data from `Request`
- creating records from a form with `Project::create()`
- generating slugs with `str()->slug()`
- moving project routes into `ProjectController`
- form validation with `$request->validate()`
- displaying validation errors with `@error`
- restoring submitted form values with `old()`
- success flash messages with `with()` and `@session`

## Content policy

Video files, audio files, and complete course transcripts are not included in the public repository.

Only original notes, lessons learned, laboratory code, and supporting scripts are published.

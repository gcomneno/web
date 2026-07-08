<?php

use App\Http\Controllers\HomeController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');

Route::get('/eloquent', function () {
    dd(Project::all());
})->name('eloquent');

/*
Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');
*/

Route::get('/projects/{project:slug}', function (Project $project) {
    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');

Route::get('/projects/create', function () {
    return view('projects.create');
})->name('projects.create');

Route::post('/projects', function (Request $request) {
    Project::create([
        'name' => $request->name,
        'slug' => str($request->name)->slug(),
    ]);

    return back();
})->name('projects.store');

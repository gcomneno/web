<?php

use App\Http\Controllers\HomeController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');

Route::get('/eloquent', function () {
    dd(Project::all());
})->name('eloquent');

Route::get('/projects/{id}', function (int $id) {
    $project = Project::findOrFail($id);

    return view('projects.show', [
        'project' => $project,
    ]);
})->name('projects.show');

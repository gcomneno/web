<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        Project::create([
            'name' => $request->name,
            'slug' => str($request->name)->slug(),
        ]);

        return back();
    }

    public function show(Project $project)
    {
        return view('projects.show', [
            'project' => $project,
        ]);
    }
}

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

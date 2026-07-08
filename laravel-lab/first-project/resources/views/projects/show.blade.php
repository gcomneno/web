<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->name }}</title>
</head>
<body>
    <h1>{{ $project->name }}</h1>

    <p>Slug: {{ $project->slug }}</p>

    <p>Created: {{ $project->created_at->diffForHumans() }}</p>
</body>
</html>

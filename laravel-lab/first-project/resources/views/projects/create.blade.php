<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New project</title>
</head>
<body>
    <h1>New project</h1>

    @session('status')
        <p>{{ $value }}</p>
    @endsession

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}">

            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Create project</button>
    </form>
</body>
</html>

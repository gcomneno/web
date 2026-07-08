<div>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>New project</title>
    </head>
    <body>
        <h1>New project</h1>

        <form action="/projects" method="POST">
            @csrf

            <div>
                <label for="name">Name</label>
                <input id="name" type="text" name="name">
            </div>

            <button type="submit">Create project</button>
        </form>
    </body>
    </html>
</div>

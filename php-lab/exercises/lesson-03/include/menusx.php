<aside class="col-md-3">
    <h2 class="h5">Filtra catalogo</h2>

    <form method="get" action="prodotti.php">
        <div class="mb-3">
            <label for="filtro" class="form-label">Titolo</label>
            <input
                type="search"
                class="form-control"
                id="filtro"
                name="filtro"
                value="<?= htmlspecialchars($filtro, ENT_QUOTES, "UTF-8") ?>"
            >
        </div>

        <button type="submit" class="btn btn-primary">Cerca</button>
    </form>
</aside>

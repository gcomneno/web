<aside class="col-lg-3">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h2 class="h5 card-title">Filtro</h2>
      <form method="GET" action="prodotti.php" class="vstack gap-3">
        <div>
          <label for="filtro" class="form-label">Titolo</label>
          <input
            type="search"
            class="form-control"
            id="filtro"
            name="filtro"
            value="<?= htmlspecialchars($filtro, ENT_QUOTES, "UTF-8") ?>"
            placeholder="Cerca un titolo"
          >
        </div>
        <div class="d-grid gap-2">
          <button type="submit" id="cerca" name="cerca" class="btn btn-primary">Cerca</button>
          <?php if ($filtro !== ""): ?>
            <a href="prodotti.php" class="btn btn-outline-secondary">Azzera filtro</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</aside>

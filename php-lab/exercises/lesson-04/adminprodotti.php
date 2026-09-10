<?php
require_once __DIR__ . "/include/db.php";
require_once __DIR__ . "/include/crud.php";

$autori = php4_authors($pdo);
$generi = php4_genres($pdo);
?>
<?php include __DIR__ . "/include/header.php"; ?>

<div class="row justify-content-center">
  <div class="col-lg-8 col-xl-7">
    <div class="mb-4">
      <p class="text-uppercase small fw-semibold text-secondary mb-1">Amministrazione</p>
      <h1 class="h2 mb-1">Crea prodotto</h1>
      <p class="text-secondary mb-0">Aggiungi un nuovo brano al catalogo.</p>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <form action="salva.php" method="POST" class="row g-3">
          <div class="col-12">
            <label for="titolo" class="form-label">Titolo</label>
            <input type="text" class="form-control" name="titolo" id="titolo" required>
          </div>

          <div class="col-md-6">
            <label for="autore" class="form-label">Autore</label>
            <select class="form-select" name="autore" id="autore" required>
              <option value="">Seleziona...</option>
              <?php foreach ($autori as $autore): ?>
                <option value="<?= (int) $autore["autore_id"] ?>"><?= htmlspecialchars($autore["nome"], ENT_QUOTES, "UTF-8") ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label for="genere" class="form-label">Genere</label>
            <select class="form-select" name="genere" id="genere">
              <option value="">Non specificato</option>
              <?php foreach ($generi as $genere): ?>
                <option value="<?= (int) $genere["genere_id"] ?>"><?= htmlspecialchars($genere["nome"], ENT_QUOTES, "UTF-8") ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label for="durata" class="form-label">Durata</label>
            <input type="text" class="form-control" name="durata" id="durata" placeholder="es. 3.45">
          </div>

          <div class="col-md-4">
            <label for="anno" class="form-label">Anno</label>
            <input type="number" class="form-control" name="anno" id="anno" min="0" max="9999">
          </div>

          <div class="col-md-4">
            <label for="prezzo" class="form-label">Prezzo</label>
            <div class="input-group">
              <span class="input-group-text">€</span>
              <input type="number" class="form-control" name="prezzo" id="prezzo" min="0" step="0.01" required>
            </div>
          </div>

          <div class="col-12 d-flex gap-2 pt-2">
            <button type="submit" name="crea" class="btn btn-primary">Crea prodotto</button>
            <a href="prodotti.php" class="btn btn-outline-secondary">Annulla</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/include/footer.php"; ?>

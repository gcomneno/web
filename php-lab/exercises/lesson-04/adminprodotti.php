<?php
require_once __DIR__ . "/include/db.php";
require_once __DIR__ . "/include/crud.php";

$autori = php4_authors($pdo);
$generi = php4_genres($pdo);
?>
<?php include __DIR__ . "/include/header.php"; ?>

<h5>Crea prodotto</h5>

<form action="salva.php" method="POST">
  <div>
    <label for="titolo">Titolo</label>
    <input type="text" name="titolo" id="titolo" required>
  </div>

  <div>
    <label for="autore">Autore</label>
    <select name="autore" id="autore" required>
      <option value=""></option>
      <?php foreach ($autori as $autore): ?>
        <option value="<?= (int) $autore["autore_id"] ?>">
          <?= htmlspecialchars($autore["nome"], ENT_QUOTES, "UTF-8") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="genere">Genere</label>
    <select name="genere" id="genere">
      <option value=""></option>
      <?php foreach ($generi as $genere): ?>
        <option value="<?= (int) $genere["genere_id"] ?>">
          <?= htmlspecialchars($genere["nome"], ENT_QUOTES, "UTF-8") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="durata">Durata</label>
    <input type="text" name="durata" id="durata">
  </div>

  <div>
    <label for="anno">Anno</label>
    <input type="number" name="anno" id="anno" min="0" max="9999">
  </div>

  <div>
    <label for="prezzo">Prezzo</label>
    <input type="number" name="prezzo" id="prezzo" min="0" step="0.01" required>
  </div>

  <div>
    <input type="submit" name="crea" value="Crea">
  </div>
</form>

<p><a href="prodotti.php">Torna al catalogo</a></p>

<?php include __DIR__ . "/include/footer.php"; ?>

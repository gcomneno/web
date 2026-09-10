<?php
require_once __DIR__ . "/include/db.php";
require_once __DIR__ . "/include/crud.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit("ID prodotto non valido");
}

$prodotto = php4_find_product($pdo, $id);
if ($prodotto === null) {
    http_response_code(404);
    exit("Prodotto non trovato");
}

$autori = php4_authors($pdo);
$generi = php4_genres($pdo);
?>
<?php include __DIR__ . "/include/header.php"; ?>

<h5>Modifica prodotto</h5>

<form action="aggiorna.php" method="POST">
  <input type="hidden" name="id" value="<?= (int) $prodotto["id"] ?>">

  <div>
    <label for="titolo">Titolo</label>
    <input type="text" name="titolo" id="titolo" required value="<?= htmlspecialchars($prodotto["titolo"], ENT_QUOTES, "UTF-8") ?>">
  </div>

  <div>
    <label for="autore">Autore</label>
    <select name="autore" id="autore" required>
      <?php foreach ($autori as $autore): ?>
        <option value="<?= (int) $autore["autore_id"] ?>"<?= (int) $autore["autore_id"] === (int) $prodotto["autore_id"] ? " selected" : "" ?>>
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
        <option value="<?= (int) $genere["genere_id"] ?>"<?= (int) $genere["genere_id"] === (int) ($prodotto["genere_id"] ?? 0) ? " selected" : "" ?>>
          <?= htmlspecialchars($genere["nome"], ENT_QUOTES, "UTF-8") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="durata">Durata</label>
    <input type="text" name="durata" id="durata" value="<?= htmlspecialchars((string) ($prodotto["durata_minuti"] ?? ""), ENT_QUOTES, "UTF-8") ?>">
  </div>

  <div>
    <label for="anno">Anno</label>
    <input type="number" name="anno" id="anno" min="0" max="9999" value="<?= htmlspecialchars((string) ($prodotto["anno"] ?? ""), ENT_QUOTES, "UTF-8") ?>">
  </div>

  <div>
    <label for="prezzo">Prezzo</label>
    <input type="number" name="prezzo" id="prezzo" min="0" step="0.01" required value="<?= htmlspecialchars((string) $prodotto["prezzo"], ENT_QUOTES, "UTF-8") ?>">
  </div>

  <div><input type="submit" value="Salva modifiche"></div>
</form>

<p><a href="dettaglioprodotto.php?id=<?= (int) $prodotto["id"] ?>">Annulla</a></p>

<?php include __DIR__ . "/include/footer.php"; ?>

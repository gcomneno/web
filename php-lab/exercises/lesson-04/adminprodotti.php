<?php
require_once __DIR__ . "/include/db.php";

$stmt = $pdo->query("
    SELECT autore_id, nome
    FROM autori
    ORDER BY nome
");
$autori = $stmt->fetchAll();
?>
<?php include __DIR__ . "/include/header.php"; ?>

<h5>Crea prodotto</h5>

  <form action="salva.php" method="POST">
    <div>
      <label for="titolo">Titolo</label>
      <input type="text" name="titolo" id="titolo">
    </div>

    <div>
      <label for="autore">Autore</label>
      <select name="autore" id="autore">
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
        <option value="1">genere 1</option>
        <option value="2">genere 2</option>
        <option value="3">genere 3</option>
      </select>
    </div>

    <div>
      <label for="durata">Durata</label>
      <input type="text" name="durata" id="durata">
    </div>

    <div>
      <label for="anno">Anno</label>
      <input type="text" name="anno" id="anno">
    </div>

    <div>
      <label for="prezzo">Prezzo</label>
      <input type="text" name="prezzo" id="prezzo">
    </div>

    <div>
      <label for="descrizione">Descrizione</label>
      <textarea name="descrizione" id="descrizione"></textarea>
    </div>

    <div>
      <input type="submit" name="crea" value="Crea">
    </div>
  </form>

<?php include __DIR__ . "/include/footer.php"; ?>

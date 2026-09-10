<?php
require_once __DIR__ . "/include/db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit("ID prodotto non valido");
}

$stmt = $pdo->prepare("
    SELECT
        b.id,
        b.titolo,
        a.nome AS autore,
        g.nome AS genere,
        b.durata_minuti,
        b.anno,
        b.prezzo
    FROM brani b
    INNER JOIN autori a ON b.autore_id = a.autore_id
    LEFT JOIN generi g ON b.genere_id = g.genere_id
    WHERE b.id = :id
");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$prodotto = $stmt->fetch();

if ($prodotto === false) {
    http_response_code(404);
    exit("Prodotto non trovato");
}
?>
<?php include __DIR__ . "/include/header.php"; ?>

<h5><?= htmlspecialchars($prodotto["titolo"], ENT_QUOTES, "UTF-8") ?></h5>
<dl>
  <dt>Autore</dt>
  <dd><?= htmlspecialchars($prodotto["autore"], ENT_QUOTES, "UTF-8") ?></dd>
  <dt>Genere</dt>
  <dd><?= htmlspecialchars($prodotto["genere"] ?? "Non specificato", ENT_QUOTES, "UTF-8") ?></dd>
  <dt>Durata</dt>
  <dd><?= htmlspecialchars((string) ($prodotto["durata_minuti"] ?? ""), ENT_QUOTES, "UTF-8") ?></dd>
  <dt>Anno</dt>
  <dd><?= htmlspecialchars((string) ($prodotto["anno"] ?? ""), ENT_QUOTES, "UTF-8") ?></dd>
  <dt>Prezzo</dt>
  <dd>€ <?= number_format((float) $prodotto["prezzo"], 2, ",", ".") ?></dd>
</dl>

<p>
  <a href="modificaprodotto.php?id=<?= (int) $prodotto["id"] ?>">Modifica</a>
  · <a href="eliminaprodotto.php?id=<?= (int) $prodotto["id"] ?>">Elimina</a>
  · <a href="prodotti.php">Catalogo</a>
</p>

<?php include __DIR__ . "/include/footer.php"; ?>

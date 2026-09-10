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

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
      <div>
        <p class="text-uppercase small fw-semibold text-secondary mb-1">Dettaglio prodotto</p>
        <h1 class="h2 mb-0"><?= htmlspecialchars($prodotto["titolo"], ENT_QUOTES, "UTF-8") ?></h1>
      </div>
      <span class="badge text-bg-secondary">#<?= (int) $prodotto["id"] ?></span>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <dl class="row mb-0">
          <dt class="col-sm-4 text-secondary fw-normal">Autore</dt>
          <dd class="col-sm-8 fw-semibold"><?= htmlspecialchars($prodotto["autore"], ENT_QUOTES, "UTF-8") ?></dd>
          <dt class="col-sm-4 text-secondary fw-normal">Genere</dt>
          <dd class="col-sm-8"><?= htmlspecialchars($prodotto["genere"] ?? "Non specificato", ENT_QUOTES, "UTF-8") ?></dd>
          <dt class="col-sm-4 text-secondary fw-normal">Durata</dt>
          <dd class="col-sm-8"><?= htmlspecialchars((string) ($prodotto["durata_minuti"] ?? "—"), ENT_QUOTES, "UTF-8") ?></dd>
          <dt class="col-sm-4 text-secondary fw-normal">Anno</dt>
          <dd class="col-sm-8"><?= htmlspecialchars((string) ($prodotto["anno"] ?? "—"), ENT_QUOTES, "UTF-8") ?></dd>
          <dt class="col-sm-4 text-secondary fw-normal">Prezzo</dt>
          <dd class="col-sm-8 fs-5 fw-semibold mb-0">€ <?= number_format((float) $prodotto["prezzo"], 2, ",", ".") ?></dd>
        </dl>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-3 mt-4">
      <a href="modificaprodotto.php?id=<?= (int) $prodotto["id"] ?>" class="btn btn-primary">Modifica</a>
      <a href="eliminaprodotto.php?id=<?= (int) $prodotto["id"] ?>" class="btn btn-outline-danger">Elimina</a>
      <a href="prodotti.php" class="btn btn-link text-decoration-none">Torna al catalogo</a>
    </div>
  </div>
</div>

<?php include __DIR__ . "/include/footer.php"; ?>

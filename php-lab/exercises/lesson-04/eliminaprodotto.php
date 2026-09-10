<?php
require_once __DIR__ . "/include/db.php";
require_once __DIR__ . "/include/crud.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1) {
        http_response_code(400);
        exit("ID prodotto non valido");
    }

    if (php4_find_product($pdo, $id) === null) {
        http_response_code(404);
        exit("Prodotto non trovato");
    }

    $stmt = $pdo->prepare("DELETE FROM brani WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    php4_redirect("prodotti.php?deleted=1");
}

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
?>
<?php include __DIR__ . "/include/header.php"; ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card border-danger shadow-sm">
      <div class="card-body p-4">
        <span class="badge text-bg-danger mb-3">Operazione distruttiva</span>
        <h1 class="h3">Elimina prodotto</h1>
        <p class="text-secondary">Stai per eliminare definitivamente:</p>
        <p class="fs-5 fw-semibold mb-4"><?= htmlspecialchars($prodotto["titolo"], ENT_QUOTES, "UTF-8") ?></p>

        <div class="alert alert-warning" role="alert">
          L'eliminazione non può essere annullata.
        </div>

        <form method="POST" action="eliminaprodotto.php" class="d-flex flex-wrap gap-2">
          <input type="hidden" name="id" value="<?= (int) $prodotto["id"] ?>">
          <button type="submit" class="btn btn-danger">Conferma eliminazione</button>
          <a href="prodotti.php" class="btn btn-outline-secondary">Annulla</a>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/include/footer.php"; ?>

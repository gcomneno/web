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

<h5>Elimina prodotto</h5>
<p>Confermi l'eliminazione di <strong><?= htmlspecialchars($prodotto["titolo"], ENT_QUOTES, "UTF-8") ?></strong>?</p>

<form method="POST" action="eliminaprodotto.php">
  <input type="hidden" name="id" value="<?= (int) $prodotto["id"] ?>">
  <input type="submit" value="Conferma eliminazione">
</form>

<p><a href="prodotti.php">Annulla</a></p>

<?php include __DIR__ . "/include/footer.php"; ?>

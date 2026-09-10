<?php
require_once __DIR__ . "/include/db.php";
require_once __DIR__ . "/include/crud.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Metodo non consentito");
}

$result = php4_validate_product($pdo, $_POST);

if ($result["errors"] !== []) {
    http_response_code(422);
    header("Content-Type: text/html; charset=UTF-8");
    echo "<h1>Dati non validi</h1><ul>";
    foreach ($result["errors"] as $error) {
        echo "<li>" . htmlspecialchars($error, ENT_QUOTES, "UTF-8") . "</li>";
    }
    echo "</ul><p><a href=\"adminprodotti.php\">Torna al form</a></p>";
    exit;
}

$data = $result["data"];
$stmt = $pdo->prepare("
    INSERT INTO brani (titolo, autore_id, genere_id, durata_minuti, anno, prezzo)
    VALUES (:titolo, :autore_id, :genere_id, :durata_minuti, :anno, :prezzo)
");
$stmt->bindValue(":titolo", $data["titolo"], PDO::PARAM_STR);
$stmt->bindValue(":autore_id", $data["autore_id"], PDO::PARAM_INT);
if ($data["genere_id"] === null) {
    $stmt->bindValue(":genere_id", null, PDO::PARAM_NULL);
} else {
    $stmt->bindValue(":genere_id", $data["genere_id"], PDO::PARAM_INT);
}
$stmt->bindValue(":durata_minuti", $data["durata_minuti"]);
if ($data["anno"] === null) {
    $stmt->bindValue(":anno", null, PDO::PARAM_NULL);
} else {
    $stmt->bindValue(":anno", $data["anno"], PDO::PARAM_INT);
}
$stmt->bindValue(":prezzo", $data["prezzo"]);
$stmt->execute();

$id = (int) $pdo->lastInsertId();
php4_redirect("dettaglioprodotto.php?id=" . $id . "&created=1");

<?php

require_once __DIR__ . "/db.php";

$filtro = trim($_GET["filtro"] ?? "");
$pagina = max(1, (int) ($_GET["pagina"] ?? 1));
$recordPerPagina = 12;
$offset = ($pagina - 1) * $recordPerPagina;

$sql = "
    SELECT
        b.id,
        b.titolo,
        a.nome AS autore,
        g.nome AS genere,
        b.durata_minuti,
        b.anno
    FROM brani b
    INNER JOIN autori a ON b.autore_id = a.autore_id
    LEFT JOIN generi g ON b.genere_id = g.genere_id
    WHERE b.titolo LIKE :filtro
    ORDER BY b.titolo
    LIMIT :limite OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":filtro", "%" . $filtro . "%", PDO::PARAM_STR);
$stmt->bindValue(":limite", $recordPerPagina, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();

$prodotti = $stmt->fetchAll();

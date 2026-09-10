<?php

function php4_redirect(string $location): never
{
    header("Location: " . $location);
    exit;
}

function php4_find_product(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("
        SELECT id, titolo, autore_id, genere_id, durata_minuti, anno, prezzo
        FROM brani
        WHERE id = :id
    ");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $product = $stmt->fetch();
    return $product === false ? null : $product;
}

function php4_authors(PDO $pdo): array
{
    return $pdo->query("SELECT autore_id, nome FROM autori ORDER BY nome")->fetchAll();
}

function php4_genres(PDO $pdo): array
{
    return $pdo->query("SELECT genere_id, nome FROM generi ORDER BY nome")->fetchAll();
}

function php4_validate_product(PDO $pdo, array $input): array
{
    $titolo = trim((string) ($input["titolo"] ?? ""));
    $autoreId = filter_var($input["autore"] ?? null, FILTER_VALIDATE_INT);
    $genereRaw = $input["genere"] ?? "";
    $genereId = $genereRaw === "" ? null : filter_var($genereRaw, FILTER_VALIDATE_INT);
    $durataRaw = trim((string) ($input["durata"] ?? ""));
    $annoRaw = trim((string) ($input["anno"] ?? ""));
    $prezzoRaw = str_replace(",", ".", trim((string) ($input["prezzo"] ?? "")));

    $errors = [];

    if ($titolo === "") {
        $errors[] = "Il titolo è obbligatorio.";
    }

    if ($autoreId === false || $autoreId < 1) {
        $errors[] = "L'autore è obbligatorio.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM autori WHERE autore_id = :id");
        $stmt->bindValue(":id", $autoreId, PDO::PARAM_INT);
        $stmt->execute();
        if ((int) $stmt->fetchColumn() !== 1) {
            $errors[] = "L'autore selezionato non esiste.";
        }
    }

    if ($genereId === false || ($genereId !== null && $genereId < 1)) {
        $errors[] = "Il genere non è valido.";
    } elseif ($genereId !== null) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM generi WHERE genere_id = :id");
        $stmt->bindValue(":id", $genereId, PDO::PARAM_INT);
        $stmt->execute();
        if ((int) $stmt->fetchColumn() !== 1) {
            $errors[] = "Il genere selezionato non esiste.";
        }
    }

    $durata = null;
    if ($durataRaw !== "") {
        if (!is_numeric($durataRaw) || (float) $durataRaw < 0) {
            $errors[] = "La durata deve essere numerica e non negativa.";
        } else {
            $durata = (float) $durataRaw;
        }
    }

    $anno = null;
    if ($annoRaw !== "") {
        $anno = filter_var($annoRaw, FILTER_VALIDATE_INT);
        if ($anno === false || $anno < 0 || $anno > 9999) {
            $errors[] = "L'anno non è valido.";
        }
    }

    if ($prezzoRaw === "" || !is_numeric($prezzoRaw) || (float) $prezzoRaw < 0) {
        $errors[] = "Il prezzo deve essere numerico e non negativo.";
        $prezzo = null;
    } else {
        $prezzo = round((float) $prezzoRaw, 2);
    }

    return [
        "errors" => $errors,
        "data" => [
            "titolo" => $titolo,
            "autore_id" => $autoreId === false ? null : $autoreId,
            "genere_id" => $genereId === false ? null : $genereId,
            "durata_minuti" => $durata,
            "anno" => $anno === false ? null : $anno,
            "prezzo" => $prezzo,
        ],
    ];
}

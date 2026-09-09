<?php

$fields = [
    "titolo",
    "autore",
    "genere",
    "durata",
    "anno",
    "prezzo",
    "descrizione",
];

$postData = [];

foreach ($fields as $field) {
    $postData[$field] = $_POST[$field] ?? "";
}

header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>PHP 4 - Dati ricevuti</title>
</head>
<body>

<h1>Dati POST ricevuti</h1>

<dl>
<?php foreach ($postData as $field => $value): ?>
  <dt><?= htmlspecialchars($field, ENT_QUOTES, "UTF-8") ?></dt>
  <dd><?= htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8") ?></dd>
<?php endforeach; ?>
</dl>

<p>Nessun dato è stato salvato nel database.</p>

</body>
</html>

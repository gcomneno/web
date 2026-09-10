<?php
$id = (int) $prod["id"];
$titolo = htmlspecialchars($prod["titolo"], ENT_QUOTES, "UTF-8");
$autore = htmlspecialchars($prod["autore"], ENT_QUOTES, "UTF-8");
$genere = htmlspecialchars($prod["genere"] ?? "Non specificato", ENT_QUOTES, "UTF-8");
$prezzo = number_format((float) $prod["prezzo"], 2, ",", ".");
?>

<div class="col-md-4 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <h6 class="card-title"><a href="dettaglioprodotto.php?id=<?= $id ?>"><?= $titolo ?></a></h6>
      <p class="card-text">Autore: <?= $autore ?></p>
      <p class="card-text">Genere: <?= $genere ?></p>
      <p class="card-text">Prezzo: € <?= $prezzo ?></p>
      <p class="card-text">Cod. prodotto: <?= $id ?></p>
      <p class="card-text">
        <a href="modificaprodotto.php?id=<?= $id ?>">Modifica</a>
        · <a href="eliminaprodotto.php?id=<?= $id ?>">Elimina</a>
      </p>
    </div>
  </div>
</div>

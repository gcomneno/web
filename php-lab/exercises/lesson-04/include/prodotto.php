<?php
$id = (int) $prod["id"];
$titolo = htmlspecialchars($prod["titolo"], ENT_QUOTES, "UTF-8");
$autore = htmlspecialchars($prod["autore"], ENT_QUOTES, "UTF-8");
$genere = htmlspecialchars($prod["genere"] ?? "Non specificato", ENT_QUOTES, "UTF-8");
$prezzo = number_format((float) $prod["prezzo"], 2, ",", ".");
?>

<div class="col-md-6 col-xl-4">
  <article class="card h-100 border-0 shadow-sm">
    <div class="card-body d-flex flex-column">
      <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <h3 class="h5 card-title mb-0">
          <a class="link-dark text-decoration-none stretched-link" href="dettaglioprodotto.php?id=<?= $id ?>"><?= $titolo ?></a>
        </h3>
        <span class="badge text-bg-light">#<?= $id ?></span>
      </div>

      <dl class="row small mb-3">
        <dt class="col-5 text-secondary fw-normal">Autore</dt>
        <dd class="col-7 mb-2"><?= $autore ?></dd>
        <dt class="col-5 text-secondary fw-normal">Genere</dt>
        <dd class="col-7 mb-2"><?= $genere ?></dd>
        <dt class="col-5 text-secondary fw-normal">Prezzo</dt>
        <dd class="col-7 mb-0 fw-semibold">€ <?= $prezzo ?></dd>
      </dl>

      <div class="mt-auto pt-3 border-top position-relative" style="z-index: 2">
        <a class="link-primary text-decoration-none" href="modificaprodotto.php?id=<?= $id ?>">Modifica</a>
        <span class="text-secondary mx-1">·</span>
        <a class="link-danger text-decoration-none" href="eliminaprodotto.php?id=<?= $id ?>">Elimina</a>
      </div>
    </div>
  </article>
</div>

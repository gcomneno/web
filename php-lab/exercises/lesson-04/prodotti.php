<?php

require_once __DIR__ . "/include/data.php";
include __DIR__ . "/include/header.php";
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
  <div>
    <p class="text-uppercase small fw-semibold text-secondary mb-1">Lezione PHP 4</p>
    <h1 class="display-6 fw-bold mb-1">Catalogo prodotti</h1>
    <p class="text-secondary mb-0">Catalogo MySQL con paginazione, filtro e gestione CRUD completa.</p>
  </div>
  <a class="btn btn-primary" href="adminprodotti.php">Aggiungi prodotto</a>
</div>

<div class="row g-4">
  <?php include __DIR__ . "/include/menusx.php"; ?>

  <section class="col-lg-9">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="h4 mb-0">Prodotti</h2>
      <span class="badge text-bg-secondary"><?= (int) $totale ?> risultati</span>
    </div>

    <div class="row g-4">
      <?php foreach ($prodotti as $prod): ?>
        <?php include __DIR__ . "/include/prodotto.php"; ?>
      <?php endforeach; ?>
    </div>

    <div class="mt-4">
      <?php include __DIR__ . "/include/pager.php"; ?>
    </div>
  </section>
</div>

<?php include __DIR__ . "/include/footer.php"; ?>

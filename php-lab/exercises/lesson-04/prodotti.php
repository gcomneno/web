<?php

require_once __DIR__ . "/include/data.php";
include __DIR__ . "/include/header.php";
?>

<h1 class="mb-4">Catalogo prodotti</h1>

<div class="row">
  <?php include __DIR__ . "/include/menusx.php"; ?>

  <section class="col-md-9">
    <h5>Prodotti</h5>

    <div class="row">
      <?php foreach ($prodotti as $prod): ?>
        <?php include __DIR__ . "/include/prodotto.php"; ?>
      <?php endforeach; ?>
    </div>

    <?php include __DIR__ . "/include/pager.php"; ?>
  </section>
</div>

<?php include __DIR__ . "/include/footer.php"; ?>

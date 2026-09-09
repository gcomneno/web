<?php

require_once __DIR__ . "/include/data.php";
include __DIR__ . "/include/header.php";
?>

<h1 class="mb-4">Catalogo musicale</h1>

<div class="row g-4">
    <?php include __DIR__ . "/include/menusx.php"; ?>

    <section class="col-md-9">
        <div class="row g-3">
            <?php foreach ($prodotti as $prod): ?>
                <?php include __DIR__ . "/include/prodotto.php"; ?>
            <?php endforeach; ?>
        </div>

        <nav class="mt-4" aria-label="Paginazione catalogo">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="?pagina=1&amp;filtro=<?= urlencode($filtro) ?>">1</a></li>
                <li class="page-item"><a class="page-link" href="?pagina=2&amp;filtro=<?= urlencode($filtro) ?>">2</a></li>
                <li class="page-item"><a class="page-link" href="?pagina=3&amp;filtro=<?= urlencode($filtro) ?>">3</a></li>
            </ul>
        </nav>
    </section>
</div>

<?php include __DIR__ . "/include/footer.php"; ?>

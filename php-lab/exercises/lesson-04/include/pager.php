<?php if ($numPagine > 1): ?>
<nav aria-label="Paginazione prodotti">
  <p>Numero prodotti: <?= $numProdotti ?></p>

  <ul class="pagination">
    <?php for ($pag = 1; $pag <= $numPagine; $pag++): ?>
      <?php
      $query = http_build_query([
          "filtro" => $filtro,
          "pagina" => $pag,
      ]);
      ?>
      <li class="page-item<?= $pag === $pagina ? " active" : "" ?>">
        <a
          class="page-link"
          href="prodotti.php?<?= htmlspecialchars($query, ENT_QUOTES, "UTF-8") ?>"
        ><?= $pag ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>

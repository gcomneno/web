<div class="col-md-3 mb-4">
  <h5>Filtro</h5>

  <form method="GET" action="prodotti.php">
    <input
      type="text"
      id="filtro"
      name="filtro"
      value="<?= htmlspecialchars($filtro, ENT_QUOTES, "UTF-8") ?>"
    >
    <input
      type="submit"
      id="cerca"
      name="cerca"
      value="cerca"
      class="btn btn-primary"
    >
  </form>

  <h5>Categorie Kleis</h5>
  <div class="list-group">
    <a href="#" class="list-group-item list-group-item-action active">Categoria 1</a>
    <a href="#" class="list-group-item list-group-item-action">Categoria 2</a>
    <a href="#" class="list-group-item list-group-item-action">Categoria 3</a>
  </div>
</div>

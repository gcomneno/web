<div class="col-md-4">
    <article class="card h-100">
        <div class="card-body">
            <h2 class="h5 card-title"><?= htmlspecialchars($prod["titolo"], ENT_QUOTES, "UTF-8") ?></h2>
            <p class="card-text mb-1">
                <strong>Autore:</strong>
                <?= htmlspecialchars($prod["autore"], ENT_QUOTES, "UTF-8") ?>
            </p>
            <p class="card-text mb-1">
                <strong>Genere:</strong>
                <?= htmlspecialchars($prod["genere"] ?? "Non specificato", ENT_QUOTES, "UTF-8") ?>
            </p>
            <p class="card-text mb-1">
                <strong>Durata:</strong>
                <?= htmlspecialchars((string) ($prod["durata_minuti"] ?? "Non specificata"), ENT_QUOTES, "UTF-8") ?>
            </p>
            <p class="card-text">
                <strong>Anno:</strong>
                <?= htmlspecialchars((string) ($prod["anno"] ?? "Non specificato"), ENT_QUOTES, "UTF-8") ?>
            </p>
        </div>
    </article>
</div>

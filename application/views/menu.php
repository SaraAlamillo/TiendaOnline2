<ul>
    <?php foreach ($categorias as $categoria): ?>
        <li><?= anchor("home/index/$categoria->id", $categoria->nombre) ?></li>
    <?php endforeach; ?>
</ul>
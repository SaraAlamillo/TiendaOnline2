<ul>
    <li><?= anchor("", "Home") ?></li>
    <?php foreach ($categorias as $categoria): ?>
        <li><?= anchor("home/ver_categoria/$categoria->id", $categoria->nombre) ?></li>
    <?php endforeach; ?>
    <hr />
    <li><?= anchor("home/consultar_carrito", "Ver contenido del carrito") ?></li>
</ul>
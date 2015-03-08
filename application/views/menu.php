<ul>
    <li><?= anchor("", "Home") ?></li>
    <?php foreach ($categorias as $categoria): ?>
        <li><?= anchor("home/ver_categoria/$categoria->id", $categoria->nombre) ?></li>
    <?php endforeach; ?>
    <hr />
    <li><?= anchor("compra/consultar_carrito", "Ver contenido del carrito") ?></li>
    <hr />
    <li><?= anchor("xml/subir_fichero", "Importar datos") ?></li>
    <li><?= anchor("xml/exportar", "Exportar datos") ?></li>
</ul>
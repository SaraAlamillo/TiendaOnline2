<ul>
    <?php foreach ($categorias as $categoria): ?>
        <li><?= anchor("home/index/$categoria->id", $categoria->nombre) ?></li>
    <?php endforeach; ?>
</ul>
<hr />
<?php if ($logueado): ?>
    <p>Dentro</p>
    <p><?= anchor("home/consultarPedidos", "Ver pedidos") ?></p>
    <p><?= anchor("home/cerrarSesion", "Cerrar sesión") ?></p>
<?php else: ?>
    <form action="<?= site_url("home/acceder") ?>" method="POST">
        Usuario: <input type="text" name="usuario" /><br />
        Contraseña: <input type="text" name="clave" /><br />
        <input type="submit" value="Acceder" />
    </form>
<?php endif; ?>
<hr />
<p><?= anchor("home/consultarCarrito", "Ver contenido del carrito") ?></p>
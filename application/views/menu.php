<ul>
    <li><?= anchor("", "Home") ?></li>
    <?php foreach ($categorias as $categoria): ?>
        <li><?= anchor("home/ver_categoria/$categoria->id", $categoria->nombre) ?></li>
    <?php endforeach; ?>
</ul>
<hr />
<?php if ($logueado): ?>
    <p>Dentro</p>
    <p><?= anchor("home/consultar_pedidos", "Ver pedidos") ?></p>
    <p><?= anchor("home/cerrar_sesion", "Cerrar sesión") ?></p>
<?php else: ?>
    <form action="<?= site_url("home/acceder") ?>" method="POST">
        Usuario: <input type="text" name="usuario" /><br />
        Contraseña: <input type="text" name="clave" /><br />
        <input type="submit" value="Acceder" />
    </form>
    <p><?= anchor("home/registrar_usuario", "Nuevo usuario") ?></p>
<?php endif; ?>
<hr />
<p><?= anchor("home/consultar_carrito", "Ver contenido del carrito") ?></p>
<div class="cabecera2">
    <h1 id="titulo">SaraSoft - Tienda Online</h1>
    <div class="controles-cabecera">
        <?php if ($logueado): ?>
            <p><?= anchor("usuarios/modificacion", "Mi cuenta") ?></p>
            <p><?= anchor("pedido/listado", "Ver pedidos") ?></p>
            <p><?= anchor("usuarios/salir", "Cerrar sesión") ?></p>
        <?php else: ?>
            <form action="<?= site_url("usuarios/acceder") ?>" method="POST">
                Usuario: <input type="text" name="usuario" /><br />
                Contraseña: <input type="text" name="clave" /><br />
                <input type="submit" value="Acceder" />
                <input type="hidden" value="<?= current_url() ?>" name="url" />
                <p><?= anchor("usuarios/registro", "Nuevo usuario") ?></p>
            </form>
            <?php if (isset($login)): ?>
                <p><?= $login ?> </p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

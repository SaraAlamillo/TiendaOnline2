<div class="cabecera2">
    <h1 id="titulo">SaraSoft - Tienda Online</h1>
    <div class="controles-cabecera">
        <?php if ($logueado): ?>
    <p><?= anchor("usuarios/modificacion", "Mi cuenta") ?></p>
    <p><?= anchor("home/consultar_pedidos", "Ver pedidos") ?></p>
    <p><?= anchor("home/cerrar_sesion", "Cerrar sesión") ?></p>
<?php else: ?>
    <form action="<?= site_url("home/acceder") ?>" method="POST">
        Usuario: <input type="text" name="usuario" /><br />
        Contraseña: <input type="text" name="clave" /><br />
        <input type="submit" value="Acceder" />
        
    <p><?= anchor("usuarios/registro", "Nuevo usuario") ?></p>
    </form>
<?php endif; ?>
    </div>
</div>

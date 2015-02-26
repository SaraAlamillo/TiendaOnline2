<div id="registro">
    <?php if (!is_null($mensaje)): ?>
    <p><?=$mensaje ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        Usuario <input type="text" name="usuario" value="<?=set_value('usuario', $datos->usuario) ?>" /> <br />
        <?= form_error('usuario') . "<br />" ?>
        Contrasenia <input type="text" name="contrasenia" value="<?=set_value('contrasenia', $datos->contrasenia) ?>" /> <br />
        <?= form_error('contrasenia') . "<br />" ?>
        Correo electrónico <input type="text" name="email" value="<?=set_value('email', $datos->email) ?>" /> <br />
        <?= form_error('email') . "<br />" ?>
        Nombre <input type="text" name="nombre" value="<?=set_value('nombre', $datos->nombre) ?>" /> <br />
        <?= form_error('nombre') . "<br />" ?>
        Apellidos <input type="text" name="apellidos" value="<?=set_value('apellidos', $datos->apellidos) ?>" /> <br />
        <?= form_error('apellidos') . "<br />" ?>
        DNI  <input type="text" name="dni" value="<?=set_value('dni', $datos->dni) ?>" /> <br />
        <?= form_error('dni') . "<br />" ?>
        Dirección <input type="text" name="direccion" value="<?=set_value('direccion', $datos->direccion) ?>" /> <br />
        <?= form_error('direccion') . "<br />" ?>
        Código postal <input type="text" name="cp" value="<?=set_value('cp', $datos->cp) ?>" /> <br />
        <?= form_error('cp') . "<br />" ?>
        Provincia <?= creaListaDesplegable("provincia", $provincias, set_value('provincia', $datos->provincia), ['nombre' => "", 'id' => ""]) ?> <br />
        <?= form_error('provincia') . "<br />" ?>
        <input type="submit" value="Completar registro" />
    </form>
    <button><?= anchor("usuarios/quitar_usuario", "Darme de baja") ?></button>
</div>
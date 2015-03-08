<div id="registro">
    <form action="" method="POST">
        Usuario <input type="text" name="usuario" value="<?=set_value('usuario') ?>" /> <br />
        <?= form_error('usuario') . "<br />" ?>
        Contraseña <input type="text" name="contrasenia" value="<?=set_value('contrasenia') ?>" /> <br />
        <?= form_error('contrasenia') . "<br />" ?>
        Correo electrónico <input type="text" name="email" value="<?=set_value('email') ?>" /> <br />
        <?= form_error('email') . "<br />" ?>
        Nombre <input type="text" name="nombre" value="<?=set_value('nombre') ?>" /> <br />
        <?= form_error('nombre') . "<br />" ?>
        Apellidos <input type="text" name="apellidos" value="<?=set_value('apellidos') ?>" /> <br />
        <?= form_error('apellidos') . "<br />" ?>
        DNI  <input type="text" name="dni" value="<?=set_value('dni') ?>" /> <br />
        <?= form_error('dni') . "<br />" ?>
        Dirección <input type="text" name="direccion" value="<?=set_value('direccion') ?>" /> <br />
        <?= form_error('direccion') . "<br />" ?>
        Código postal <input type="text" name="cp" value="<?=set_value('cp') ?>" /> <br />
        <?= form_error('cp') . "<br />" ?>
        Provincia <?= creaListaDesplegable("provincia", $provincias, set_value('provincia'), ['nombre' => "", 'id' => ""]) ?> <br />
        <?= form_error('provincia') . "<br />" ?>
        <input type="submit" value="Completar registro" />
    </form>
</div>
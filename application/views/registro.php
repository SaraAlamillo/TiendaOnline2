<div id="registro">
    <?= validation_errors() ?>
    <form action="" method="POST">
        Usuario <input type="text" name="usuario" value="<?=set_value('usuario') ?>" /> <br />
        Contrasenia <input type="text" name="contrasenia" value="<?=set_value('contrasenia') ?>" /> <br />
        Correo electrónico <input type="text" name="email" value="<?=set_value('email') ?>" /> <br />
        Nombre <input type="text" name="nombre" value="<?=set_value('nombre') ?>" /> <br />
        Apellidos <input type="text" name="apellidos" value="<?=set_value('apellidos') ?>" /> <br />
        DNI  <input type="text" name="dni" value="<?=set_value('dni') ?>" /> <br />
        Dirección <input type="text" name="direccion" value="<?=set_value('direccion') ?>" /> <br />
        Código postal <input type="text" name="cp" value="<?=set_value('cp') ?>" /> <br />
        Provincia <?= creaListaDesplegable("provincia", $provincias, set_value('provincia'), ['nombre' => "", 'id' => ""]) ?> <br />
        <input type="submit" value="Completar registro" />
    </form>
</div>
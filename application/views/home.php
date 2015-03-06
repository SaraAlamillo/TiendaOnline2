<!DOCTYPE html>
<html>
    <head>
        <title>Tienda Online</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
        <link href="<?= base_url() ?>assets/css/estilos.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <section id="cabecera">
            <?= $cabecera ?>
        </section>
        <section id="principal">
            <div id="menu-lateral">
                <?= $menu ?>
            </div>
            <div id="contenido">
                <?= $contenido ?>
            </div>
        </section>
        <section id="footer">
            <div class="footer1"></div>
            <div class="footer2"></div>
        </section>
    </body>
</html>


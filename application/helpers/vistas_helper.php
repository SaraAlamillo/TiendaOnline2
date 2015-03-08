<?php
if (!function_exists('generarVistaProducto')) {

    function generarVistaProducto($producto, $error, $version_extendida = FALSE) {
        ob_start();
        ?>
        <div id="producto">
            <form action="<?= site_url("compra/agregar") ?>" method="POST">
                <?php if ($version_extendida): ?>
                    <p><?= anchor("home/ver_categoria/$producto->categoria", "Volver a la categoría") ?></p>
                <?php endif; ?>
                <img src="<?= base_url() ?>assets/img/productos/<?= $producto->imagen ?>" />
                <p><?= $producto->nombre ?></p>
                <p>Stock: <?= $producto->stock ?></p>
                <?php if ($producto->descuento != 0): ?>
                    <p>Precio: <?= round($producto->precio - ($producto->precio * ($producto->descuento / 100)), 2) ?> €</p>
                <?php else: ?>
                    <p>Precio: <?= $producto->precio ?> €</p>
                <?php endif; ?>
                <?php if ($version_extendida): ?>
                    <p>Categoria: <?= $producto->categoria_nombre ?> </p>
                    <p>IVA(<?= $producto->iva ?>%): <?= round($producto->iva / 100 * ($producto->precio - ($producto->precio * ($producto->descuento / 100))), 2) ?> €</p>
                <?php if ($producto->descuento != 0): ?>
                    <p>Precio real: <?= $producto->precio ?> €</p>
                    <p>Descuento: <?= $producto->descuento ?>%</p>
                    <?php endif; ?>
                    <p>Descripcion:</p>
                    <p><?= $producto->descripcion ?> </p>
                <?php else: ?>
                    <p><?= anchor("home/ver_producto/$producto->id", "Más información") ?></p>
                <?php endif; ?>
                <input type="hidden" name="id" value="<?= $producto->id ?>" />
                <input type="hidden" name="url" value="<?= current_url() ?>" />
                <p>Cantidad: <input type="text" size="5" name="cantidad" value="" /><input type="submit" value="Comprar" /></p>
                <?php if ($error['id'] == $producto->id): ?>
                    <p><?= $error['mensaje'] ?></p>
                <?php endif; ?>
            </form>
        </div>


        <?php
        $contenido = ob_get_clean();

        return $contenido;
    }

}
if (!function_exists('creaListaDesplegable')) {

    function creaListaDesplegable(
    $nombre, $datos, $valorPorDefecto = NULL, $nullValue = NULL, $camposDatos = ['desc' => 'nombre', 'valor' => 'id']) {
        $html = "<select name='$nombre'>\n";

        if (is_array($nullValue)) {
            if ($nullValue[$camposDatos['valor']] == $valorPorDefecto) {
                $html .= "<option value='{$nullValue[$camposDatos['valor']]}' selected='selected'>{$nullValue[$camposDatos['desc']]}</option>\n";
            } else {
                $html .= "<option value='{$nullValue[$camposDatos['valor']]}'>{$nullValue[$camposDatos['desc']]}</option>\n";
            }
        }

        foreach ($datos as $d) {
            if ($d->$camposDatos['valor'] == $valorPorDefecto) {
                $html .= "<option value='{$d->$camposDatos['valor']}' selected='selected'>{$d->$camposDatos['desc']}</option>\n";
            } else {
                $html .= "<option value='{$d->$camposDatos['valor']}'>{$d->$camposDatos['desc']}</option>\n";
            }
        }

        $html .= "</select>\n";

        return $html;
    }

}
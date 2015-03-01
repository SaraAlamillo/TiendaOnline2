<?php
if (!function_exists('generarVistaProducto')) {

    function generarVistaProducto($producto, $error) {
	ob_start();
	?>
	<div id="producto">
	    <form action="<?= site_url("home/comprar") ?>" method="POST">
		<img src="<?= base_url() ?>assets/img/productos/<?= $producto->imagen ?>" />
		<p><?= $producto->nombre ?></p>
		<p>Stock: <?= $producto->stock ?></p>
		<?php if ($producto->descuento != 0): ?>
	    	<p>Precio: <strike><?= $producto->precio ?> €</strike> <?= round($producto->precio - ($producto->precio * ($producto->descuento / 100)), 2) ?> €</p>
		<?php else: ?>
	    	<p>Precio: <?= $producto->precio ?> €</p>
		<?php endif; ?>
		<input type="hidden" name="id" value="<?=$producto->id?>" />
                <input type="hidden" name="url" value="<?=current_url() ?>" />
		<input type="text" size="5" name="cantidad" value="" /><input type="submit" value="Comprar" />
                <?php if ($error['id'] == $producto->id): ?>
                <p><?=$error['mensaje'] ?></p>
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
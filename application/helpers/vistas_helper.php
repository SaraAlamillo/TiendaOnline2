<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('CargaVista')) {

    function CargaVista($fichero, array $variablesDeVista = NULL) {
	if (!file_exists(APPPATH . "views/" . $fichero . ".php")) {
	    return "No existe la vista $fichero"; // Nada que incluir
	}
	if (!is_null($variablesDeVista)) {
	    // Creamos variables que hemos pasado en el array
	    foreach ($variablesDeVista as $nombreVariableArrayEnForeach => $valorVariableArray) {   // OJO al doble $
		$$nombreVariableArrayEnForeach = $valorVariableArray;
	    }
	}
	// Interpretamos plantilla
	ob_start();
	include(APPPATH . "views/" . $fichero . ".php");
	$contenido = ob_get_clean();

	return $contenido;
    }

}
if (!function_exists('generarVistaProducto')) {

    function generarVistaProducto($producto) {
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
		<input type="text" size="5" name="cantidad" value="" /><input type="submit" value="Comprar" />
	    </form>
	</div>


	<?php
	$contenido = ob_get_clean();

	return $contenido;
    }

}

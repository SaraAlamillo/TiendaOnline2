<div id="productos">
    <?php foreach ($productos as $p):
	?>
	<?= generarVistaProducto($p, $error) ?>
    <?php endforeach; ?>
</div>
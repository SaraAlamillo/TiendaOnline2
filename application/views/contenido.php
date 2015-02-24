<div id="destacados">
    <?php foreach ($destacados as $d):
	?>
	<?= generarVistaProducto($d, $error) ?>
    <?php endforeach; ?>
</div>
<div id="productos">
    <?php foreach ($productos as $p):
	?>
	<?= generarVistaProducto($p, $error) ?>
    <?php endforeach; ?>
</div>
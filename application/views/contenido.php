<div id="destacados">
    <?php foreach ($destacados as $d):
	?>
<?= generarVistaProducto($d) ?>
    <?php endforeach; ?>
</div>
<div id="productos">
    <?php foreach ($productos as $p):
	?>
<?= generarVistaProducto($p) ?>
    <?php endforeach; ?>
</div>
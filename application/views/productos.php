<div id="productos">
    <div class="paginacion">
    <?= $paginador ?>
    </div>
    <?php foreach ($productos as $p): ?>
	<?= generarVistaProducto($p, $error) ?>
    <?php endforeach; ?>
    <?= $paginador ?>
</div>
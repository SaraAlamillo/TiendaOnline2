<div id="productos">
    <div class="paginacion">
    <?= $paginador ?>
    </div>
    <br />
    <?php foreach ($productos as $p): ?>
	<?= generarVistaProducto($p, $error) ?>
    <?php endforeach; ?>
    <br />
    <div class="paginacion">
    <?= $paginador ?>
    </div>
</div>
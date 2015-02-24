<div id="carrito">
    <table border="1">
	<tr>
	    <td>Producto</td>
	    <td>Precio</td>
	    <td>Cantidad</td>
	    <td>Total</td>
	</tr>
	<?php foreach ($productos as $p): ?>
	<tr>
	    <td><?=$p['nombre'] ?></td>
	    <td><?=$p['precio'] ?></td>
	    <td><?=$p['cantidad'] ?></td>
	    <td><?=$p['precio'] * $p['cantidad'] ?></td>
	</tr>
	<?php endforeach; ?>
    </table>
    <br />
    <?php if ($logueado): ?>
    <button><?= anchor("home/tramitar_compra", "Comprar productos") ?></button>
    <?php else: ?>
    <button disabled="disabled">Comprar productos</button> <br />
    <small>Debe iniciar sesión para continuar con la compra</small>
    <?php endif; ?>
</div>
<div id="carrito">
    <?php if (!is_null($mensaje)): ?>
    <p><?=$mensaje ?></p>
    <?php endif; ?>
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
	    <td><?=anchor("compra/eliminar_producto_carrito/${p['id']}", "Eliminar del carrito") ?></td>
	</tr>
	<?php endforeach; ?>
    </table>
    <br />
    <?php if ($logueado): ?>
    <button><?= anchor("compra/confirmar_productos", "Comprar productos") ?></button>
    <button><?= anchor("compra/vaciar_carrito", "Vaciar carrito") ?></button>
    
    <?php else: ?>
    <button disabled="disabled">Comprar productos</button> <br />
    <small>Debe iniciar sesión para continuar con la compra</small>
    <?php endif; ?>
</div>
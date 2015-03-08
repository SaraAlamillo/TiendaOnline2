<div id="carrito">
    <p>El pedido se realizará con los siguientes productos:</p>
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
    <button><?= anchor("compra/consultar_carrito", "Cancelar") ?></button>
    <button><?= anchor("compra/confirmar_usuario", "Siguiente") ?></button>
</div>


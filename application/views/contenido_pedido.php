<div id="pedidos">
    <p><?= anchor("pedido/listado", "Volver atrás") ?></p>
   <table border="1">
	<tr>
	    <td>Producto</td>
	    <td>Precio</td>
	    <td>Cantidad</td>
	    <td>Total</td>
	</tr>
	<?php foreach ($contenido as $c): ?>
	<tr>
	    <td><?=$c->nombre ?></td>
	    <td><?=$c->precio ?></td>
	    <td><?=$c->cantidad ?></td>
	    <td><?=$c->precio * $c->cantidad ?></td>
	</tr>
	<?php endforeach; ?>
    </table>
</div>


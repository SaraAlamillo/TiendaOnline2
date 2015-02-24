<div id="pedidos">
    <table border="1">
	<tr>
            <td>Referencia</td>
	    <td>Estado</td>
	    <td>Fecha de pedido</td>
	    <td>Fecha de entrega</td>
            <td>Total</td>
	</tr>
	<?php foreach ($pedidos as $p): ?>
	<tr>
	    <td><?=$p->id ?></td>
	    <td><?=$p->estado?></td>
	    <td><?=$p->fecha_pedido ?></td>
	    <td><?=$p->fecha_entrega ?></td>
            <td><?=$p->total ?></td>
            <td><?= anchor("home/consultar_pedido/$p->id", "Más detalles") ?></td>
	</tr>
	<?php endforeach; ?>
    </table>
</div>


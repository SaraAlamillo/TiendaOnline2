<div id="pedidos">
    <?php if (!is_null($mensaje)): ?>
    <small><?=$mensaje ?></small>
    <?php endif; ?>
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
                <td><?= $p->id ?></td>
                <td><?= $p->estado ?></td>
                <td><?= $p->fecha_pedido ?></td>
                <td><?= $p->fecha_entrega ?></td>
                <td><?= $p->total ?></td>
                <td><?= anchor("home/consultar_pedido/$p->id", "Más detalles") ?></td>        
                <?php if ($p->estado != 'Cancelado'): ?>     
                <td><?= anchor("home/generar_factura/$p->id", "Generar factura") ?></td>    
                <td><?= anchor("home/cancelar_pedido/$p->id/$p->estado", "Cancelar pedido") ?></td>
                <?php endif; ?>

            </tr>
        <?php endforeach; ?>
    </table>
</div>


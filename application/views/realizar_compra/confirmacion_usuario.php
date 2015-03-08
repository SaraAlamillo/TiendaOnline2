<div id="">
    <p>El paquete con los productos enviados se enviarán al siguiente destinatario:</p>
    <table border="1">
        <tr>
            <td>Nombre completo</td>
            <td><?=$usuario->nombre ?> <?=$usuario->apellidos ?></td>
        </tr>
        <tr>
            <td>Dirección</td>
            <td><?=$usuario->direccion ?></td>
        </tr>
        <tr>
            <td>Código Postal</td>
            <td><?=$usuario->cp ?></td>
        </tr>
        <tr>
            <td>Provincia</td>
            <td><?=$usuario->provincia ?></td>
        </tr>
    </table>
    <br />
    <button><?= anchor("compra/confirmar_productos", "Anterior") ?></button>
    <button><?= anchor("compra/consultar_carrito", "Cancelar") ?></button>
    <button><?= anchor("compra/realizar_pedido", "Siguiente") ?></button>
</div>


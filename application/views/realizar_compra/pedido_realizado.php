<div id="">
    <p>Se ha creado correctamente el pedido <?=$pedido ?>. ¿Desea recibir un correo electrónico con los detalles del pedido efectuado?</p>
    <br />
    <button><?= anchor("compra/mensaje_final", "No deseo recibir nada") ?></button>
    <button><?= anchor("compra/enviar_detalle/$pedido", "Deseo que me envíen los detalles") ?></button>
    <button><?= anchor("compra/enviar_pdf/$pedido", "Deseo que me envíen una factura") ?></button>
</div>


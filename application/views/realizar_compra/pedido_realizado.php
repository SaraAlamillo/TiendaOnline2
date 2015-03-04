<div id="">
    <p>Se ha creado correctamente el pedido <?=$pedido ?>. ¿Desea recibir un correo electrónico con los detalles del pedido efectuado?</p>
    <br />
    <button><?= anchor("compra/mensaje_final", "No deseo recibir nada") ?></button>
    <button><?= anchor("compra/email_detalle", "Deseo que me envíen los detalles") ?></button>
    <button><?= anchor("compra/email_pdf/$pedido", "Deseo que me envíen los detalles en PDF") ?></button>
</div>


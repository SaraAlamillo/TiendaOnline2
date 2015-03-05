<div>
    <form action="<?=  site_url("xml/exportar") ?>" method="POST">
        <input type text="text" value="<?= set_value("ruta") ?>" name="ruta"/>
        <input type="submit" value="Exportar" />
    </form>
    <?= form_error('ruta') . "<br />" ?>
</div>


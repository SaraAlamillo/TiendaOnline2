<div>
    <form action="<?= site_url("xml/subir_fichero") ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="userfile" value="fichero"/>
        <input type="submit" value="Importar" />
    </form>
    <?php if(isset($error)): ?>
    <?= $error ?>
    <?php endif; ?>
</div>


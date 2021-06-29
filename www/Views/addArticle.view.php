<h2>Ajout d'un article</h2>

<?php if (isset($errors)) : ?>

    <?php foreach ($errors as $error) : ?>
        <li style="color:red"><?= $error; ?></li>
    <?php endforeach; ?>

<?php endif; ?>



<h3>Formulaire</h3>
<?php if (isset($form)) : ?>
    <?php App\Core\FormBuilder::render($form); ?>
<?php endif; ?>

<script src="/../ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>
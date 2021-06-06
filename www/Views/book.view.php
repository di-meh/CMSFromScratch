<h2>Ajout de books</h2>

<?php if (isset($errors)) : ?>

    <?php foreach ($errors as $error) : ?>
        <li style="color:red"><?= $error; ?></li>
    <?php endforeach; ?>

<?php endif; ?>

<h3>Liste de books</h3>

<?php if (isset($books)) : ?>

    <?php var_dump($books); ?>

<?php endif; ?>

<h3>Formulaire</h3>
<?php if (isset($form)) : ?>
    <?php App\Core\FormBuilder::render($form); ?>
<?php endif; ?>
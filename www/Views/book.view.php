<h2>Ajout de books</h2>

<?php if (isset($errors)) : ?>

    <?php foreach ($errors as $error) : ?>
        <li style="color:red"><?= $error; ?></li>
    <?php endforeach; ?>

<?php endif; ?>

<h3>Liste de books</h3>

<?php if (isset($books)) : ?>
    <?php if (empty($books)) : ?>
        <p>Il n'y a pas de livres dans la bd.</p>
    <?php else : ?>
        <?php var_dump($books); ?>
    <?php endif; ?>

<?php endif; ?>

<h3>Formulaire</h3>
<?php if (isset($form)) : ?>
    <?php App\Core\FormBuilder::render($form); ?>
<?php endif; ?>
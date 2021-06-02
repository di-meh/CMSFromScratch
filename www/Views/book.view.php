<h2>Ajout de books</h2>

<?php if (isset($errors)) : ?>

    <?php foreach ($errors as $error) : ?>
        <li style="color:red"><?= $error; ?></li>
    <?php endforeach; ?>

<?php endif; ?>

<h3>Liste de books</h3>

<?php if (isset($books)) : ?>

    <?php foreach ($books as $book) : ?>
        <li style="color:blue"><?= $book; ?></li>
    <?php endforeach; ?>

<?php endif; ?>

<?php App\Core\FormBuilder::render($form) ?>
<h2>Bienvenue !</h2>
<?php if (isset($form)) : ?>
    <?php App\Core\FormBuilder::render($form); ?>
<?php endif; ?>
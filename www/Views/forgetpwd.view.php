
<?php if (isset($errors)) : ?>
    <?php foreach ($errors as $error) : ?>
<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="alert alert-danger">
                <button class="alert-close"><span>X</span></button>
                <h1 class="alert-heading">Erreur</h1>
                <p><?= $error; ?></p>
            </div>
        </div>
    </div>
</section>

    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($infos)) : ?>

    <?php foreach ($infos as $info) : ?>
        <section class="container-fluid">
            <div class="row">
                <div class="col-full">
                    <div class="alert alert-info">
                        <button class="alert-close"><span>X</span></button>
                        <h1 class="alert-heading">Info</h1>
                        <p><?= $info; ?></p>
                    </div>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

<?php endif; ?>

<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Mot de passe oublié</h6>
                <div class="card-content"><?php App\Core\FormBuilder::render($form) ?></div>
            </div>
        </div>
    </div>

</section>
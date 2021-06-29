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

<?php if (isset($books)) : ?>
    <section class="container-fluid">
        <div class="row">
            <div class="col-full">
                <div class="card">
                    <h6 class="card-title">Liste de livres</h6>
                    <div class="card-content">
                        <?php if (empty($books)) : ?>
                            <p>Il n'y a pas de livres dans la bd.</p>
                        <?php else : ?>
                            <?php var_dump($books); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php endif; ?>

    <section class="container-fluid">
        <div class="row">
            <div class="col-full">
                <div class="card">
                    <h6 class="card-title">Ajouter un livre</h6>
                    <div class="card-content">
                        <?php if (isset($form)) : ?>
                            <?php App\Core\FormBuilder::render($form); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

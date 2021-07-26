<?php if (isset($form)) : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-full">
                <div class="card">
                    <h2 class="card-title">Bienvenue !</h2>
                    <div class="card-content">
                        <?php App\Core\FormBuilder::render($form); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
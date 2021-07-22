<script src="/../node_modules/ckeditor4/ckeditor.js"></script>
<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Ajouter une catégorie</h6>
                <div class="card-content">
                    <?php if (isset($form)) : ?>
                        <?php App\Core\FormBuilder::render($form); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
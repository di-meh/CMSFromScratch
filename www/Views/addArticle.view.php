<script src="/../node_modules/ckeditor4/ckeditor.js"></script>
<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <div class="card-title">
                    <a href="../lbly-admin/articles"><button class='btn btn-light'><span class='material-icons'>undo</span></button></a>
                    <h6>Ajouter un article</h6>
                </div>
                <div class="card-content">
                    <?php if (isset($form)) : ?>
                        <?php App\Core\FormBuilder::render($form); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
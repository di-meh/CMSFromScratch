<script src="/../node_modules/ckeditor4/ckeditor.js"></script>
<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <div class="card-title">
                    <button class='btn btn-light' onclick='history.go(-1)'><span class='material-icons'>undo</span></button>
                    <h6>Ajouter une page</h6>
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
<script>
    CKEDITOR.replace('editor');
</script>

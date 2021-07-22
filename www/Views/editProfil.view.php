
<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Modification du profil</h6>

                <div class="card-content">
                    <?php App\Core\FormBuilder::render($form) ?>
                    <a href="/deleteuser"><button class="btn btn-danger">Supprimer votre compte</button></a>

                </div>
            </div>
        </div>
    </div>

</section>

<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <div class="card-title">
                    <button class='btn btn-light' onclick='history.go(-1)'><span class='material-icons'>undo</span></button>
                    <h6>Modification du profil</h6>
                </div>
                <div class="card-content">
                    <?php App\Core\FormBuilder::render($form) ?>
                    <a href="/deleteuser"><button class="btn btn-danger">Supprimer votre compte</button></a>

                </div>
            </div>
        </div>
    </div>

</section>
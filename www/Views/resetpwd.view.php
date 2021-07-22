<section class="d-flex flex-direction-column flex-align-items-center flex-justify-content-center s-h-full">
	<div class="card s-w-350">
		<div class="card-title">
                    <button class='btn btn-light' onclick='history.go(-1)'><span class='material-icons'>undo</span></button>
                    <h6>Réinitialisation du mot de passe</h6>
		</div>
		<div class="card-content"><?php App\Core\FormBuilder::render($form) ?>
        </div>
	</div>
</section>

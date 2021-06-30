<section class="d-flex flex-direction-column flex-align-items-center flex-justify-content-center s-w-full">
	<div class="card s-w-350 sp-my-3">
		<h6 class="card-title">S'inscrire</h6>
		<div class="card-content"><?php App\Core\FormBuilder::render($form) ?></div>
	</div>
	<!-- <section>
		<br />
		<a href="/">Accueil</a>
		<?php
		session_start();
		if (!isset($_SESSION['id']))
			echo "<a id='' href='login'>Connexion</a>";
		else
			echo "<a id='' href='logout'>Déconnexion</a>";

		?>

	</section> -->
</section>
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
                        <h1 class="alert-heading">Info</h1>
                        <p><?= $info; ?></p>
                    </div>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

<?php endif; ?>

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
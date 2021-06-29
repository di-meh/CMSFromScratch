<?php if (isset($errors)) : ?>
	<?php foreach ($errors as $error) : ?>
		<div class="alert alert-danger">
			<h1 class="alert-heading">Erreur</h1>
			<p><?= $error; ?></p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (isset($infos)) : ?>

	<?php foreach ($infos as $info) : ?>
		<div class="alert alert-info">
			<h1 class="alert-heading">Info</h1>
			<p><?= $info; ?></p>
		</div>
	<?php endforeach; ?>

<?php endif; ?>



<section class="d-flex flex-direction-column flex-align-items-center flex-justify-content-center s-w-full s-h-h-screen">
	<div class="card s-w-350">
		<h6 class="card-title">Modification du profil</h6>
		<div class="card-content"><?php App\Core\FormBuilder::render($form) ?></div>
	</div>
</section>
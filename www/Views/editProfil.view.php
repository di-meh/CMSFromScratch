<h2>Edition du profil</h2>

<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>


<?php App\Core\FormBuilder::render($form)?>

<section>
	<br/>
	<a id="" href="/">Accueil</a>
	<br/>
	<a id="" href="logout">Déconnexion</a>

	
</section>
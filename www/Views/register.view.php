<h2>S'inscrire</h2>

<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>



<?php App\Core\FormBuilder::render($form)?>

<section>
	<br/>
	<a href="/">Accueil</a>
	<?php
		session_start();
		if(!isset($_SESSION['id']))
			echo "<a id='' href='login'>Connexion</a>";
		else
			echo "<a id='' href='logout'>Déconnexion</a>";

	?>
	
</section>
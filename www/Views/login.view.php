<h2>Se connecter</h2>

<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>



<?php App\Core\FormBuilder::render($form)?>


<h2>Ajouter une Page</h2>

<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>

<?php App\Core\FormBuilder::render($form)?>

<script src="/../ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
<h2>Modifier cette page</h2>

<?php if(isset($errors)):?>

    <?php foreach ($errors as $error):?>
        <li style="color:red"><?=$error;?></li>
    <?php endforeach;?>

<?php endif;?>
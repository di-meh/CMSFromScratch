<h2>Ajout d'un article</h2>

<?php if (isset($errors)) : ?>

    <?php foreach ($errors as $error) : ?>
        <li style="color:red"><?= $error; ?></li>
    <?php endforeach; ?>

<?php endif; ?>



<h3>Formulaire</h3>
<?php if (isset($form)) : ?>
    <?php App\Core\FormBuilder::render($form); ?>
<?php endif; ?>

<section>
	<br/>
	<a id="" href="/">Accueil</a>
	<br/>
	<a id="" href="/articles">Liste des articles</a>
	<br/>
	<a id="" href="/logout">Déconnexion</a>

	
</section>
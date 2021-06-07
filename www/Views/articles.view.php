<section>
	<h2>Liste des articles</h2>

	<?php if (isset($articles)) : ?>
		<?php if (empty($articles)) : ?>
			<p>Il n'y a pas d'article dans la bdd.</p>
		<?php else : ?>
			<?php foreach ($articles as $article) {
				foreach ($article as $key => $value) {
					echo $key.' : '.$value.'<br/>';
				}
				echo '<hr/>';
			}
		endif; ?>

	<?php endif; ?>
</section>

<section>
	<br/>
	<a id="" href="/">Accueil</a>
	<br/>
	<a id="" href="articles/add">Ajouter un article</a>
	<br/>
	<a id="" href="logout">Déconnexion</a>

	
</section>
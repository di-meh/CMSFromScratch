<!--
<section class="d-flex flex-direction-column flex-align-items-center flex-justify-content-center s-w-full s-h-full">
	<?php

	if (isset($_SESSION['id'])) {

			echo "<a id'' href='editprofil'>Edition du profil</a><br/>";		
			echo "<a id='' href='page/all'>Voir mes pages</a> <br>";
			echo "<a id='' href='page/add'>Ajouter une page</a>";
			echo "<a id'' href='articles'>Articles</a><br/>";
			echo "<a id='' href='logout'>Déconnexion</a>";

		}else
			echo "<a id='' href='login'>Connexion</a>";
	?>
	<br /><br />
	<a id="" href="register">Inscription</a>
	<a id="" href="category">Catégories</a>

</section> -->


<div class="d-flex flex-direction-column flex-align-items-center flex-justify-content-center s-w-full s-h-full">
	<h1 class="sp-mb-2">Bienvenue chez <?= SITENAME ?></h1>
	<p>Retrouvez vos livres préférés</p>
</div>

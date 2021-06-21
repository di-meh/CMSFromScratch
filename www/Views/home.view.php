<!-- <section>
	<h2>Welcome <?= $pseudo;?></h2>
	<small>Vous avez <?= $age;?> ans et votre email c'est <?= $email ?></small>
</section> -->



<section>
	<?php

		if(isset($_SESSION['id'])){

			echo "<a id'' href='editprofil'>Edition du profil</a><br/>";
			echo "<a id='' href='logout'>Déconnexion</a><br/>";
		}else
			echo "<a id='' href='login'>Connexion</a>";
	?>
	<br/><br/>
	<a id="" href="register">Inscription</a>

	<a id="" href="category">Catégories</a>

</section>


<!-- <?php for ($i=0; $i < 10; $i++):?>
	<b>test</b>
<?php endfor;?> -->
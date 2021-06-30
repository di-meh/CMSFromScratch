



<section>
	<br/>
	<a href="/">Accueil</a>
	<?php
		if(!isset($_SESSION['id']))
			echo "<a id='' href='login'>Connexion</a>";
		else
			echo "<a id='' href='logout'>Déconnexion</a>";

	?>
	
</section>
<section>
	<h2>Liste des articles</h2>

	<?php if (isset($articles)) :?>
        <?php if (empty($articles)) :?>
            <p>Vous n'avez pas créé de article.</p>
        <?php else: ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date de création</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($articles as $article) { ?>
                            <tr>
                            <td><?=$article["title"];?></td>
                            <td><?=$article["created"];?></td>
                            <td><a href="<?=$article["slug"]?>">Voir</a></td>
                            <td><a href="#">Modifier</a></td>
                            </tr>
                            <?php
                            }
                        endif; ?>

                </tbody>
                </table>
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
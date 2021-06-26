<section>
    <h2>Vos Pages</h2>
    <?php if (isset($pages)) :?>
        <?php if (empty($pages)) :?>
            <p>Vous n'avez pas crée de page.</p>
        <?php else: ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>titre</th>
                        <th>date de création</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($pages as $page) { ?>
                            <tr>
                            <td><?php echo $page["title"];?></td>
                            <td><?php echo $page["createdAt"];?></td>
                            <td><a href="#">Voir ma page</a></td>
                            <td><a href="#">Modifier ma page</a></td>
                            <!--<td><//?php echo $page["slug"];?></td>-->
                            </tr>
                            <?php echo '<hr/>';
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
    <a id="" href="add">Ajouter une page</a>
    <br/>
    <a id="" href="logout">Déconnexion</a>
</section>
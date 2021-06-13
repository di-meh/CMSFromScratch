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
                        <th>créateur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $page) {
                            foreach ($page as $key => $value){
                                echo $key.' : '.$value.'<br/>';
                            }
                            echo '<hr/>';
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
    <a id="" href="page/all">Ajouter une page</a>
    <br/>
    <a id="" href="logout">Déconnexion</a>
</section>
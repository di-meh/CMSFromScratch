<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Vos articles</h6>
                <div class="card-content">

                    <?php if (isset($articles)) :?>
                        <?php if (empty($articles)) :?>
                            <p>Vous n'avez pas créé d'article.</p>
                        <?php else: ?>
                            <table class="table">
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
                                    <td><a href="articles/edit?article=<?=$article["id"]?>">Modifier</a></td>
                                </tr>
                                <?php
                            }
                        endif; ?>

                        </tbody>
                        </table>
                    <?php endif; ?>
                    <a id="" href="articles/add"><button class="btn btn-primary">Ajouter un article</button></a>
                </div>
            </div>
        </div>
    </div>
</section>
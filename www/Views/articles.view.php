<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <?php if (isset($deletemodal)) :?>
                <div class="card">
                    <h6 class="card-title">Delete Modal</h6>
                    <div class="card-content">
                        <p>Voulez vous supprimer cette page : <?=$article["title"]?> ?<br/><br/>
                        <a id="" href=""><button class="btn btn-primary">Supprimer</button></a>
                        <a id="" href="/lbly-admin/articles"><button class="btn btn-danger">Annuler</button></a>
                    </div>
                </div>
                <br/>
            <?php endif; ?>
            <div class="card">
                <h6 class="card-title">Vos articles</h6>
                <div class="card-content">
                    <a id="" href="articles/add"><button class="btn btn-primary">Ajouter un article</button></a>

                    <?php if (isset($articles)) :?>
                        <?php if (empty($articles)) :?>
                            <p>Vous n'avez pas créé d'article.</p>
                        <?php else: ?>
                            <table id="table" class="table">
                            <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date de création</th>
                                <th>Status</th>
                                <th>Status</th>
                                <th>Status</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($articles as $article) { ?>
                                <tr>
                                    <td><?=$article["title"];?></td>
                                    <td><?=$article["created"];?></td>
                                    <td><?=$article["status"];?></td>
                                    <td><a href="/<?=$article["slug"]?>"><button class="btn btn-primary">Voir</button></a></td>
                                    <td><a href="/lbly-admin/articles/edit/<?=$article["slug"]?>"><button class="btn btn-primary">Modifier</button></a></td>
                                    <td><a href="/lbly-admin/articles/delete/<?=$article["slug"]?>"><button class="btn btn-danger">Supprimer</button></a></td>
                                </tr>
                                <?php
                            }
                        endif; ?>

                        </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
use App\Core\Security;
?>

<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <?php if (isset($deletemodal)) :?>
                <div class="card">
                    <h6 class="card-title">Delete Modal</h6>
                    <div class="card-content">
                        <?php if (isset($formdelete)) : ?>
                            <?php App\Core\FormBuilder::render($formdelete); ?>
                        <?php endif; ?>
                        <br/>
                        <a id="" href="/lbly-admin/articles"><button class="btn btn-primary">Annuler</button></a>
                    </div>
                </div>
                <br/>
            <?php endif; ?>
            <div class="card">
            <?php if (isset($articles)) :?>

                <h6 class="card-title">Vos articles</h6>
                <div class="card-content">
                    <a id="" href="articles/add"><button class="btn btn-primary">Ajouter un article</button></a>
                    <br/><br/>
                        <?php if (empty($articles)) :?>
                            <p>Vous n'avez pas créé d'article.</p>
                        <?php else: ?>

                            <table class="table">
                            <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date de création</th>
                                <th>Auteur</th>                                  

                                <th>Status</th>
                                <th>Voir</th>
                                <th>Modifier</th>
                                <?php if(!Security::isOnlyContributor()): ?>

                                    <th>Publier</th>
                                <?php endif; ?>
                                <th>Supprimer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($articles as $article) { ?>
                                <?php if(Security::hasAuthorization() || (Security::isOnlyAuthor() && $article['author'] == $_SESSION['id']) || (Security::isOnlyContributor() && $article['author'] == $_SESSION['id'])) :?>
                                    <tr>
                                        <td><?=$article["title"];?></td>
                                        <td><?=$article["createdAt"];?></td>
                                        <td><?= Security::getName($article["author"]);?></td>

                                        <td><?= $article["status"];?></td>
                                        <td><a href="/articles/<?=$article["slug"]?>"><button class="btn btn-primary">Voir</button></a></td>

                                        <td><a href="/lbly-admin/articles/edit/<?=$article["slug"]?>"><button class="btn btn-primary">Modifier</button></a></td>

                                        <?php if($article["status"] == 'publish'):?>

                                            <td><a href="/lbly-admin/articles?withdraw=true&articleid=<?=$article["id"]?>"><button class="btn btn-primary">Retirer</button></a></td>

                                        <?php else :?>
                                            <?php if(!Security::isOnlyContributor()): ?>
                                                <td><a href="/lbly-admin/articles?publish=true&articleid=<?=$article["id"]?>"><button class="btn btn-primary">Publier</button></a></td>
                                            <?php endif;?>
                                        <?php endif; ?>

                                        <td><a href="/lbly-admin/articles/delete/<?=$article["slug"]?>"><button class="btn btn-danger">Supprimer</button></a></td>

                                    </tr>
                                <?php endif; ?>
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
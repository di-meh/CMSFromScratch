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
                        <!-- <a id="" href=""><button class="btn btn-primary">Supprimer</button></a> -->
                        <br/>
                        <a id="" href="/lbly-admin/pages"><button class="btn btn-danger">Annuler</button></a>
                    </div>
                </div>
                <br/>
            <?php endif; ?>
            <div class="card">
                <h6 class="card-title">Vos pages</h6>
                <div class="card-content">
                    <a id="" href="/lbly-admin/pages/add"><button class="btn btn-primary">Ajouter une page</button></a>
                    <br/><br/>
                    <?php if (isset($pages)) :?>
                        <?php if (empty($pages)) :?>
                            <p>Vous n'avez pas crée de page.</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date de création</th>
                                <th>Status</th>
                                <th>Voir</th>
                                <th>Modifier</th>

                                <th>Publier</th>

                                <th>Supprimer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pages as $page) { ?>
                                <tr>
                                    <td><?php echo $page["title"];?></td>
                                    <td><?php echo $page["createdAt"];?></td>
                                    <td><?php echo $page["status"];?></td>
                                    <td><a href="/pages/<?=$page["slug"]?>"><button class="btn btn-primary">Voir</button></a></td>
                                    <td><a href="/lbly-admin/edit/<?=$page["slug"]?>"><button class="btn btn-primary">Modifier</button></a></td>
                                    <?php if($page["status"] == 'publish'):?>

                                            <td><a href="/lbly-admin/pages?withdraw=true&pageid=<?=$page["id"]?>"><button class="btn btn-primary">Retirer</button></a></td>

                                    <?php else :?>

                                            <td><a href="/lbly-admin/pages?publish=true&pageid=<?=$page["id"]?>"><button class="btn btn-primary">Publier</button></a></td>

                                    <?php endif; ?>

                                    <td><a href="/lbly-admin/delete/<?=$page["slug"]?>"><button class="btn btn-danger">Supprimer</button></a></td>
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

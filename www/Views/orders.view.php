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
                        <a id="" href="/lbly-admin/articles"><button class="btn btn-danger">Annuler</button></a>
                    </div>
                </div>
                <br/>
            <?php endif; ?>
            <div class="card">
                <h6 class="card-title">Vos Commandes</h6>
                <div class="card-content">
                    <?php if (isset($orders)) :?>
                        <?php if (empty($orders)) :?>
                            <p>Vous n'avez pas créé d'article.</p>
                        <?php else: ?>
                            <table class="table">
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
                            <?php foreach ($orders as $order) { ?>
                                <tr>
                                    <td><?=$order["title"];?></td>
                                    <td><?=$order["created"];?></td>
                                    <td><?=$order["status"];?></td>
                                    <td><a href="/articles/<?=$order["slug"]?>"><button class="btn btn-primary">Voir</button></a></td>
                                    <td><a href="/lbly-admin/articles/edit/<?=$order["slug"]?>"><button class="btn btn-primary">Modifier</button></a></td>
                                    <td><a href="/lbly-admin/articles/delete/<?=$order["slug"]?>"><button class="btn btn-danger">Supprimer</button></a></td>
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
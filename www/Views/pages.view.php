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
                                <th>titre</th>
                                <th>date de création</th>
                                <th>Options</th>
                                <th>Options</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pages as $page) { ?>
                                <tr>
                                    <td><?php echo $page["title"];?></td>
                                    <td><?php echo $page["createdAt"];?></td>
                                    <td><a href="/<?=$page["slug"]?>"><button class="btn btn-primary">Voir</button></a></td>
                                    <td><a href="/lbly-admin/edit/<?=$page["slug"]?>"><button class="btn btn-primary">Modifier</button></a></td>
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

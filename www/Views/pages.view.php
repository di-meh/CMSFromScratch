<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Vos pages</h6>
                <div class="card-content">
                    <?php if (isset($pages)) :?>
                        <?php if (empty($pages)) :?>
                            <p>Vous n'avez pas crée de page.</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>titre</th>
                                <th>date de création</th>
                                <th colspan="3">mes actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pages as $page) { ?>
                                <tr>
                                    <td><?php echo $page["title"];?></td>
                                    <td><?php echo $page["createdAt"];?></td>
                                    <td><a href="<?=$page['slug']?>">Voir ma page</a></td>
                                    <td><a href="<?=$page['editSlug']?>">Modifier / Supprimer ma page</a></td>
                                    <!--<td><//?php echo $page["slug"];?></td>-->
                                </tr>
                                <?php
                            }
                        endif; ?>

                        </tbody>
                        </table>
                    <?php endif; ?>
                    <a id="" href="/lbly-admin/pages/add"><button class="btn btn-primary">Ajouter une page</button></a>
                </div>
            </div>
        </div>
    </div>

</section>

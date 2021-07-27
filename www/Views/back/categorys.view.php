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
                        <a id="" href="/lbly-admin/category"><button class="btn btn-primary">Annuler</button></a>
                    </div>
                </div>
                <br/>
            <?php endif; ?>
            <div class="card">
                <h6 class="card-title">Vos catégories</h6>
                <div class="card-content">
                    <a id="" href="/lbly-admin/category/add"><button class="btn btn-primary">Ajouter une catégorie</button></a>
                    <br/><br/>
                    <?php if (isset($categorys)) :?>
                        <?php if (empty($categorys)) :?>
                            <p>Vous n'avez pas créé de catégorie</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Couleur</th>
                                <th>Options</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($categorys as $category) { ?>
                                <tr>
                                    <td><?=$category["nameCategory"];?></td>
                                    <td>   
                                        <?=$category["colorCategory"];?>
                                        <span class="material-icons" style="color:<?=$category["colorCategory"];?>;">palette</span>
                                    </td>
                                    <td><a href="/lbly-admin/category/edit/<?=$category["nameCategory"]?>"><button class="btn btn-primary">Modifier</button></a></td>
                                    <td><a href="/lbly-admin/category/delete/<?=$category["nameCategory"]?>"><button class="btn btn-danger">Supprimer</button></a></td>                
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
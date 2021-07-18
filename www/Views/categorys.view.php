<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Ajouter une catégorie</h6>
                <div class="card-content">
                    <?php if (isset($form)) : ?>
                        <?php App\Core\FormBuilder::render($form); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Liste des catégories</h6>
                <div class="card-content">
                    <?php if (isset($categorys)) :?>
                        <?php if (empty($categorys)) :?>
                            <p>Vous n'avez pas créé de catégorie</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Couleur</th>
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
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
                <h6 class="card-title">Vos livres</h6>
                <div class="card-content">
                    <a id="" href="/lbly-admin/books/add"><button class="btn btn-primary">Ajouter un livre</button></a>
                    <br/><br/>
                    <?php if (isset($books)) :?>
                        <?php if (empty($books)) :?>
                            <p>Vous n'avez pas crée de livre.</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>titre</th>
                                <th>Auteur</th>
                                <th>Stock</th>
                                <th><span class="material-icons-outlined">delete</span></th>
                                <th>Stock</th>
                                <th>Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($books as $book) { ?>
                                <tr>
                                    <td><img src="../<?=$book["image"]?>" height="28px"></td> 
                                    <td><?php echo $book["title"];?></td>
                                    <td><?php echo $book["author"];?></td>
                                    <td><?php echo $book["stock_number"];?></td>
                                    <td><a href="/books/<?=$book["slug"]?>"><button class="btn btn-primary">Voir</button></a></td>
                                    <td><a href="/lbly-admin/books/edit/<?=$book["slug"]?>"><button class="btn btn-primary">Modifier</button></a></td>
                                    <td><a href="/lbly-admin/books/delete/<?=$book["slug"]?>"><button class="btn btn-danger">Supprimer</button></a></td>
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



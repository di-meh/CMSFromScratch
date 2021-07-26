<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Tous les livres</h6>
                <div class="card-content">
                        <?php if (empty($books)) :?>
                            <p>Il n'y a aucun livre</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>titre</th>
                                <th>Auteur</th>
                                <th>Nombre</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($books as $book) { ?>
                                    <tr>
                                        <td><?php echo $book["title"];?></td>
                                        <td><?php echo $book["author"];?></td>
                                        <td><?php echo $book["stock_number"];?></td>
                                        <td><?php App\Core\FormBuilder::render($forms[$book["id"]]); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</section>



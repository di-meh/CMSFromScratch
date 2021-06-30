<?php if (isset($books)) : ?>
    <section class="container-fluid">
        <div class="row">
            <div class="col-full">
                <div class="card">
                    <h6 class="card-title">Liste de livres</h6>
                    <div class="card-content">
                        <?php if (empty($books)) : ?>
                            <p>Il n'y a pas de livres dans la bd.</p>
                        <?php else : ?>
                        <table class="table">
                            <thead>
                            <tr>
                                <td>Titre</td>
                                <td>Auteur</td>
                                <td>Nombre de livres en stock</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($books as $book) :?>
                                <tr>
                                    <td><?= $book['title'];?></td>
                                    <td><?= $book['author'];?></td>
                                    <td><?= $book['stock_number'];?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php endif; ?>

    <section class="container-fluid">
        <div class="row">
            <div class="col-full">
                <div class="card">
                    <h6 class="card-title">Ajouter un livre</h6>
                    <div class="card-content">
                        <?php if (isset($form)) : ?>
                            <?php App\Core\FormBuilder::render($form); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

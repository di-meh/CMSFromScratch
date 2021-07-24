<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Votre panier</h6>
                <div class="card-content">
                    <?php if (empty($books)) :?>
                            <p>Vous n'avez rien dans votre panier</p>
                        <?php else: ?>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>titre</th>
                                    <th>Auteur</th>
                                    <th>Quantité</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($books as $book) { ?>
                                    <tr>
                                        <td><?php echo $book["title"];?></td>
                                        <td><?php echo $book["author"];?></td>
                                        <td><?php echo $book["qty"];?></td>
                                        <td><?php App\Core\FormBuilder::render($forms[$book["id"]]); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            
                    <?php  App\Core\FormBuilder::render($forms["reset_cart"]);
                
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
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
                                    <th>Prix unitaire</th>
                                    <th>Prix total</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $totalprice = 0;
                                $totalqty = 0;
                                foreach ($books as $book) { ?>
                                    <tr>
                                        <td><?php echo $book["title"];?></td>
                                        <td><?php echo $book["author"];?></td>
                                        <td><?php echo $book["qty"];?></td>
                                        <td><?php echo $book["price"];?> €</td>
                                        <td><?php echo $book["qty"]*$book["price"];?> €</td>
                                        <td><?php App\Core\FormBuilder::render($forms[$book["id"]]); ?></td>
                                    </tr>
                                    <?php
                                    $totalprice += $book["qty"]*$book["price"];
                                    $totalqty += $book["qty"];
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th><?php echo $totalqty;?></th>
                                        <th></th>
                                        <th><?php echo $totalprice;?> €</th>
                                        <th><?php  App\Core\FormBuilder::render($forms["reset_cart"]); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        <a href="/cart/confirm" style="display: table; margin: 1.25rem 0 0 auto;"><button class="btn btn-primary">Confirmer le panier</button></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
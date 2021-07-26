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
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Quantité</th>
                                <th>Montant</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($orders as $order) { ?>
                                <tr>
                                    <td><?=$order["name"];?></td>
                                    <td><?=$order["email"];?></td>
                                    <td><?=$order["create_at"];?></td>
                                    <td><?=$order["item_number"];?></td>
                                    <td><?=$order["amount"];?> €</td>
                                    <td><?=$order["payment_status"];?></td>
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
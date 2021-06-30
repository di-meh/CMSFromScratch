<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Vos users</h6>
                <div class="card-content">
                    <?php if (isset($users)) :?>
                        <?php if (empty($users)) :?>
                            <p>Il n'y a aucun utilisateur en base.</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Mail</th>
                                <th>Pays</th>
                                <th>Statut</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <td><?php echo $user["lastname"];?></td>
                                    <td><?php echo $user["firstname"];?></td>
                                    <td><?php echo $user["email"];?></td>
                                    <td><?php echo $user["country"];?></td>
                                    <td><?php echo App\Core\Security::readStatus($user["status"]);?></td>
                                </tr>
                                <?php
                            }
                        endif; ?>

                        </tbody>
                        </table>
                    <?php endif; ?>
                    <a id="" href="/lbly-admin/users/add"><button class="btn btn-primary">Ajouter une user</button></a>
                </div>
            </div>
        </div>
    </div>

</section>




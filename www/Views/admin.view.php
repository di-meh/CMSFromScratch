<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <?php if (isset($deleteUser)) :?>
                <div class="card">
                    <h6 class="card-title">Confirmation de suppression</h6>
                    <div class="card-content">
                        <?php if (isset($formDelete)) : ?>
                            <?php App\Core\FormBuilder::render($formDelete); ?>
                        <?php endif; ?>
                        <br/>
                        <a id="" href="/lbly-admin/adminview"><button class="btn btn-danger">Annuler</button></a>
                    </div>
                </div>
                <br/>
            <?php endif; ?>
            <?php if (isset($changeRole) && $changeRole == true) :?>
                <div class="card">
                    <h6 class="card-title">Changer les droits</h6>
                    <div class="card-content">
                        <?php if (isset($formRoles)) : ?>
                            <?php App\Core\FormBuilder::render($formRoles); ?>
                        <?php endif; ?>
                        <br/>
                        <a id="" href="/lbly-admin/adminview"><button class="btn btn-danger">Annuler</button></a>
                    </div>
                </div>
                <br/>
            <?php endif; ?>
            <div class="card">
                <h6 class="card-title">Les Utilisateurs</h6>
                <div class="card-content">
                    <a id="" href="/lbly-admin/register"><button class="btn btn-primary">Ajouter un Utilisateur</button></a>
                    </br></br>
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
                                <th>Roles</th>
                                <th>Date de création</th>                                
                                <th>Modifier</th>
                                <th>Supprimer</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <td><?= $user["lastname"];?></td>
                                    <td><?= $user["firstname"];?></td>
                                    <td><?= $user["email"];?></td>
                                    <td><?= $user["country"];?></td>
                                    <td><?= App\Core\Security::readStatus($user["status"]);?></td>
                                    <td><?= $user["createdAt"];?></td>

                                    <td><a href="/lbly-admin/changerole?userid=<?=$user["id"]?>"><button class="btn btn-primary">Modifier droits</button></a></td> 
                                    <td><a href="/deleteuser?userid=<?=$user["id"]?>"><button class="btn btn-danger">Supprimer</button></a></td> 
                                        

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




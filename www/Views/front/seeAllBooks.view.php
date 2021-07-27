<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Tous les livres</h6>
                <div class="card-content">
                    <div class="row">
                        <?php if (empty($books)) :?>
                            <p>Il n'y a aucun livre</p>
                        <?php else: ?>
                                <?php foreach ($books as $book) { ?>
                                    <div class="card col-3 p-position-relative" style="min-height:35rem">
                                        <h5 class="card-title"><?php echo $book["title"];?></h5>
                                        <div class="card-content">
                                            <?php if (isset($book["image"])) :?>
                                                <img class="img-fluid" src="<?="/". $book["image"];?>" >
                                            <?php else:?>
                                                <img class="img-fluid" src="/img/defaultImage.jpg" height="18rem">
                                            <?php endif;?>
                                            <h5>Auteur : <?=$book["author"];?></h5>
                                            <h5>Maison d'édition : <?=$book["publisher"];?></h5>
                                            <h5>Prix : <?=$book["price"];?> € </h5>
                                        </div>
                                        <div class="card-footer d-flex flex-justify-content-space-between p-position-absolute s-w-full p-b-0">
                                            <a href="/books/<?=$book["slug"]?>"><button class="btn btn-primary">Voir</button></a>
                                            <?php App\Core\FormBuilder::render($forms[$book["id"]]); ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>



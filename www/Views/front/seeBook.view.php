<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <div class="card-title">
                    <h2>Titre : <?=$book["title"];?></h2>
                </div>
                <div class="card-content">
                    <?php if (isset($book["image"])) :?>
                        <img src="<?="/". $book["image"];?>" height=400 width=300>
                    <?php else:?>
                        <img src="/img/defaultImage.jpg" height=400 width=300>
                    <?php endif;?>
                    <h3>Prix : <?=$book["price"];?> € </h3>
                    <?php if (preg_match("/[a-z]/i",$book["category"])) :?>
                        <h4>Catégorie : <?=$book["category"];?></h4>
                    <?php else:?>
                        <p>La catégorie de ce livre n'a pas encore été attribué</p>
                    <?php endif;?>
                    <p>Date de publication : <?=$book["publication_date"];?></p>
                    <p>Description : <?=$book["description"];?></p>
                    <h3>Auteur : <?=$book["author"];?></h3>
                    <h3>Maison d'édition : <?=$book["publisher"];?></h3>
                    <h3>En stock : <?= ($book["stock_number"] != 0) ? "OUI": "NON";?></h3>
                    <?php App\Core\FormBuilder::render($form); ?>
                </div>
             </div>
        </div>
    </div>
</section>
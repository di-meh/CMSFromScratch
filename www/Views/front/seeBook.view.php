<section>
    <h2>Titre : <?php echo $book["title"];?></h2>
    <?php if (isset($book["image"])) :?>
    <img src="<?php echo "/". $book["image"];?>" height=400 width=300>
    <?php else:?>
    <img src="/img/defaultImage.jpg" height=400 width=300>
    <?php endif;?>
    <h3>Prix : <?php echo $book["price"];?> € </h3>
    <h4>Catégorie : <?php echo $book["category"];?></h4>
    <p>Date de publication : <?php echo $book["publication_date"];?></p>
    <p>Description : <?php echo $book["description"];?></p>
    <h3>Auteur : <?php echo $book["author"];?></h3>
    <h3>Maison d'édition : <?php echo $book["publisher"];?></h3>
    <h3>En stock : <?php  echo $string = ($book["stock_number"] != 0) ? "OUI": "NON";?></h3>
    <!--<img src="/img/uno-uno-uno.png">-->


</section>
<?php

//1er fichier test1.php

$var = "test";


--------------------------------------------------

//2nd fichier test2.php



echo $var; //Afficher test



--------------------------------------------------

//3ème fichier

include "test1.php";

echo $var; //Afficher test
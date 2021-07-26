<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;

class Book extends Singleton
{
    private $id = null;
    protected $title;
    protected $description;
    protected $author;
    protected $publication_date;
    protected $image;
    protected $publisher;
    protected $price = 0;
    protected $category;
    protected $stock_number = 0;
    protected $slug;

    private $table = "lbly_books";
    public function __construct()
    {
    }
    # set all properties from database from the email
    public function setAll($id)
    {
        $this->id = $id;
        $query = "SELECT * FROM " . $this->table . " WHERE id = '" . $id . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        $this->setId($id);
        $this->setTitle($res["title"]);
        $this->setDescription($res["description"]);
        $this->setAuthor($res["author"]);
        $this->setPublicationDate($res["publication_date"]);
        $this->setImage($res["image"]);
        $this->setPublisher($res["publisher"]);
        $this->setPrice($res["price"]);
        $this->setCategory($res["category"]);
        $this->setStockNumber($res["stock_number"]);
    }

    public function stockUp($number = 1)
    {
        $this->setStockNumber($this->getStockNumber() + $number);
    }
    public function stockDown($number = 1)
    {
        $this->setStockNumber($this->getStockNumber() - $number);
    }

    public function book2slug($book){
        $book = preg_replace('~[^\pL\d]+~u', '-', $book);

        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        $book = strtr( $book, $unwanted_array );

        //retire symboles spéciaux
        $book = iconv("UTF-8", "ASCII//TRANSLIT", $book);

        $book = preg_replace('~[^-\w]+~', '', $book);

        $book = trim($book, '-');
        //suprimme double -
        $book = preg_replace('~-+~', '-', $book);
        //minuscule
        $book = strtolower($book);

        return $book;
    }

    // Forms

    public function formAddBook()
    {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_editprofil",
                "class" => "form_builder",
                "submit" => "Valider",
                "enctype" => "multipart/form-data"
            ],
            "inputs" => [
                "title" => [
                    "type" => "text",
                    "label" => "Titre du livre",
                    "minLength" => 1,
                    "maxLength" => 255,
                    "id" => "title",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Harry Potter et La Coupe de Feu",
                    "value" => "",
                    "error" => "Le titre doit faire entre 1 et 100 caractères ",
                    "required" => true
                ],
                "description" => [
                    "type" => "text",
                    "label" => "Description du livre",
                    "minLength" => 1,
                    "maxLength" => 65535,
                    "id" => "description",
                    "class" => "form_input",
                    "placeholder" => "Un super livre",
                    "value" => "",
                    "error" => "Votre description doit faire entre 1 et 255 caractères",
                    "required" => true
                ],
                "author" => [
                    "type" => "text",
                    "label" => "Nom de l'auteur",
                    "minLength" => 1,
                    "maxLength" => 310,
                    "id" => "author",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Sun Tzu",
                    "value" => '',
                    "error" => "Le nom de l'auteur doit faire entre 1 et 320 caractères",
                    "required" => true,
                ],
                "publication_date" => [
                    "type" => "date",
                    "label" => "Date de publication",
                    "id" => "publication_date",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "La date rentrée est incorrecte",
                    "required" => true
                ],
                "image" => [
                    "type" => "file",
                    "label" => "Image de couverture du livre",
                    "accept" => "image/png, image/jpeg",
                    "id" => "image",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Le fichier envoyé est incorrect",
                    "required" => false
                ],
                "publisher" => [
                    "type" => "text",
                    "label" => "Maison d'édition",
                    "minLength" => 1,
                    "maxLength" => 55,
                    "id" => "publisher",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Flammarion",
                    "error" => "La maison d'édition doit faire entre 1 et 100 caractères",
                    "required" => true
                ],
                "price" => [
                    "type" => "number",
                    "label" => "Prix de vente",
                    "min" => 1,
                    "step" => "any",
                    "id" => "price",
                    "class" => "form_input",
                    "placeholder" => "200",
                    "error" => "Le prix doit être au moins supérieur à 1€",
                    "required" => true
                ],

                "category[]" => [
                    "type" => "select",
                    "label" => "Catégorie",
                    "multiple" => "multiple",
                    "options" => $this->getCreatedCategory(),
                    "id" => "category",
                    "class" => "form_input",
                    "error" => "Veuillez choisir au minimum 1 catégorie",
                    "required" => true
                ],
                "stock_number" => [
                    "type" => "number",
                    "label" => "Nombre de livres en stock",
                    "min" => 1,
                    "id" => "stock_number",
                    "class" => "form_input",
                    "placeholder" => "200",
                    "error" => "Le nombre de livres doit être au moins supérieur à 1",
                    "required" => true
                ],

            ]
        ];
    }

    public function formDeleteBook(){
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_deletebook",
                "class" => "form_builder",
                "submit" => "Supprimer",
                "btn_class" => "btn btn-danger"
            ],
            "inputs" => [
                "delete" => [
                    "type" => "hidden",
                    "label" => "Voulez vous supprimez ce livre : ".$this->title." ?",
                    "id" => "title",
                    "class" => "form_input",
                    "value" => $this->slug,
                    "error" => "id not found",
                    "required" => true
                ]
            ]
        ];
    }

    public function formEditBook()
    {

        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_editbook",
                "class" => "form_builder",
                "enctype" => "multipart/form-data",
                "submit" => "Valider"
            ],
            "inputs" => [
                "title" => [
                    "type" => "text",
                    "label" => "Titre du livre",
                    "minLength" => 1,
                    "maxLength" => 255,
                    "id" => "title",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Harry Potter et La Coupe de Feu",
                    "value" => $this->title ?? "",
                    "error" => "Le titre doit faire entre 1 et 255 caractères ",
                    "required" => true
                ],
                "description" => [
                    "type" => "text",
                    "label" => "Description du livre",
                    "minLength" => 1,
                    "maxLength" => 200,
                    "id" => "description",
                    "class" => "form_input",
                    "placeholder" => "Un super livre",
                    "value" => $this->description ?? "",
                    "error" => "Votre description doit faire entre 1 et 255 caractères",
                    "required" => true
                ],
                "author" => [
                    "type" => "text",
                    "label" => "Nom de l'auteur",
                    "minLength" => 1,
                    "maxLength" => 310,
                    "id" => "author",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Sun Tzu",
                    "value" => $this->author ?? '',
                    "error" => "Le nom de l'auteur doit faire entre 1 et 320 caractères",
                    "required" => true,
                ],
                "publication_date" => [
                    "type" => "date",
                    "label" => "Date de publication",
                    "id" => "publication_date",
                    "class" => "form_input",
                    "placeholder" => "",
                    "value" => $this->publication_date ?? '',
                    "error" => "La date rentrée est incorrecte",
                    "required" => true
                ],
                "image" => [
                    "type" => "file",
                    "label" => "Image de couverture du livre",
                    "accept" => "image/png, image/jpeg",
                    "id" => "image",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Le fichier envoyé est incorrect",
                    "required" => false
                ],
                "publisher" => [
                    "type" => "text",
                    "label" => "Maison d'édition",
                    "minLength" => 1,
                    "maxLength" => 55,
                    "id" => "publisher",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Flammarion",
                    "value" => $this->publisher ?? '',
                    "error" => "La maison d'édition doit faire entre 1 et 100 caractères",
                    "required" => true
                ],
                "price" => [
                    "type" => "number",
                    "label" => "Prix de vente",
                    "min" => 1,
                    "step" => "any",
                    "id" => "price",
                    "class" => "form_input",
                    "placeholder" => "200",
                    "value" => $this->price ?? '',
                    "error" => "Le prix doit être au moins supérieur à 1€",
                    "required" => true
                ],
                "category[]" => [
                    "type" => "select",
                    "label" => "Catégorie",
                    "multiple" => "multiple",
                    "options" => $this->getCreatedCategory(),
                    "value" => explode(",",$this->getCategory()),
                    "id" => "category",
                    "class" => "form_input",
                    "error" => "Veuillez réessayer svp",
                    "required" => true
                ],
                "stock_number" => [
                    "type" => "number",
                    "label" => "Nombre de livres en stock",
                    "min" => 1,
                    "id" => "stock_number",
                    "class" => "form_input",
                    "placeholder" => "200",
                    "value" => $this->stock_number ?? '',
                    "error" => "Le nombre de livres doit être au moins supérieur à 1",
                    "required" => true
                ],
            ]
        ];
    }

    // Getters & Setters
    public function getTable()
    {
        return $this->table;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    public function getPublicationDate()
    {
        return $this->publication_date;
    }
    public function setPublicationDate($publication_date)
    {
        $this->publication_date = $publication_date;
    }
    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }
    public function getPublisher()
    {
        return $this->publisher;
    }
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function setCategory($category)
    {
        $this->category = $category;
    }
    public function getStockNumber()
    {
        return $this->stock_number;
    }
    public function setStockNumber($stock_number)
    {
        $this->stock_number = $stock_number >= 0 ? $stock_number : 0;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getAllBySlug($slug){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE slug = '".$slug."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function setAllBySlug($slug){
        $res = $this->getAllBySlug($slug);
        $res = $res[0];
        $this->setId($res['id']);
        $this->setTitle($res['title']);
        $this->setDescription($res['description']);
        $this->setAuthor($res['author']);
        $this->setImage($res['image']);
        $this->setPublicationDate($res['publication_date']);
        $this->setPublisher($res['publisher']);
        $this->setPrice($res['price']);
        $this->setCategory($res['category']);
        $this->setStockNumber($res['stock_number']);
        $this->setSlug($res['slug']);
    }

    public function deleteBySlug($slug){
        $query = "DELETE FROM " . $this->getTable() . " WHERE slug = '" . $slug ."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
    }

    public function getCreatedCategory(){
        $array = [];
        $query = "SELECT nameCategory FROM lbly_category ORDER BY nameCategory ASC";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        foreach ($res as $re) {
            array_push($array,$re["nameCategory"]);
        }
        return $array;
    }
}

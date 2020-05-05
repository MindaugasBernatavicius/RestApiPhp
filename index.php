<?php

// duomenai

class Author {
    private $id;
    private $name;
    public function __construct($id, $name){
        $this->id = $id;
        $this->name = $name;
    }
    public function getId(){ return $this->id; }
    public function setId($id){ $this->id = $id; }
    public function getName(){ return $this->name; }
    public function setName($name){ $this->name = $name;}
}

class Book {
    private $isbn;  
    private $title;
    private $authors = [];
    public function __construct($isbn, $title, $authors){
        $this->isbn = $isbn;
        $this->title = $title;
        $this->authors = $authors;
    }
    public function getIsbn(){ return $this->isbn; }
    public function setIsbn($isbn){ $this->isbn = $isbn; }
    public function getTitle(){ return $this->title; }
    public function setTitle($title){ $this->title = $title;}
    public function getAuthors(){ return $this->authors; }
    public function setAuthors($authors){ $this->authors = $authors;}
}

$books = [
    new Book("111-111", "Paris Hoteris", new Author(1, "Marytė Melnikaitė")),
    new Book("111-222", "Špiono Užrašai", new Author(2, "Ignius Knyguolis"))
];

// create authors from books
$authors = [];
foreach($books as $book)
    array_push($authors, $book->getAuthors());

// Logika
switch($_SERVER['REQUEST_METHOD']){
    case 'POST': print("POST"); break;
    case 'GET': 
        // get all authors
        if($_SERVER['REQUEST_URI'] === "/RestApiPhp/author"){
            print_r($authors);
        } elseif (substr($_SERVER['REQUEST_URI'], 0, strlen("/RestApiPhp/author/")) === "/RestApiPhp/author/"){
            $id = end(explode('/', $_SERVER['REQUEST_URI']));
            foreach($authors as $author){
                if($author->getId() == $id)
                    print_r($author);
            }
        }
        // print($_SERVER['REQUEST_URI']);
        break;
    case 'PUT': print("PUT"); break;
    case 'DELETE': print("DELETE"); break;
    default: die("HTTP verb unknown!");
}


?>
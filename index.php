<?php
    // duomenai
    class Author implements JsonSerializable {
        private $id;
        private $name;
        private $surname;
        public function __construct($id, $name, $surname){
            $this->id = $id;
            $this->name = $name;
            $this->surname = $surname;
        }
        public function getId(){ return $this->id; }
        public function setId($id){ $this->id = $id; }
        public function getName(){ return $this->name; }
        public function setName($name){ $this->name = $name;}
        public function getSurname(){ return $this->surname; }
        public function setSurname($surname){ $this->surname = $surname;}
        public function jsonSerialize() {
            $vars = get_object_vars($this);
            return $vars;
        }
    }

    // Read JSON file
    $FILE = 'authors.json';
    $json_file = file_get_contents($FILE);
    $authors_arr = json_decode($json_file, true);

    // create authors from file
    $authors = [];
    foreach($authors_arr as $author)
        array_push($authors, new Author(
            $author['id'], 
            $author['name'],
            $author['surname']
        ));

    // Logika
    switch($_SERVER['REQUEST_METHOD']){
        case 'POST': 
            if($_SERVER['REQUEST_URI'] === "/RestApiPhp/authors"){
                $inputJSON = file_get_contents('php://input');
                $x = json_decode($inputJSON, true);
                array_push($authors, new Author($x['id'], $x['name'],$x['surname']));
                $people_arr = json_encode($authors);
                file_put_contents($FILE, $people_arr);
            }
            break;
        case 'GET': 
            // ... get all authors
            if($_SERVER['REQUEST_URI'] === "/RestApiPhp/authors")
                prnt($authors);

            // ... get user by id
            elseif (substr($_SERVER['REQUEST_URI'], 0, strlen("/RestApiPhp/authors/")) === "/RestApiPhp/authors/"){
                $id = end(explode('/', $_SERVER['REQUEST_URI']));
                foreach($authors as $author) 
                    if($author->getId() == $id) prnt($author);
            }
            break;

        case 'PUT': 
            print("PUT");
            break;

        case 'DELETE': 
            print("DELETE");
            break;

        default: 
            die("HTTP verb unknown!");
    }

    // Helper function
    function prnt($x){
        echo json_encode($x, JSON_UNESCAPED_UNICODE);
    }
?>
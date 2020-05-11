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
    $json_string = file_get_contents($FILE);
    $authors_arr = json_decode($json_string, true);

    // create authors from file (deserializacija)
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
            // ... create new author
            // ... $ curl http://localhost/RestApiPhp/authors -s -D - -X POST -d '{"id":"5", "na"surname":"B."}'
            // ...  HTTP/1.1 200 OK
            // ...  Date: Mon, 11 May 2020 06:52:30 GMT
            // ...  Server: Apache/2.4.41 (Win64) OpenSSL/1.1.1c PHP/7.3.11
            // ...  X-Powered-By: PHP/7.3.11
            // ...  Content-Length: 0
            // ...  Content-Type: text/html; charset=UTF-8
            if($_SERVER['REQUEST_URI'] === "/RestApiPhp/authors"){
                $inputJSON = file_get_contents('php://input');
                $author_string = json_decode($inputJSON, true);
                array_push($authors, new Author($author_string['id'], $author_string['name'],$author_string['surname']));
                $author_arr = json_encode($authors);
                file_put_contents($FILE, $author_arr);
            }
            break;
        case 'GET': 
            // ... get all authors
            // ... $ curl http://localhost/RestApiPhp/authors -s -X GET
            // ... [{"id":"1","name":"Marytė","surname":"Melnikaitė"},{"id":"2","name":"Ignius","surname":"Knyguolis"},{"id":"3","name":"Jonas","surname":"Biliunas"}]
            if($_SERVER['REQUEST_URI'] === "/RestApiPhp/authors")
                prnt($authors);

            // ... get user by id
            // ... $ curl http://localhost/RestApiPhp/authors/1 -s -X GET
            // ... {"id":"1","name":"Marytė","surname":"Melnikaitė"}
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
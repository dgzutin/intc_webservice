<?php
// Routes

const booksFile = "/home/garbi/projects/lectures/webServices/intc_webservice/db/books.json";

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/books/', function ($request, $response, $args) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/' route");

    // REad JSON array from local file
    $booksString = file_get_contents(booksFile, true);
    $books = json_decode($booksString);

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    return $response->write(json_encode($books));
});

$app->get('/book/{id}', function ($request, $response) {

    $id = $request->getAttribute('id');
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    $booksString = file_get_contents(booksFile, true);
    $jsonBooks = json_decode($booksString);
    $books = $jsonBooks->books;

    foreach ($books as $book){

        if ($book->id == $id){
            return $response->write(json_encode($book));
        }
    }

    $message = array("message" => "Id not found");
    return $response->write(json_encode($message));
});

$app->post('/deleteBook/{id}', function ($request, $response) {

    header('Content-type: application/json');

    $id = $request->getAttribute('id');
    $booksString = file_get_contents(booksFile, true);
    $jsonBooks = json_decode($booksString);
    $books = $jsonBooks->books;

    $i =0;
    foreach ($books as $book){

        if ($book->id == $id){
            unset($books[$i]);
            $jsonBooks = array("books" => array_values($books));
            if (file_put_contents(booksFile, json_encode($jsonBooks)) != false){
                $message = array("message" => "Item was deleted",
                    "id" => $id);
                header('Access-Control-Allow-Origin: *');
                return $response->write(json_encode($message));
            }
        }
        $i++;
    }
    $message = array("message" => "Id not found");
    header('Access-Control-Allow-Origin: *');
    return $response->write(json_encode($message));
});

$app->post('/addBook/', function ($request, $response) {


    $parsedBody = json_decode($request->getBody());
    $booksString = file_get_contents(booksFile, true);
    $books = json_decode($booksString)->books;

    if ($parsedBody->author == null){
        $message = array("error" => "could not parse json request");
        header('Access-Control-Allow-Origin: *');
        return $response->write(json_encode($message));
    }

    $highest = 0;
    foreach ($books as $book){
        if ($highest < $book->id){
            $highest = $book->id;
        }
    }
    $highest++;
    $parsedBody->id = $highest;
    array_push($books,$parsedBody);

    $jsonBooks = array("books" => array_values($books));
    if (file_put_contents(booksFile, json_encode($jsonBooks)) != false){
        $message = array("message" => "Item was added",
                         "id" => $parsedBody->id);
    }
    else{
        $message = array("message" => "An error occurred");
    }
    header('Access-Control-Allow-Origin: *');
    return $response->write(json_encode($message));
});


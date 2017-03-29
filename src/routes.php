<?php
// Routes

include "config.php";

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
    $booksString = file_get_contents(DB_FILE_PATH, true);
    $books = json_decode($booksString);

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    if ($books != null){
        $books->exception = false;
        $books->message = count($books->books).' books found.';
        return $response->withJson($books);
    }
    $books['exception'] = true;
    $books['message'] = 'Could not read Json file';
    return $response->withJson($books);
});

$app->get('/book/{id}', function ($request, $response) {

    $id = $request->getAttribute('id');
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    $booksString = file_get_contents(DB_FILE_PATH, true);
    $jsonBooks = json_decode($booksString);
    $books = $jsonBooks->books;

    foreach ($books as $book){

        if ($book->id == $id){
            $book->exception = false;
            return $response->withJson($book);
        }
    }

    $message = array(
        'exception' => true,
        'message' => 'Id not found');
    return $response->withJson($message);
});

$app->post('/deleteBook/{id}', function ($request, $response) {

    header('Content-type: application/json');

    $id = $request->getAttribute('id');
    $booksString = file_get_contents(DB_FILE_PATH, true);
    $jsonBooks = json_decode($booksString);
    $books = $jsonBooks->books;

    $i =0;
    foreach ($books as $book){

        if ($book->id == $id){
            unset($books[$i]);
            $jsonBooks = array("books" => array_values($books));
            if (file_put_contents(DB_FILE_PATH, json_encode($jsonBooks)) != false){
                $message = array(
                    'exception' => false,
                    'message' => "Item was deleted",
                    'id' => $id);
                header('Access-Control-Allow-Origin: *');
                return $response->write(json_encode($message));
            }
        }
        $i++;
    }
    $message = array(
        'exception' => true,
        'message' => "Id not found");
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($message);
});

$app->post('/addBook/', function ($request, $response) {

    $parsedBody = json_decode($request->getBody());
    $booksString = file_get_contents(DB_FILE_PATH, true);
    $books = json_decode($booksString)->books;

    if ($parsedBody->author == null){
        $message = array(
            'exception' => true,
            'message' => "could not parse json request");

        header('Access-Control-Allow-Origin: *');
        return $response->withJson($message);
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
    if (file_put_contents(DB_FILE_PATH, json_encode($jsonBooks)) != false){
        $message = array(
            'exception' => false,
            'message' => "Item was added",
            'id' => $parsedBody->id);
    }
    else{
        $message = array(
            'exception' => true,
            'message' => "An error occurred. Could not open json file");
    }

    header('Access-Control-Allow-Origin: *');
    return $response->withJson($message);
});


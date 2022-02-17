<?php

require "class_router.php";
require "class_books.php";

use MamunApp\PHP\Routing\Router;
use MamunApp\Inventory\BookController;

Router::get('/', function () {
    echo "This is home page";
});

Router::get('/hello/{id}/', function ($hello) {
    echo $hello;
});

Router::get('/hello/:id/', function ($hello) {
    echo $hello;
});

Router::get('/test', function ($test) {
    echo $test;
});



Router::group('/city', function ($Router) {

    $Router::get('/user/:id', function ($id) {
        echo $id;
    });
    $Router::get('/user/last/:id', function ($id) {
        echo $id;
    });
});

Router::group('/section', function ($Router) {

    $Router::get('/', function () {
        echo "This is section home page";
    });
    $Router::get('/client/:id', function ($id) {
        echo $id;
    });
    $Router::get('/client/last/:id', function ($id) {
        echo $id;
    });

    $Router::get('/showPrice/:price/:date', [BookController::class, 'showPrice']);
});






Router::cleanup();



?>
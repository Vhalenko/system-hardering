<?php

require_once(__DIR__ . "/../controllers/ComicController.php");

Route::add('/comics', function () {
    $comicController = new ComicController();
    $result = $comicController->getAllComics();
    echo json_encode($result);
}, 'get');

Route::add('/comics/(\d+)', function ($id) {
    $comicController = new ComicController();
    $result = $comicController->getComicById($id);
    echo json_encode($result);
}, 'get');

Route::add('/comics', function () {
    $comicController = new ComicController();
    $result = $comicController->addComic();
    echo json_encode($result);
}, 'post');

Route::add('/comics/(\d+)', function ($id) {
    $comicController = new ComicController();
    $result = $comicController->updateComic($id);
    echo json_encode($result);
}, 'put');

Route::add('/comics/(\d+)', function ($id) {
    $comicController = new ComicController();
    $result = $comicController->deleteComic($id);
    echo json_encode($result);
}, 'delete');
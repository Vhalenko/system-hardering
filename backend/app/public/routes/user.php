<?php

require_once(__DIR__ . "/../controllers/UserController.php");

Route::add('/login', function () {
    $userController = new UserController();
    $result = $userController->login();
    echo json_encode($result);
}, 'post');

Route::add('/logout', function () {
    $userController = new UserController();
    $result = $userController->logout();
    echo json_encode($result);
}, 'post');

Route::add('/users', function () {
    $userController = new UserController();
    $result = $userController->getAllUsers();
    echo json_encode($result);
}, 'get');

Route::add('/users/create', function () {
    $userController = new UserController();
    $result = $userController->createUser();
    echo json_encode($result);
}, 'post');
<?php

require './config/envConfig.php';
require './Database.php';
require './controllers/UserController.php';

$db = Database::connect();
$userController = new UserController($db);

//var_dump($userController->create('Gustavo Morais', 'gusxmorais@gmail.com', '123123'));
var_dump($userController->list());
<?php

require 'Router.php';

$app = new Router();

class ItemController {

  public function index(Request $req, Response $res)
  {
    if ($req->params->itemname) {
      echo 'ok';
    }
  }

  public function create(Request $req, Response $res)
  {
    $res->status(201)->json('gustavo');
  }

  public function delete()
  {
    return 'Object deleted';
  }

}


$app->get('/item/:itemname', 'ItemController::index');
$app->post('/create', 'ItemController::create');
$app->delete('/delete', 'ItemController::delete');
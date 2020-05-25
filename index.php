<?php

require 'Router.php';

$app = new Router();

class ItemController {

  public function index(Request $req, Response $res)
  {
    echo $req->params->itemname;
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
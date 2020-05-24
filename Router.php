<?php

class Request {

  public ? object $params  = null;
  public ? object $query   = null;
  public ? object $headers = null;
  public ? object $body    = null;

}

class Response {

  public function status(int $code) {
    http_response_code($code);

    return $this;
  }

  public function json($data)
  {
    echo json_encode($data);
  }

}

class Router {

  private Request $request;
  private string $route;
  private string $method;
  private Array $routePaths;
  private bool $notFound = true;

  public function __construct()
  { 
    $route = $_SERVER['REQUEST_URI'];
    $queries = [];
    $param = [];

    if (strstr($route, '?')) {
      $splitedRoute = explode('?', $route);
      $route = $splitedRoute[0];
      $rawQuery = $splitedRoute[1];

      if (strlen($rawQuery) > 0) {
        if (strstr($rawQuery, '&')) {
          $splitedQuery = explode('&', $rawQuery);
          
          foreach ($splitedQuery as $rawQuery) {
            if (strstr($rawQuery, '=')) {
              $query = explode('=', $rawQuery);
              $queries[$query[0]] = $query[1];
            } else {
              $queries[] = $rawQuery;
            }
            
          }
        } else {
          if (strstr($rawQuery, '=')) {
            $query = explode('=', $rawQuery);
            $queries[$query[0]] = $query[1];
          } else {
            $queries[] = $rawQuery;
          }
        }
      }
    }

    $this->request = new Request();

    $this->route = $route;
    $this->routePaths = explode('/', $this->route);
    
    $this->request->query = (object) $queries;
    $this->request->body = (object) $_POST;
    $this->request->method = (object) $_SERVER['REQUEST_METHOD'];
  }

  public function __destruct()
  {
    if ($this->notFound) {
      echo 'Cannot ' . $this->method . ' ' . $this->route;
    }
  }

  private function verify(string $route)
  {
    if (strstr($route, '/:')) {
      $splitedRoute = explode('/:', $route);
      $route = $splitedRoute[0];
      $this->route = $route;
      $this->request->params = (object) [$splitedRoute[1] => array_pop($this->routePaths)];
    }

    if ($route != $this->route) {
      exit();
    } else {
      return $this->notFound = false;  
    }
  }

  public function get(string $route, string $callback)
  { 
    $this->verify($route);

    call_user_func($callback, $this->request, new Response);
  }

  public function post(string $route, string $callback)
  { 
    $this->verify($route);

    call_user_func($callback, $this->request, new Response);
  }

  public function delete(string $route, string $callback)
  { 
    $this->verify($route);

    call_user_func($callback, $this->request, new Response);
  }

}
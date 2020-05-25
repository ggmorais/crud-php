<?php

class Request {

  public ? string $method  = null;
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
      $route = explode('?', $route)[0];
    }

    if (substr($route, -1) == '/') {
      $route = substr($route, 0, -1);
    }

    $this->request = new Request();

    $this->route = $route;
    $this->routePaths = explode('/', $this->route);
    
    $this->request->query = (object) $_GET;
    $this->request->body = (object) $_POST;
    $this->request->method = $_SERVER['REQUEST_METHOD'];
  }

  public function __destruct()
  {
    if ($this->notFound) {
      echo 'Cannot ' . $this->request->method . ' ' . $this->route;
    }
  }

  private function verify(string $route)
  {
    if (strstr($route, '/:')) {
      $breakAt = strpos($route, '/:');
      $splitedRoute = explode('/:', $route);
      $abstractRoute = $splitedRoute[0];

      if (
          !substr($this->route, $breakAt) && substr($route, -1) != '?' 
          || strstr(substr($this->route, $breakAt + 1, -1), '/')
        ) {
          exit();
      } 
      
      $this->route = substr($this->route, 0, $breakAt);
      $this->request->params = (object) [$splitedRoute[1] => array_pop($this->routePaths)];
    }

    if ($abstractRoute != $this->route) {
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
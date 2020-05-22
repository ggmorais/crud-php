<?php

class UserController {

  public PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function list(string $email = '') {
    $sql = 'SELECT email, fullname FROM users';

    if ($email) {
      $sql = 'SELECT emil, fullname FROM users WHERE email = $email';
    }

    $query = $this->db->query($sql);

    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create(string $fullname, string $email, string $password) {
    $sql = 'INSERT INTO users (fullname, email, password) VALUES ("test", "test", "test")';
    $this->db->prepare($sql)->execute();
  }

}
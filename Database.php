<?php

class Database {

  public function connect() {
    return new PDO(
      DB_TYPE . ':dbname=' . DB_NAME . ';host=' . DB_HOST, 
      DB_USER, 
      DB_PASSWORD
    );
  }

}

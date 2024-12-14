<?php

namespace Lapetus\Database;

class Database
{
  public \PDO $pdo;

  public function __construct($config = [])
  {
    $username = $config['username'] ?? null;
    $password = $config['password'] ?? null;

    $dsn = "sqlite:{$config['database']}";

    $this->pdo = new \PDO(
      $dsn,
      $username,
      $password,
      [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      ]
    );
  }
}

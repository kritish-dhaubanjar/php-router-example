<?php

namespace Lapetus\Database;

class Database
{
  public \PDO $pdo;

  public function __construct($config = [])
  {
    $driver = $config['driver'];

    $host = $config['host'] ?? null;
    $port = $config['port'] ?? null;
    $database = $config['database'] ?? null;
    $username = $config['username'] ?? null;
    $password = $config['password'] ?? null;

    $dsn = match ($driver) {
      'sqlite' => "sqlite:{$database}",
      'mysql' => "mysql:host={$host};port={$port};dbname={$database}"
    };

    $this->pdo = new \PDO(
      $dsn,
      $username,
      $password,
      [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      ]
    );
  }

  public function prepare($sql): \PDOStatement
  {
    return $this->pdo->prepare($sql);
  }
}

<?php

namespace Lapetus\Database;

use Lapetus\Application;

class Migrator
{
  private String $path;
  private Database $database;

  public function __construct()
  {
    $this->database = Application::$application->DB;
    $this->path = Application::$application->paths('migrations');
    $this->boot();
  }

  private function boot()
  {
    $this->database->pdo->exec(
      "CREATE TABLE IF NOT EXISTS migrations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        migration VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )"
    );
  }

  private function store($migration)
  {
    $statement = $this->database->pdo->prepare("INSERT INTO migrations(migration) VALUES (:migration)");
    $statement->bindParam(':migration', $migration, \PDO::PARAM_STR);
    $statement->execute();
  }

  private function destroy($migration)
  {
    $statement = $this->database->pdo->prepare("DELETE FROM migrations WHERE migration = :migration");
    $statement->bindParam(':migration', $migration, \PDO::PARAM_STR);
    $statement->execute();
  }

  private function index()
  {
    $statement = $this->database->pdo->query("SELECT migration FROM migrations");

    return $statement->fetchAll(\PDO::FETCH_COLUMN);
  }

  public function migrate()
  {
    $migrations = $this->index();

    $files = scandir($this->path);

    $pendingMigrations = array_diff($files, $migrations);
    $pendingMigrations = array_filter($pendingMigrations, fn($migration) => pathinfo($migration, PATHINFO_EXTENSION) === 'php');

    if (count($pendingMigrations) === 0) {
      logger("Nothing to migrate.");

      return;
    }

    logger("Running migrations.");

    foreach ($pendingMigrations as $migration) {
      $extension = pathinfo($migration, PATHINFO_EXTENSION);

      if ($extension != 'php') {
        continue;
      }

      logger($migration);

      $instance = require_once($this->path . DIRECTORY_SEPARATOR . $migration);
      $instance->up();

      $this->store($migration);
    }
  }

  public function rollback()
  {
    $statement = $this->database->pdo->query("SELECT migration, MAX(id) FROM migrations");
    $migration = $statement->fetch(\PDO::FETCH_COLUMN);
    $statement->closeCursor();

    if (!$migration) {
      logger("Nothing to rollback.");

      return;
    }

    logger("Rolling back migrations.");
    logger($migration);

    $instance = require_once($this->path . DIRECTORY_SEPARATOR . $migration);
    $instance->down();

    $this->destroy($migration);
  }
}

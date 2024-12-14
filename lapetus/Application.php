<?php

namespace Lapetus;

use Lapetus\Database\Database;
use Lapetus\Support\Env;
use Lapetus\Routing\Router;

class Application
{
  private Env $env;
  private Database $DB;
  private array $path;
  private array $config;
  private Router $router;

  public static Application $application;

  private function __construct() {}

  public static function configure($path)
  {
    $application = new Application();

    self::$application = $application;

    $application->setPaths($path);
    $application->setEnvironment();
    $application->setConfigurations();
    $application->setDatabase();

    return self::$application;
  }

  public function paths(String $key)
  {
    return $this->path[$key];
  }

  public static function withRouting($routes)
  {
    foreach ($routes as $key => $value) {
      require_once $value;
    }

    self::$application->router = new Router();
  }

  public function setPaths(String $path)
  {
    $this->path['app'] = $path;
    $this->path['config'] = $path . DIRECTORY_SEPARATOR . "config";
    $this->path['database'] = $path . DIRECTORY_SEPARATOR . "database";
    $this->path['.env'] = $path . DIRECTORY_SEPARATOR . ".env";
  }

  private function setEnvironment()
  {
    $this->env = Env::configure($this->path['.env']);
  }

  private function setConfigurations()
  {
    $files = scandir($this->path['config']);
    $config = [];

    foreach ($files as $file) {
      $extension = pathinfo($file, PATHINFO_EXTENSION);
      $filename = pathinfo($file, PATHINFO_FILENAME);

      if ($extension !== 'php') {
        continue;
      }

      $config[$filename] = include($this->path['config'] . DIRECTORY_SEPARATOR . $file);
    }

    $this->config = $config;
  }

  private function setDatabase()
  {
    $database = $this->config['database'];
    $driver = $database['default'];
    $connections = $database['connections'];

    $config = $connections[$driver];

    $this->DB = new Database($config);
  }
}

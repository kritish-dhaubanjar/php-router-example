<?php

namespace Lapetus;

use Lapetus\Support\Env;
use Lapetus\Routing\Router;
use Lapetus\Database\Database;
use Lapetus\Support\Config;

class Application
{
  private array $path;

  public Env $env;
  public Database $DB;
  public Router $router;
  public Config $config;
  public Request $request;
  public Response $response;

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

    self::$application->request = new Request();
    self::$application->response = new Response();
    self::$application->router = new Router(self::$application->request);

    try {
      self::$application->router->resolve();
    } catch (\Exception $exception) {

      if (!config('app.debug')) {
        $exception = null;
      }

      return response()->statusCode(404)->view('error', $exception);
    }
  }

  public function setPaths(String $path)
  {
    $this->path['app'] = $path;
    $this->path['.env'] = $path . DIRECTORY_SEPARATOR . ".env";
    $this->path['config'] = $path . DIRECTORY_SEPARATOR . "config";
    $this->path['database'] = $path . DIRECTORY_SEPARATOR . "database";
    $this->path['resources'] = $path . DIRECTORY_SEPARATOR . "resources";

    $this->path['views'] = $this->path['resources'] . DIRECTORY_SEPARATOR . "views";
    $this->path['migrations'] = $this->path['database'] . DIRECTORY_SEPARATOR . "migrations";
  }

  private function setEnvironment()
  {
    $this->env = Env::configure($this->path['.env']);
  }

  private function setConfigurations()
  {
    $this->config = Config::configure($this->path['config']);
  }

  private function setDatabase()
  {
    $driver = config('database.default');
    $connections = config("database.connections");

    $config = $connections[$driver];

    $this->DB = new Database($config);
  }
}

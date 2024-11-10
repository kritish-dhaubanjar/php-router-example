<?php

namespace App;

class Router
{
  public function __construct()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI']);
    $path = rtrim($uri['path'], '/');

    $routes = Route::$routes[$method];

    [$handler, $arguments] = self::match($routes, $path);

    if (is_callable($handler)) {
      call_user_func($handler, ...$arguments);
      return;
    }

    if (is_array($handler)) {
      [$class, $method] = $handler;
      call_user_func_array([new $class(), $method], ...$arguments);
      return;
    }

    echo "NotFoundHttpException";
  }

  private static function match(array $routes, string $path)
  {
    if (array_key_exists($path, $routes)) {
      return [$routes[$path], []];
    }

    $pattern = '/\{(.*)\}/';

    foreach (array_keys($routes) as $route) {
      if (!preg_match($pattern, $route)) {
        continue;
      }

      $route_fragments = explode('/', ltrim($route, '/'));
      $path_fragments = explode('/', ltrim($path, '/'));

      if (count($route_fragments) !== count($path_fragments)) {
        continue;
      }

      $arguments = self::compare($route_fragments, $path_fragments);

      if ($arguments) {
        return [$routes[$route], $arguments];
      }
    }

    return [null, null];
  }

  private static function compare(array $route_fragments, array $path_fragments)
  {
    $pattern = '/\{(.*)\}/';

    $arguments = [];

    foreach ($route_fragments as $index => $fragment) {
      if (preg_match($pattern, $fragment)) {
        array_push($arguments, $path_fragments[$index]);
        continue;
      }

      if ($path_fragments[$index] !== $fragment) {
        return null;
      }
    }

    return $arguments;
  }
}

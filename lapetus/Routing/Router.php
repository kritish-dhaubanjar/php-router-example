<?php

namespace Lapetus\Routing;

use Lapetus\Request;
use Lapetus\Exception\RouteNotFoundException;

class Router
{
  private $handler;
  private $arguments;

  private Request $request;

  public function __construct(Request $request)
  {
    $this->request = $request;

    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI']);
    $path = rtrim($uri['path'], '/');

    $routes = Route::$routes[$method];

    [$handler, $arguments] = self::match($routes, $path);

    $this->handler = $handler;
    $this->arguments = $arguments;
  }

  public function resolve()
  {
    if (is_callable($this->handler)) {
      call_user_func($this->handler, $this->request, ...$this->arguments);
      return;
    }

    if (is_array($this->handler)) {
      [$class, $method] = $this->handler;
      call_user_func_array([new $class(), $method], array_merge([$this->request, ...$this->arguments]));
      return;
    }

    throw new RouteNotFoundException();
  }

  private static function match(array $routes, string $path)
  {
    foreach ($routes as $route => $handler) {
      if (rtrim($route, '/') === $path) {
        return [$handler, []];
      }
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

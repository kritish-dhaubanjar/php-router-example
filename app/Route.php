<?php

namespace App;

class Route
{
  public static array $routes;

  public static function get(string $route, callable|array $handler): void
  {
    self::$routes['GET'][$route] = $handler;
  }

  public static function post(string $route, callable|array $handler): void
  {
    self::$routes['POST'][$route] = $handler;
  }

  public static function put(string $route, callable|array $handler): void
  {
    self::$routes['PUT'][$route] = $handler;
  }

  public static function patch(string $route, callable|array $handler): void
  {
    self::$routes['PATCH'][$route] = $handler;
  }

  public static function delete(string $route, callable|array $handler): void
  {
    self::$routes['DELETE'][$route] = $handler;
  }
}

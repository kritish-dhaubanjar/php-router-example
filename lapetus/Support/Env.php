<?php

namespace Lapetus\Support;

class Env
{
  private static ?Env $env = null;
  private static array $map = [];

  private function __construct(String $path)
  {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      $line = trim($line);

      if (strpos($line, '#') === 0) {
        continue;
      }

      list($key, $value) = explode('=', $line, 2);

      $key = trim($key);
      $value = self::cast(trim($value));

      self::$map[$key] = $value;
    }
  }

  public static function configure(String $path)
  {
    if (self::$env == null) {
      self::$env = new Env($path);
    }

    return self::$env;
  }

  private static function cast($value)
  {
    switch ($value) {
      case 'null':
        return null;
      case 'true':
        return true;
      case 'false':
        return false;
      default:
        return $value;
    }
  }

  public static function get($key, $default = null)
  {
    if (key_exists($key, self::$map)) {
      return self::$map[$key];
    }

    return $default;
  }
}

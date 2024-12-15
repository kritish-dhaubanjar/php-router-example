<?php

namespace Lapetus\Support;

class Config
{
  private static ?Config $config = null;
  private static array $map = [];

  private function __construct(string $path)
  {
    $files = scandir($path);
    $config = [];

    foreach ($files as $file) {
      $extension = pathinfo($file, PATHINFO_EXTENSION);
      $filename = pathinfo($file, PATHINFO_FILENAME);

      if ($extension !== 'php') {
        continue;
      }

      $config[$filename] = include($path . DIRECTORY_SEPARATOR . $file);
    }

    self::$map = $config;
  }

  public static function configure(string $path)
  {
    if (self::$config == null) {
      self::$config = new Config($path);
    }

    return self::$config;
  }

  public static function get($key, $default = null)
  {
    if (key_exists($key, self::$map)) {
      return self::$map[$key];
    }

    $map = self::$map;
    $segments = explode('.', $key);

    foreach ($segments as $segment) {
      if (key_exists($segment, $map)) {
        $map = $map[$segment];
      } else {
        return $default;
      }
    }

    return $map;
  }
}

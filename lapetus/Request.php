<?php

namespace Lapetus;

class Request
{
  private static function get($source, $filter, $attribute = null)
  {
    $data = [];

    foreach ($source as $key => $value) {
      $data[$key] = filter_input($filter, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    if ($attribute) {
      return $data[$attribute];
    }

    return $data;
  }

  public static function query($attribute = null)
  {
    $data = self::get($_GET, INPUT_GET, $attribute);
    return $data;
  }

  public static function input($attribute = null)
  {
    $data = self::get($_POST, INPUT_POST, $attribute);
    return $data;
  }
}

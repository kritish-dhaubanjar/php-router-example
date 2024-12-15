<?php

use Lapetus\Response;
use Lapetus\Application;
use Lapetus\Support\Env;
use Lapetus\Support\Config;

function database_path($database = '')
{
  return Application::$application->paths('database') . DIRECTORY_SEPARATOR . $database;
}

function logger($message)
{
  echo "$message" . PHP_EOL . PHP_EOL;
}

function env($key, $default = null)
{
  return Env::get($key, $default);
}

function config($key, $default = null)
{
  return Config::get($key, $default);
}

function response($status = 200, array $headers = [])
{
  $response = new Response();

  $response->statusCode($status);
  $response->headers($headers);

  return $response;
}

function view($view, $data = [])
{
  include(Application::$application->paths('views') . DIRECTORY_SEPARATOR . $view . '.view.php');
}

<?php

use Lapetus\Application;

function database_path($database = '')
{
  return Application::$application->paths('database') . DIRECTORY_SEPARATOR . $database;
}

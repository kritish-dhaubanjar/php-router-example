<?php

namespace Lapetus\Database;

use Lapetus\Application;

abstract class Migration
{
  protected Database $DB;

  public function __construct()
  {
    $this->DB = Application::$application->DB;
  }

  public abstract function up();
  public abstract function down();
}

<?php

namespace Lapetus\Exception;

class RouteNotFoundException extends \Exception
{
  protected $message = 'RouteNotFoundException';
  protected $code = 404;
}

<?php

use Lapetus\Support\Env;

return [
  'name' => Env::get('APP_NAME', 'Lapetus'),
  'url' => Env::get('APP_URL', 'http://localhost'),
];

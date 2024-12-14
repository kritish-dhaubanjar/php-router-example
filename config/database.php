<?php

use Lapetus\Support\Env;

return [
  'default' => Env::get('DB_CONNECTION', 'sqlite'),

  'connections' => [
    'sqlite' => [
      'driver' => 'sqlite',
      'database' => Env::get('DB_DATABASE', database_path('database.sqlite'))
    ],
  ],
];

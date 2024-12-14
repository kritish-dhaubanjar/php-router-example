<?php

use Lapetus\Application;

Application::configure(dirname(__DIR__))->withRouting(['web' => __DIR__ . '/../routes/web.php']);

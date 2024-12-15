<?php

declare(strict_types=1);
set_error_handler(fn($severity, $message, $file, $line) => throw new \ErrorException($message, $severity, $severity, $file, $line));

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/app.php';

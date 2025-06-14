<?php
// config/env.php

function loadEnv($path = null)
{
    if ($path === null) {
        $path = __DIR__ . '/../.env';
    }

    if (!file_exists($path)) {
        throw new Exception('.env file not found at ' . $path);
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv("$name=$value");
            $_SERVER[$name] = $value;
            $_ENV[$name] = $value;
        }
    }
}
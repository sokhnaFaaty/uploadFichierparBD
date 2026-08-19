<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    protected function value(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
}

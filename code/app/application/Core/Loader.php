<?php

namespace Core;

class Loader
{
    public static function loadClass($class, string $method): ?string
    {
        if (!class_exists($class)) {
            return null;
        }
        $instance = new $class();
        return call_user_func([$instance, $method]);
    }

    public static function loadTemplate(string $path)
    {
        include ROOT_PATH . "app/application/Template/$path.html";
    }
}
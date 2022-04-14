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

    public static function getInstance(string $class): array
    {
        $args = [];
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if ($constructor) {
            $params = $constructor->getParameters();
            $subParams = [];
            foreach ($params as $param) {
                $argClass = $param->getType()->getName();
                if (class_exists($argClass)) {
                    $subParams = self::getInstance($argClass);
                }
            }
            $args[] = new $class(...$subParams);
        }
        return $args;
    }

    public static function loadTemplate(string $path)
    {
        include ROOT_PATH . "app/application/Template/$path.html";
    }
}
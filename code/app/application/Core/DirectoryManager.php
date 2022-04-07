<?php

namespace Core;

class DirectoryManager
{
    private static string $root;

    public static function setRoot(): string
    {

        return static::$root;
    }

    public static function getRoot(): string
    {
        return static::$root;
    }
}
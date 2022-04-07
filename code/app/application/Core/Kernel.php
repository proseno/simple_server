<?php

namespace Core;

class Kernel
{
    public static function createApplication(): Application
    {
        return new Application();
    }
}
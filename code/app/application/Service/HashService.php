<?php

namespace Service;

class HashService
{
    public function encode(string $string): string
    {
        return md5($string);
    }
}
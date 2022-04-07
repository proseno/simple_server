<?php

namespace Core\Request;

class Parser
{
    protected static \Core\Request\Request $request;

    public static function getRequest(): ?Request
    {
        if (!isset(static::$request))
        {
            static::parseRequest();
        }
        return static::$request;
    }

    protected static function parseRequest()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
            "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        static::$request = new Request();
        static::$request->setUrl($url)
            ->setGetParams($_GET)
            ->setPostParams($_POST);
    }
}
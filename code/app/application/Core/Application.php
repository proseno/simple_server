<?php

namespace Core;

class Application
{
    private Request\Request $request;

    public function run()
    {
        $request = \Core\Request\Parser::getRequest();
        $path = trim(parse_url($request->getUrl(), PHP_URL_PATH), "/");
        if ($path == '') {
            $path = "Home";
        } else {
            $path = explode('/', $path);
            array_walk($path, fn(&$item) => $item = ucfirst($item));
            $path = implode("\\", $path);
        }
        $path = "\\Action\\$path";
        $result = \Core\Loader::loadClass($path, 'execute');
        if (!$result) {
            http_response_code(404);
            return;
        }
        echo $result;
    }
}
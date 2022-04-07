<?php

namespace Core;

class Connection extends DataObject
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->setConfig();
    }

    private function setConfig()
    {
        $env = include ROOT_PATH . "app/config/env.php";
        $config = $env['db']['connection'];
        $this->setData($config);
    }
}
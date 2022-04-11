<?php

namespace Core\Connection;

use Core\DataObject;
use const ROOT_PATH;

class Connection extends DataObject implements ConnectionInterface
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->setConnectionConfig();
    }

    private function setConnectionConfig()
    {
        $env = include ROOT_PATH . "app/config/env.php";
        $config = $env['db']['connection'];
        $this->setData($config);
    }

    public function getUsername(): string
    {
        return (string)$this->getData(static::USERNAME);
    }

    public function getPassword(): string
    {
        return (string)$this->getData(static::PASSWORD);
    }

    public function getHost(): string
    {
        return (string)$this->getData(static::HOST);
    }

    public function getDbname(): string
    {
        return (string)$this->getData(static::DBNAME);
    }

    public function getPort(): string
    {
        return (string)$this->getData(static::PORT);
    }
}
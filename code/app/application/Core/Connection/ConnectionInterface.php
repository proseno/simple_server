<?php

namespace Core\Connection;

interface ConnectionInterface
{
    const HOST = 'host';
    const DBNAME = 'dbname';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const PORT = 'port';

    public function getUsername(): string;

    public function getPassword(): string;

    public function getHost(): string;

    public function getDbname(): string;

    public function getPort(): string;
}
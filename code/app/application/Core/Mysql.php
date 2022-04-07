<?php

namespace Core;

class Mysql
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(string $table, array $data)
    {
        $connection = new \PDO(
            "mysql:host={$this->connection->getHost()};dbname={$this->connection->getDbname()}",
            $this->connection->getUsername(),
            $this->connection->getPassword()
        );
        $connection->beginTransaction();

        $sql = "INSERT INTO " . $table . "(" . implode(", ", array_keys($data)) . ")" .
            "VALUES (" . implode(array_values($data)) . ")";

        $connection->exec($sql);
        $result = $connection->commit();
        if (!$result) {
            $connection->rollBack();
        }
    }
}
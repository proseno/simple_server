<?php

namespace Core\DB;

use Core\Connection\Connection;

class Mysql
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(string $table, array $data): bool|int
    {
        $connection = new \PDO(
            "mysql:host={$this->connection->getHost()}" .
            ";port={$this->connection->getPort()}" .
            ";dbname={$this->connection->getDbname()}",
            $this->connection->getUsername(),
            $this->connection->getPassword()
        );
        $connection->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        $connection->beginTransaction();

        $bind = array_values($data);
        array_walk($bind, fn(&$item) => $item = "'$item'");

        $sql = "INSERT INTO " . $table . "(" . implode(", ", array_keys($data)) . ")" .
            " VALUES (" . implode(", ", $bind) . ");";

        $result = $connection->exec($sql);
        $success = $connection->commit();
        if (!$success) {
            $connection->rollBack();
        }
        return $result;
    }

    public function select(string $table, string $cond)
    {
        $connection = new \PDO(
            "mysql:host={$this->connection->getHost()}" .
            ";port={$this->connection->getPort()}" .
            ";dbname={$this->connection->getDbname()}",
            $this->connection->getUsername(),
            $this->connection->getPassword()
        );
        $connection->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM $table WHERE $cond;";

        $stmt = $connection->query($sql);
        return $stmt->fetch();
    }
}
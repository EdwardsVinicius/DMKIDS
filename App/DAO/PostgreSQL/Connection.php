<?php

namespace App\DAO\PostgreSQL;

abstract class Connection
{
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct()
    {
        $host = getenv('BD_POSTGRESQL_HOST');
        $port = getenv('BD_POSTGRESQL_PORT');
        $user = getenv('BD_POSTGRESQL_USER');
        $pass = getenv('BD_POSTGRESQL_PASSWORD');
        $dbname = getenv('BD_POSTGRESQL_DBNAME');

        $dsn = "pgsql:host={$host};dbname={$dbname};port={$port}";

        $this->pdo = new \PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );
    }
}
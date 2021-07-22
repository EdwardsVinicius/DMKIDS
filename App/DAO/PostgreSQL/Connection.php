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
        $host = getenv('BD_HOST');
        $port = getenv('BD_PORT');
        $user = getenv('BD_USER');
        $pass = getenv('BD_PASSWORD');
        $dbname = getenv('BD_DBNAME');

        $dsn = "mysql:host={$host};dbname={$dbname};port={$port}";

        $this->pdo = new \PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );
    }
}
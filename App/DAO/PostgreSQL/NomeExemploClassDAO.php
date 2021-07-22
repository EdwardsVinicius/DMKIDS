<?php

namespace App\DAO\PostgreSQL;

use App\DAO\PostgreSQL\Conexao;

final class NomeExemploClassDAO extends Conexao
{
    public function __construct()
    {
        parent::__construct(); 
    }

    public function nomeMetodoDaClass(): array
    {
        $statement = $this->pdo
            ->prepare(" SELECT 
                            *
                        FROM nometabela
                        WHERE condicao
            ");
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    
}
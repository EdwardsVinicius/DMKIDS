<?php

namespace App\DAO\PostgreSQL;

use App\DAO\PostgreSQL\Connection;
use App\Models\PostgreSQL\PersonModel;

final class PersonDAO extends Connection
{
    public function __construct(\PDO $connection = null)
    {
        parent::__construct(); 
        if (isset($connection)) {
            $this->pdo = $connection;
        }
    }

    public function listPersons(): array
    {
        $statement = $this->pdo
            ->prepare(" SELECT 
                            * 
                        FROM administracao.pessoa
                        ");
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function getPersonByCpf(string $cpf): array
    {
        $statement = $this->pdo
            ->prepare(' SELECT 
                            idpessoa,
                            naturalidade,
                            nome,
                            datanascimento,
                            sexo,
                            cpf,
                            nomemae,
                            email,
                            telefone1,
                            telefone2
                        FROM administracao.pessoa
                        WHERE idpessoa = :cpf
                        ORDER BY idpessoa
            ');
        $statement->bindValue('cpf', $cpf);
        $statement->execute();
        $response = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $response;
    }

    public function getPersonById(int $id): array
    {
        $statement = $this->pdo
            ->prepare(' SELECT 
                            idpessoa,
                            naturalidade,
                            nome,
                            datanascimento,
                            sexo,
                            cpf,
                            nomemae,
                            email,
                            telefone1,
                            telefone2
                        FROM administracao.pessoa
                        WHERE idpessoa = :id
                        ORDER BY idpessoa
            ');
        $statement->bindValue('id', $id);
        $statement->execute();
        $response = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $response;
    }

    public function registerPerson(PersonModel $person)
    {   
        $statement = $this->pdo
            ->prepare('INSERT INTO administracao.pessoa (
                nome, 
                datanascimento, 
                genero,
                idade,
                cidade,
                estado,
                tempoDiagnostico
            )
            VALUES(
                :nome, 
                :datanascimento, 
                :genero,
                :idade,
                :cidade,
                :estado,
                :tempoDiagnostico
            );');

        $statement->execute([
            'nome' => $person->getName(),
            'datanascimento' => $person->getBirth(),
            'sexo' => $person->getGender(),
            ''
        ]);
        //$result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $idPerson =  $this->pdo->lastInsertId();   

        return $idPerson;
    }

    public function updatePersonData(PersonModel $person): array
    {
        
        $statement = $this->pdo
            ->prepare(' UPDATE administracao.pessoa SET
                            naturalidade = :naturalidade,
                            nome = :nome, 
                            datanascimento = :datanascimento, 
                            sexo = :sexo, 
                            cpf = :cpf, 
                            nomemae = :nomemae, 
                            email = :email, 
                            telefone1 = :telefone1, 
                            telefone2 = :telefone2
                        WHERE
                            cpf = :cpf
        ;');

        $statement->execute([
            'naturalidade' => $person->getNaturalness(),
            'nome' => $person->getName(),
            'datanascimento' => $person->getBirth(),
            'sexo' => $person->getGender(),
            'cpf' => $person->getCpf(),
            'nomemae' => $person->getMotherName(),
            'email' => $person->getEmail(),
            'telefone1' => $person->getPhone1(),
            'telefone2' => $person->getPhone2()
        ]);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}
<?php

namespace App\DAO\PostgreSQL;

use App\DAO\PostgreSQL\Connection;
use App\Models\PostgreSQL\UserModel;

use App\DAO\PostgreSQL\PersonDAO;
use App\Models\PostgreSQL\PersonModel;

final class UserDAO extends Connection
{
    public function __construct(\PDO $connection = null)
    {
        parent::__construct(); 
        if (isset($connection)) {
            $this->pdo = $connection;
        }
    }

    public function registerUser(UserModel $user)
    {
        $statement = $this->pdo
            ->prepare(' SELECT
                            *
                        FROM adm.usuario
                        WHERE
                            :login = email

            ');
        $statement->execute([
            'login'=>$user->getLogin()
        ]);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($result){
            return null;
        }

        $statement = $this->pdo
            ->prepare(' INSERT INTO 
                        adm.usuario (
                            idpessoa, 
                            email, 
                            senha
                        ) VALUES (
                            :idpessoa,
                            :login,
                            :senha 
                        );
            ');
        $statement->execute([
            'idpessoa'=>$user->getIdPerson(),
            'login'=>$user->getLogin(),
            'senha'=>$user->getPassword()
        ]);

        $idUser =  $this->pdo->lastInsertId();

        
        return $idUser;
    }

    public function listUsers(): array
    {
        $statement = $this->pdo
            ->prepare(' SELECT 
                            idusuario,
                            idpessoa,
                            email
                        FROM adm.usuario
                        ORDER BY idusuario
            ');
        $statement->execute();
        $response = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $response;
    }

    public function updateUserData(UserModel $user): array
    {
        
        return $response;
    }

    public function queryUserRest(string $email): array
    {
        $statement = $this->pdo
            ->prepare(' SELECT
                            u.idusuario,
                            p.nome,
                            u.email
                        FROM adm.usuario u
                        join adm.pessoa p
                            on u.idpessoa = p.idpessoa
                        WHERE email = :email
            ');
        $statement->bindParam('email', $email);
        $statement->execute();
        $user = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $user;
    }

    public function updatePassword(UserModel $user): bool
    {
        $statement = $this->pdo
            ->prepare(' UPDATE admin_dmkids.user SET
                            password = :senha
                        WHERE email = :login
                
            ');
        $statement->execute([
            'login'=>$user->getLogin(),
            'senha'=>$user->getPassword()
        ]);
        $success = $statement->rowCount() === 1;

        return $success;
    }

    public function userLogin(string $user): ?UserModel
    {
        $statement = $this->pdo
            ->prepare(' SELECT 
                            idusuario,
                            email,
                            senha,
                            idpessoa
                        FROM adm.usuario
                        WHERE email = :usuario
            ');
        $statement->bindParam('usuario', $user);
        $statement->execute();
        $users = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(count($users) === 0)
            return null;
        
        $user = new UserModel();
        $user
            ->setIdPerson($users[0]['idpessoa'])
            ->setLogin($users[0]['email'])
            ->setPassword($users[0]['senha']);


        return $user;
    }

    public function getUserById(int $idUser)
    {
        $statement = $this->pdo
            ->prepare(' SELECT 
                            idusuario,
                            idpessoa,
                            email,
                            ativo
                        FROM adm.usuario
                        WHERE idusuario = :idusuario
                        ORDER BY idusuario,ativo
            ');
        $statement->bindParam('idusuario', $idUser);
        $statement->execute();
        $response = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $response;
    }

}
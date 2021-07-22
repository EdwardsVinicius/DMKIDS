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
                        FROM admin_dmkids.user
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
                        admin_dmkids.user (
                            idperson, 
                            email, 
                            password, 
                            active
                        ) VALUES (
                            :idpessoa,
                            :login,
                            :senha,
                            :ativo   
                        );
            ');
        $statement->execute([
            'idpessoa'=>$user->getIdPerson(),
            'login'=>$user->getLogin(),
            'senha'=>$user->getPassword(),
            'ativo' =>$user->getActive()
        ]);

        $idUser =  $this->pdo->lastInsertId();

        
        return $idUser;
    }

    public function listUsers(): array
    {
        $statement = $this->pdo
            ->prepare(' SELECT 
                            id_user,
                            idperson,
                            email,
                            active
                        FROM admin_dmkids.user
                        ORDER BY id_user
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
                            u.id_user,
                            p.name,
                            u.email
                        FROM admin_dmkids.user u
                        join admin_dmkids.person p
                            on u.idperson = p.idperson
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
                            id_user,
                            email,
                            password,
                            idperson
                        FROM admin_dmkids.user
                        WHERE email = :usuario
            ');
        $statement->bindParam('usuario', $user);
        $statement->execute();
        $users = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(count($users) === 0)
            return null;
        
        $user = new UserModel();
        $user
            ->setIdPerson($users[0]['idperson'])
            ->setLogin($users[0]['email'])
            ->setPassword($users[0]['password']);


        return $user;
    }

    public function getUserById(int $idUser)
    {
        $statement = $this->pdo
            ->prepare(' SELECT 
                            idusuario,
                            idpessoa,
                            login,
                            ativo
                        FROM administracao.usuario
                        WHERE idusuario = :idusuario
                        ORDER BY idusuario,ativo
            ');
        $statement->bindParam('idusuario', $idUser);
        $statement->execute();
        $response = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $response;
    }

}
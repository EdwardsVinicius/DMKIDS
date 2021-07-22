<?php

namespace App\Models\PostgreSQL;

final class UserModel
{ 
    private $idUser;
    private $idPerson;
    private $login;
    private $password;
    private $active;

    /**
     * Get the value of idUser
     */ 
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */ 
    public function setIdUser($idUser): UserModel
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get the value of idPerson
     */ 
    public function getIdPerson(): int
    {
        return $this->idPerson;
    }

    /**
     * Set the value of idPerson
     *
     * @return  self
     */ 
    public function setIdPerson($idPerson): UserModel
    {
        $this->idPerson = $idPerson;

        return $this;
    }

    /**
     * Get the value of login
     */ 
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Set the value of login
     *
     * @return  self
     */ 
    public function setLogin($login): UserModel
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password): UserModel
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of active
     */ 
    public function getActive(): string
    {
        return $this->active;
    }

    /**
     * Set the value of active
     *
     * @return  self
     */ 
    public function setActive($active): UserModel
    {
        $this->active = $active;

        return $this;
    }
}
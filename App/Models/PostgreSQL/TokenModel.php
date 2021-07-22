<?php

namespace App\Models\PostgreSQL;

final class TokenModel
{ 
    private $idToken;
    private $idPerson;
    private $token;
    private $refreshToken;
    private $dateExpire;


    /**
     * Get the value of idToken
     */ 
    public function getIdToken(): int
    {
        return $this->idToken;
    }

    /**
     * Set the value of idToken
     *
     * @return  self
     */ 
    public function setIdToken($idToken): TokenModel
    {
        $this->idToken = $idToken;

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
    public function setIdPerson($idPerson): TokenModel
    {
        $this->idPerson = $idPerson;

        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token): TokenModel
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of refreshToken
     */ 
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Set the value of refreshToken
     *
     * @return  self
     */ 
    public function setRefreshToken($refreshToken): TokenModel
    {
        $this->refreshToken = $refreshToken;

        return $this;
    } 

    /**
     * Get the value of dateExpire
     */ 
    public function getDateExpire(): string
    {
        return $this->dateExpire;
    }

    /**
     * Set the value of dateExpire
     *
     * @return  self
     */ 
    public function setDateExpire($dateExpire): TokenModel
    {
        $this->dateExpire = $dateExpire;

        return $this;
    }
}
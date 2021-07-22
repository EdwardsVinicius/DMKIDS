<?php

namespace App\Models\PostgreSQL;

final class NomeExemploClassModel
{ 
    private $nomeVariavel;

    /**
     * Get the value of nomeVariavel
     */ 
    public function getNomeVariavel()
    {
        return $this->nomeVariavel;
    }

    /**
     * Set the value of nomeVariavel
     *
     * @return  self
     */ 
    public function setNomeVariavel($nomeVariavel)
    {
        $this->nomeVariavel = $nomeVariavel;

        return $this;
    }
}
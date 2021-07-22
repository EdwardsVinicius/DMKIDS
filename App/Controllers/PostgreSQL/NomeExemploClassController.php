<?php 

namespace App\Controllers\PostgreSQL;

use App\DAO\PostgreSQL\NomeExemploClassDAO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NomeExemploClassController
{
    public function nomeMetodoDaClass(Request $request, Response $response, array $args): Response
    {
        $nomeInstanciaClass = new NomeExemploClassDAO();

        $result = $nomeInstanciaClass->nomeMetodoDaClass();

        $response = $response
            ->withjson($result);

        return $response;
    }

}
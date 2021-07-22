<?php 

namespace App\Controllers\PostgreSQL;

use App\DAO\PostgreSQL\TokenDAO;
use App\DAO\PostgreSQL\UserDAO;
use App\Models\PostgreSQL\TokenModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Firebase\JWT\JWT;

final class AuthenticateController
{
    public function login(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $login = $data['login'];
        $password = md5($data['password']);

        $userDAO = new UserDAO();
        $user = $userDAO->userLogin($login);

        if(is_null($user)){
            $result = [
                'message' => [
                    'pt' => 'Usuario Invalido.',
                    'en' => 'Invalid User.'
                ],
                'result' => null
            ]; 
            return $response->withJson($result)
                            ->withStatus(401);
        }elseif($password <> $user->getPassword()){
            $result = [
                'message' => [
                    'pt' => 'Senha Invalida.',
                    'en' => 'Invalid password.'
                ],
                'result' => null
            ]; 
            return $response->withJson($result)
                            ->withStatus(401);
        }

        $dateExpire = (new \DateTime())->modify('+5 hour')->format('Y-m-d H:i:s');

        $tokenCarrega = [
            'sub' => $user->getIdPerson(),
            'login' => $user->getLogin(),
            'dateExpire' => $dateExpire
        ];

        $token = JWT::encode($tokenCarrega,getenv('JWT_SECRET_KEY'));

        $refreshToken = [
            'login' => $user->getLogin()
        ];

        $refreshToken = JWT::encode($refreshToken, getenv('JWT_SECRET_KEY'));

        $tokenModel = new TokenModel();
        $tokenModel
            ->setToken($token)
            ->setRefreshToken($refreshToken)
            ->setDateExpire($dateExpire)
            ->setIdPerson($user->getIdPerson());

        $tokenDAO = new TokenDAO();
        $tokenDAO->createToken($tokenModel);

        $tokenResult = [
            "token" => $token,
            "refreshToken" => $refreshToken,
        ];
        
        $result = [
            'message' => [
                'pt' => 'Autenticação realizada com sucesso.',
                'en' => 'Authentication was successful.'
            ],
            'result' => $tokenResult
        ]; 
        $response = $response->withJson($result);

        return $response;
    }

}
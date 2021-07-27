<?php 

namespace App\Controllers\PostgreSQL;

use App\DAO\PostgreSQL\Connection;

use App\DAO\PostgreSQL\UserDAO;
use App\DAO\PostgreSQL\PersonDAO;

use App\Models\PostgreSQL\PersonModel;

use App\Models\PostgreSQL\UserModel;
use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController extends Connection
{

    public function registerUser(Request $request, Response $response, array $args): Response
    {
        // $data = $request->getQueryParams();
        $data = $request->getParsedBody();

        $userDAO = new UserDAO($this->pdo);
        $user = new UserModel();

        $personDAO = new PersonDAO($this->pdo);
        $personModel = new PersonModel();

        if(strlen($data['password']) < 8){
            $result = [
                'message' => [
                    'pt' => 'Senha abaixo de 8 caracteres.',
                    'en' => 'Password below 8 characters.'
                ],
                'result' => null
            ]; 
            $response = $response->withjson($result);

            return $response->withStatus(401);
        }

        if(strlen($data['login']) == 0 || !filter_var($data['login'], FILTER_VALIDATE_EMAIL)){
            $result = [
                'message' => [
                    'pt' => 'Login invalido.',
                    'en' => 'Invalid login.'
                ],
                'result' => null
            ]; 
            $response = $response->withjson($result);

            return $response->withStatus(401);
        }

        $this->pdo->beginTransaction();
    
        if($data){
            $personModel
                ->setName($data['nome'])
                ->setAge($data['idade'])
                ->setGender($data['sexo'])
                ->setBirth($data['dataNascimento'])
                ->setCity($data['cidade'])
                ->setEstate($data['estado'])
                ->setTimeDiagnosis($data['tempoDiagnostico']);
                 

            // Try to register person
            $idPerson = $personDAO->registerPerson($personModel);

            if($idPerson){
                $user
                ->setIdPerson($idPerson)
                ->setLogin((string)$data['login'])
                ->setPassword(md5($data['password']));
            }else{
                $result = [
                    'message' => [
                        'pt' => 'Erro ao cadastrar usuário.',
                        'en' => 'Error registering user.'
                    ],
                    'result' => null
                ]; 
                $response = $response->withjson($result)->withStatus(406);
                $this->pdo->rollBack();
            }
    
            // Try to register user
            $idUser = $userDAO->registerUser($user);
    
            if($idUser){
                $result = [
                    'message' => [
                        'pt' => 'Usuário cadastrado com sucesso.',
                        'en' => 'User successfully registered.'
                    ],
                    'result' => null
                ]; 
                $response = $response
                    ->withjson($result)
                    ->withStatus(201);
                $this->pdo->commit();

            }else {
                $result = [
                    'message' => [
                        'pt' => 'Erro ao cadastrar usuário.',
                        'en' => 'Error registering user.'
                    ],
                    'result' => null
                ]; 
                $response = $response->withjson($result)->withStatus(406);
                $this->pdo->rollBack();
            }
        }else{
            $result = [
                'message' => [
                    'pt' => 'Parametros não aceitaveis.',
                    'en' => 'Parameters are not acceptable.'
                ],
                'result' => null
            ]; 
            $response = $response->withjson($result)->withStatus(406);
            $this->pdo->rollBack();
        }
    
        return $response;
    }

    public function listUsers(Request $request, Response $response, array $args): Response
    {
        $user = new UserDAO();
        $person = new PersonDAO();

        $data = $user->listUsers();

        // foreach($data as &$dataUser){
        //     $_person = $person->getPersonById($dataUser['idpessoa']);
            
        //     $dataUser['pessoa'] = $_person;
        // }

        $result = [
            'message' => [
                'pt' => null,
                'en' => null
            ],
            'result' => $data
        ];

        $response = $response
            ->withjson($result);

        return $response;
    }

    public function getUser(Request $request, Response $response, array $args): Response
    {
        $user = new UserDAO();
        $person = new PersonDAO();

        $data = $user->listUsers();

        foreach($data as &$dataUser){
            $_person = $person->getPersonById($dataUser['idpessoa']);
            
            $dataUser['pessoa'] = $_person;
        }

        $result = [
            'message' => [
                'pt' => null,
                'en' => null
            ],
            'result' => $data
        ];

        $response = $response
            ->withjson($result);

        return $response;
    }

    public function updateUserData(Request $request, Response $response, array $args): Response
    {

        return $response;
    }

    public function updatePassword(Request $request, Response $response, array $args): Response
    {   
        $data = $request->getParsedBody();
        $query = $request->getQueryParams();

        $userDAO = new UserDAO();
        $user = new UserModel();

        if(strlen($data['password']) < 8){
            
            $result = [
                'message' => [
                    'pt' => 'Senha com menos de 8 caracteres',
                    'en' => 'Password less than 8 characters'
                ],
                'result' => null
            ];

            return $response
                ->withJson($result)
                ->withStatus(401);
        }

        $token = $query['token'];
        $tokenDecoded = JWT::decode($token, getenv('JWT_SECRET_KEY'), array('HS256'));

        $expireDate = date_format(new \DateTime($tokenDecoded->dateExpire), 'Y-m-d H:i:s');
        $now = new \DateTime();
        $now = date_format($now, 'Y-m-d H:i:s');
        
        if($expireDate > $now && $tokenDecoded->login == $data['email']){
            $user
                ->setLogin($data['email'])
                ->setPassword(md5($data['password']));

            $userDAO->updatePassword($user);
           
            $result = [
                'message' => [
                    'pt' => 'Senha alterada com sucesso.',
                    'en' => 'Password changed successfully.'
                ],
                'result' => null
            ];            

            $response = $response->withjson($result);

        }else {

            $result = [
                'message' => [
                    'pt' => 'Token inválido',
                    'en' => 'Invalid token.'
                ],
                'result' => null
            ]; 
            $response = $response->withjson($result);
        }

        return $response;
    }

    public function queryUserRest(Request $request, Response $response, array $args): Response
    {
        $queryParams = $request->getQueryParams();

        $userDAO = new UserDAO();
        $email = $queryParams['email'];
        @$method = $queryParams['method'];

        if($method == ''){
            $dataUser = $userDAO->queryUserRest($email);

            if($dataUser){
                $response = $response
                    ->withJson($dataUser[0]);
            }else{
                $result = [
                    'message' => [
                        'pt' => 'Usuário não cadastrado no sistema.',
                        'en' => 'User not registered in the system.'
                    ],
                    'result' => null
                ];
                $response = $response->withjson($result);
            }

        }elseif($method == 'reset'){

            $checks = $userDAO->queryUserRest($email);
            $emailRecipient = $checks[0]['email'];
            $dataUser = $userDAO->queryUserRest($email);
            $name = $dataUser[0]['name'];
            
            if($emailRecipient){

                $emailOrigen = 'vinicius.edwards@gmail.com';
                $password = 'mojrttksepcgruxk';
                $nomeProjeto = 'Projeto DMKIDS';

                $dateExpire = (new \DateTime())->modify('+5 hour')->format('Y-m-d H:i:s');

                $tokenCarrega = [
                    'sub' => $emailRecipient,
                    'login' => $emailRecipient,
                    'dateExpire' => $dateExpire
                ];

                $token = JWT::encode($tokenCarrega,getenv('JWT_SECRET_KEY'));

                $mailer = new PHPMailer();
                $mailer->IsSMTP();
                $mailer->CharSet = 'UTF-8';
                $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
                
                $mailer->Host = 'smtp.gmail.com'; //google
                $mailer->SMTPSecure = 'tls';
                $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
                $mailer->Username = $emailOrigen; //Informe o e-mai o completo
                $mailer->Password = $password; //Senha da caixa postal
                $mailer->FromName = $nomeProjeto; //Nome que será exibido para o destinatário
                $mailer->From = $emailOrigen; //Obrigatório ser a mesma caixa postal indicada em "username"
                $mailer->AddAddress($emailRecipient); //Destinatários
                $mailer->Subject = 'Recuperação de senha '.$nomeProjeto.' - '.date("d/m/Y");
                $mailer->Body = '
                                <h2>Projeto DMKIDS</h2>
                                
                                Para validar seu acesso e alterar sua senha clique no link a seguir: <br>
                                <a href="http://localhost/dmkids/index.php/user-password/?token='.$token.'&name='.$name.'">http://localhost/dmkids/index.php/login?token='.$token.'&name='.$name.'</a><br><br>
                                
                                
                    ';
                $mailer->isHTML(true);
                if($mailer->Send())
                {
                    $result = [
                        'message' => [
                            'pt' => 'Email encaminhado com sucesso.',
                            'en' => 'Email forwarded successfully.'
                        ],
                        'result' => [
                            "email" => $emailRecipient
                        ]
                    ];
                    $response = $response->withJson($result);
                }else{
                    $result = [
                        'message' => [
                            'pt' => 'Favor entrar em contato com o administrador do sistema.',
                            'en' => 'Please contact your system administrator.'
                        ],
                        'result' => [
                            "email" => $emailRecipient
                        ]
                    ];
                    $response = $response
                                    ->withjson($result)
                                    ->withStatus(401);
                }

            }else{
                $result = [
                    'message' => [
                        'pt' => 'Usuário não cadastrado no sistema.',
                        'en' => 'User not registered in the system.'
                    ],
                    'result' => null
                ];
                $response = $response->withjson($result);
            }


        }
        return $response;
    }
}
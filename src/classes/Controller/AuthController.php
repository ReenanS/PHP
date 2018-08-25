<?php
namespace Controller;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

//Gera um token a cada refresh da pagina

class AuthController extends Controller
{

    public function genJWT($request, $response, $args) //por enquanto tá pelo metodo get
    {
      //cria JWT token
      $secretKey = base64_decode(getenv('JWT_SECRET'));
      $date = date_create();
      $jwtIAT = date_timestamp_get($date);
      $jwtExp = $jwtIAT + (20 * 60); //expira depois de 20 minutos

      $jwtToken = array(
         "iss" => "ebloom.com.br", //chave do cliente
         "iat" => $jwtIAT, //emitido no tempo
         "exp" => $jwtExp, //expira
      );
      $token = JWT::encode($jwtToken, $secretKey, 'HS256'); //Data que vai ser "encoded" no JWT //Chave de assinatura //Algoritmo usado para validar o token

      $data = array('token' => $token); //Retorna um array 
      return $response->withJson($data, 200)
                      ->withHeader('Content-type', 'application/json');
    }

}
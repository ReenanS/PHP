<?php

namespace classes\Controllers\Auth;

use App\Auth\JwtAuth;
use \Controller\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LoginController extends Controller
{
    protected $auth;

    public function __construct(JwtAuth $auth)
    {
        $this->auth = $auth;
    }

    public function index(Request $request, Response $response)
    {
        if (!$token = $this->auth->attempt($request->getParam('email'), $request->getParam('password'))) {
            return $response->withStatus(401);
        }

        return $response->withJson([
            'token' => $token
        ]);
    }
}

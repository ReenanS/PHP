<?php
namespace Controller;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class NotificacaoController extends Controller
{
    public function getAllMsg($request, $response, $args) 
    {
        // TODO
        // Retorna todas as notificacoes do usuario
        return $response;
    }

    public function getMsg($request, $response, $args) 
    {
        // TODO
        // Retorna todos os detalhes da notificacao em especifico
        return $response;
    }

    public function addMsg($request, $response, $args) 
    {
        // TODO
        // Cria uma nova mensagem no banco de dados e
        // cria um notificacao para todos os usuários apropriados
        return $response;
    }

    public function editMsg($request, $response, $args) 
    {
        // TODO
        // Altera as infos da mensagem ou notificacao
        return $response;
    }

    public function delMsg($request, $response, $args) 
    {
        // TODO
        // Delete a notificacao do usuario, mas não deleta a mensagem
        return $response;
    }
}
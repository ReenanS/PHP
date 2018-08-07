<?php
namespace Service;

use \DBO\Users\UserDBO as User;

class Security
{
    private $db;
    private $user;
    private $controller;

    public function __construct($db, $id = null)
    {
        $this->user = new User($db);
    }

    public function userLogged($uid)
    {
        // TODO
        return true;
    }

    public function validarUser($uid)
    {
        return ($this->user->getUID() == $uid);
    }
    
    // Forma generica de dar o controle de acesso diretamente para o objeto q vai ser acessado
    // Parametros:
    //   itemId = id do item que desejar acessar
    //   itemType = tipo do item q deseja acessar
    //   method = metodo de acesso a bd
    public function userAccess($itemId, $itemType, $method)
    {
        $dbo = $this->controller->{$itemType}();
        return $dbo->allowAccess($this->user->getId(), $this->user->getTipo(), $itemId, $method);
    }

    // Recursos especificos
    public function adminOnly($userId)
    {
        return ($this->user->getTipo() == 'admin');
    }

    public function professorOnly($id)
    {
        return ($this->user->getTipo() == 'professor');
    }

    public function alunoOnly($id)
    {
        return ($this->user->getTipo() == 'aluno');
    }

}
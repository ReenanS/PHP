<?php
namespace DBO\Users;

use \DBO\DBO;

class UserDBO extends DBO
{
    private $criado;
    public $tipo;
    public $email;
    public $pwd;

    public function __construct($db)
    {

        parent::__construct($db);
        $this->setTableName("user");
        $this->setType("user");
        $this->setRelations(["aluno", "professor"]);
        $this->userTipo = array(
            0 => 'aluno',
            1 => 'professor',
            2 => 'admin'
        );
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

    // getter and setter
    public function getTipo()
    {
        return $this->tipoId2Str($this->tipo);
    }

    public function setTipoByKey($tipo)
    {
        $this->tipo = $this->tipoStr2Id($tipo);
    }

    public function setTipoById($tipo)
    {
        $this->tipo = $tipo;
    }

    public function tipoId2Str($tipo)
    {
        return $this->userTipo[$tipo];
    }

    private function tipoStr2Id($tipo)
    {
        return array_search($tipo, $this->userTipo);
    }

    public function getUID()
    {
        return $this->uid;
    }
    
    public function setUID($uid)
    {
        $this->uid = $uid;
    }
}
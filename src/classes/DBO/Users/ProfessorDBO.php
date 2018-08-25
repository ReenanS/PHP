<?php
namespace DBO\Users;

use \DBO\DBO;
use \DBO\Users\UserDBO as User;

class ProfessorDBO extends DBO
{
    private $criado;
    private $modificado;

    public $professor;
    private $professorid;

    private $uid;
    public $user;

    public $nome;
    public $sobrenome;

    public function __construct($db)
    {
     //   $professor = $professorid;
     //   $user = $uid;

        parent::__construct($db);
        $this->setTableName("professor");
        $this->setType("professor");
        $this->setFK(["user"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna professor q é uma FK para essa tbl
        $this->getRelations(["curso", "leciona"]);
    }

    // getter and setter
    public function getProfessorID()
    {
        return $this->professorid;
    }
    public function setProfessorID($professorid)
    {
        $this->professorid = $professorid;
    }

    public function getUID()
    {
        return $this->uid;
    }
    public function setUID($uid)
    {
        $this->uid = $uid;
    }

    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getSobrenome()
    {
        return $this->sobrenome;
    }
    public function setSobrenome($sobrenome)
    {
        $this->sobrenome = $sobrenome;
    }


}
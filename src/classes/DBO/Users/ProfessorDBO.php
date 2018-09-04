<?php
namespace DBO\Users;

use \DBO\DBO;

class ProfessorDBO extends DBO
{
    private $criado;
    private $modificado;

    // public $professor;
    public $user;
    public $nome;
    public $sobrenome;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("professor");
        $this->setType("professor");
        $this->setFK(["user"]);
        $this->setRelations(["curso", "leciona"]);
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

}

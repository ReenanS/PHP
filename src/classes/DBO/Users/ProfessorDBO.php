<?php
namespace DBO\Users;

use \DBO\DBO;
use \DBO\Users\UserDBO as User;

class ProfessorDBO extends DBO
{
    private $criado;
    private $modificado;

    public $professor;
    public $user;
    public $nome;
    public $sobrenome;

    public function __construct($db)
    {

        parent::__construct($db);
        $this->setTableName("professor");
        $this->setType("professor");
        $this->setFK(["user"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna professor q é uma FK para essa tbl
        $this->setRelations(["curso", "leciona"]);
    }

}

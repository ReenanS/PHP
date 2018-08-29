<?php
namespace DBO\Business;

use \DBO\DBO;

class ProfessorDBO extends DBO
{
    private $criado;
    private $modificado;

    public $disciplina;

    public $tipo;
    public $numero;
    public $peso;

    public function __construct($db)
    {

        parent::__construct($db);
        $this->setTableName("detalhe");
        $this->setType("detalhe");
        $this->setFK(["disciplina"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna professor q é uma FK para essa tbl
        $this->setRelations(["nota"]);
    }

}

<?php
namespace DBO\Business;
use \DBO\DBO;

class CursoDBO extends DBO
{
    private $criado;
    private $modificado;
    
    public $professor;

    public $nome;
    public $periodo;
    public $qualidade;
    
    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("curso");
        $this->setType("curso");
        $this->setFK(["professor"]);
        $this->setRelations(["disciplina"]);
    }

}

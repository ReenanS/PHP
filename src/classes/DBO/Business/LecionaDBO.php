<?php
namespace DBO\Business;
use \DBO\DBO;

class LecionaDBO extends DBO
{
    private $criado;
    private $modificado;

    public $professor;
    public $disciplina;

    public $notificado;
    public $status;
    
    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("leciona");
        $this->setType("leciona");
        $this->setFK(["professor","disciplina"]);
        // $this->setRelations(["disciplina"]);
    }

}

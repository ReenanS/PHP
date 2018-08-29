<?php
namespace DBO\Users;

use \DBO\DBO;

class MensagemDBO extends DBO
{
    private $criado;
    private $modificado;

    public $titulo;
    public $descricao;
    public $importancia;

    public function __construct($db)
    {

        parent::__construct($db);
        $this->setTableName("mensagem");
        $this->setType("mensagem");
        // $this->setFK(["user"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna professor q é uma FK para essa tbl
        $this->setRelations(["notificacao"]);
    }

}

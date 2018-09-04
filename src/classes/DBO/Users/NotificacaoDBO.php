<?php
namespace DBO\Users;

use \DBO\DBO;

class NotificacaoDBO extends DBO
{
    private $criado;
    private $modificado;

    public $user;
    public $lida;

    public function __construct($db)
    {

        parent::__construct($db);
        $this->setTableName("notificacao");
        $this->setType("notificacao");
        $this->setFK(["user"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna notificacao q é uma FK para essa tbl
        $this->setRelations(["mensagem"]);
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

}

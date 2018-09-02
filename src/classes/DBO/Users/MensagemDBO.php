<?php
namespace DBO\Users;

use \DBO\DBO;

class MensagemDBO extends DBO
{
    // variaveis private podem ser lidas ou não,
    // mas nunca seria exportadas
    // visiveis apenas pela classe DBO
    private $criado;
    private $modificado;

    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    public $titulo;
    public $descricao;
    public $importancia;

    public function __construct($db)
    {

        // chama o contrutor do pai (hierarquia - extends)
        parent::__construct($db);

        // set o nome da tabela no BD
        $this->setTableName("mensagem");

        // set o tipo da classe para ser exportado no JSON API
        // pode ser um nome diferente da tbl para aumentar a seguranca
        // mas nesse caso o controller nao poderia implementar as funcoes de leitura de forma generica atual
        // obs: talvez seja interessante revisar isso
        $this->setType("mensagem");

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna notificacao q é uma FK para essa tbl
        $this->setRelations(["notificacao"]);
    }

}

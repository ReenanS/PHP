<?php
namespace DBO\Business;

use \DBO\DBO;

class DetalheDBO extends DBO
{
    // variaveis private podem ser lidas ou não,
    // mas nunca seria exportadas
    // visiveis apenas pela classe DBO
    private $criado;
    private $modificado;

    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    public $disciplina;
    public $tipo;
    public $numero;
    public $peso;

    public function __construct($db)
    {
        // chama o contrutor do pai (hierarquia - extends)
        parent::__construct($db);
        
        // set o nome da tabela no BD
        $this->setTableName("detalhe");

        // set o tipo da classe para ser exportado no JSON API
        // pode ser um nome diferente da tbl para aumentar a seguranca
        // mas nesse caso o controller nao poderia implementar as funcoes de leitura de forma generica atual
        // obs: talvez seja interessante revisar isso
        $this->setType("detalhe");

        // set as colunas da tbl q sao chaves estrangeiras (FK)
        // para isso o nome da tabela tem q ser igual o nome da coluna (sql naming convention)
        $this->setFK(["disciplina"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna detalhe q é uma FK para essa tbl
        $this->setRelations(["nota"]);
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

}

<?php
namespace DBO;

use \DBO\DBO;

// Exemplo de uma classe real
// Nota é uma tabela do BD
class NotaDBO extends DBO
{
    
    // variaveis private podem ser lidas ou não,
    // mas nunca seria exportadas
    // visiveis apenas pela classe DBO
    private $criado;
    private $modificado;
    private $lancado;

    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    public $nota;
    public $valor;
    public $matricula;
    public $aluno;
    public $disciplina;
    public $detalhe;

    public function __construct($db)
    {
        // chama o contrutor do pai (hierarquia - extends)
        parent::__construct($db);

        // set o nome da tabela no BD
        $this->setTableName("nota");

        // set o tipo da classe para ser exportado no JSON API
        // pode ser um nome diferente da tbl para aumentar a seguranca
        // mas nesse caso o controller nao poderia implementar as funcoes de leitura de forma generica atual
        // obs: talvez seja interessante revisar isso
        $this->setType("nota");

        // set as colunas da tbl q sao chaves estrangeiras (FK)
        // para isso o nome da tabela tem q ser igual o nome da coluna (sql naming convention)
        $this->setFK(["matricula", "aluno", "disciplina", "detalhe"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna nota q é uma FK para essa tbl
        $this->getRelations([""]);
    }


}
<?php
namespace DBO\Users;

use \DBO\DBO;

// Exemplo de uma classe real
// Aluno é uma tabela do BD
class AlunoDBO extends DBO
{
    
    // variaveis private podem ser lidas ou não,
    // mas nunca seria exportadas
    // visiveis apenas pela classe DBO
    private $criado;
    private $modificado;

    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    public $alunoid;
    public $uid;
    public $nome;
    public $sobrenome;
    public $ra;
    public $curso;
    public $ano_matricula;

    public function __construct($db)
    {
        // chama o contrutor do pai (hierarquia - extends)
        parent::__construct($db);

        // set o nome da tabela no BD
        $this->setTableName("aluno");

        // set o tipo da classe para ser exportado no JSON API
        // pode ser um nome diferente da tbl para aumentar a seguranca
        // mas nesse caso o controller nao poderia implementar as funcoes de leitura de forma generica atual
        // obs: talvez seja interessante revisar isso
        $this->setType("aluno");

        // set as colunas da tbl q sao chaves estrangeiras (FK)
        // para isso o nome da tabela tem q ser igual o nome da coluna (sql naming convention)
        $this->setFK(["user"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna professor q é uma FK para essa tbl
        $this->getRelations(["user", "matricula", "aprendizado"]);
    }

        // getter and setter
        public function getAlunoID()
        {
            return $this->alunoid;
        }
        public function setAlunoID($alunoid)
        {
            $this->alunoid = $alunoid;
        }
    
        public function getUID()
        {
            return $this->uid;
        }
        public function setUID($uid)
        {
            $this->uid = $uid;
        }
    
        public function getNome()
        {
            return $this->nome;
        }
        public function setNome($nome)
        {
            $this->nome = $nome;
        }
    
        public function getSobrenome()
        {
            return $this->sobrenome;
        }
        public function setSobrenome($sobrenome)
        {
            $this->sobrenome = $sobrenome;
        }

        public function getRA()
        {
            return $this->ra;
        }
        public function setRA($ra)
        {
            $this->ra = $ra;
        }
    
        public function getCurso()
        {
            return $this->curso;
        }
        public function setCurso($curso)
        {
            $this->curso = $curso;
        }

        public function getAnoMatricula()
        {
            return $this->ano_matricula;
        }
        public function setAnoMatricula($ano_matricula)
        {
            $this->ano_matricula = $ano_matricula;
        }


}
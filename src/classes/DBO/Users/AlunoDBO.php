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
    public $user;
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
        $this->setRelations(["matricula", "nota"]);
    }


    public function matricular($aid, $did)
    {
        $sql = "INSERT INTO matricula(aluno,disciplina) VALUES " .
            " (" . $aid . ',' . $did . ');';
        $stmt = $this->db->exec($sql);
        return $this->readId();
    }

    public function desmatricular($aid, $did)
    {
        $sql = "DELETE FROM matricula " .
            "WHERE aluno = '" . $aid . "' " .
            "AND disciplina = '" . $did . "';";
        $stmt = $this->db->exec($sql);
        return $this->readId();
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

    public function readAlunoMatriculados($disciplina)
    {
        $sql = "SELECT matricula, " . $this->table_name . ', ' . $this->getKeys() .
            " FROM matricula " .
            " LEFT JOIN " . $this->table_name . " USING (" . $this->table_name . ") " .
            " WHERE disciplina = '" . $disciplina . "';";
        $stmt = $this->db->query($sql);
        $response = array();
        while ($row = $stmt->fetch()) {
            $object = $this->instantiateSelf();
            $object->set($row);
            $object->setId($row[$this->table_name]);
            array_push($response, $object);
        }
        return $response;
    }

}

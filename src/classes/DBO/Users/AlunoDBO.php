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
        parent::__construct($db);
        $this->setTableName("aluno");
        $this->setType("aluno");
        $this->setFK(["user"]);
        $this->setRelations(["matricula", "nota"]);
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

    public function readAllByFK($k, $v)
    {
        if ($k == "disciplina") {
            $sql = "SELECT " . $this->table_name . ', ' . $this->getKeys() .
            " FROM " . $this->table_name .
            " LEFT JOIN matricula USING (" . $this->table_name . ")" .
            " WHERE " . $k . " = '" . $v . "'".
            " ORDER BY " . $this->table_name . " ASC;";
        } else {
            $sql = "SELECT " . $this->table_name . ', ' . $this->getKeys() .
            " FROM " . $this->table_name .
            " WHERE " . $k . " = '" . $v . "';";
        }
        var_export($sql);
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

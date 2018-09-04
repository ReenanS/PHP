<?php
namespace DBO\Business;

use \DBO\DBO;

// Exemplo de uma classe real
// Disciplina é uma tabela do BD
class DisciplinaDBO extends DBO
{
    
    // variaveis private podem ser lidas ou não,
    // mas nunca seria exportadas
    // visiveis apenas pela classe DBO
    private $criado;
    private $modificado;

    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    public $nome;
    public $moodle;
    public $periodo;
    public $qtdP;
    public $qtdT;
    public $kP;
    public $kT;
    public $ementa;
    public $curso;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("disciplina");
        $this->setType("disciplina");
        $this->setFK(["curso"]);
        $this->setRelations(["leciona", "matricula", "nota", "detalhe"]);
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

    public function readAllByFK($k, $v)
    {
        if ($k == "professor") {
            $sql = "SELECT " . $this->table_name . ', ' . $this->getKeys() .
            " FROM " . $this->table_name .
            " LEFT JOIN leciona USING (" . $this->table_name . ")" .
            " WHERE " . $k . " = '" . $v . "'".
            " ORDER BY " . $this->table_name . " ASC;";
        } else if ($k == "aluno") {
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
        // var_export($sql);
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
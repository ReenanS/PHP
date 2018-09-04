<?php
namespace DBO\Business;

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
    
    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    public $valor;
    public $lancado;
    public $matricula;
    public $aluno;
    public $disciplina;
    public $detalhe;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("nota");
        $this->setType("nota");
        $this->setFK(["matricula","aluno","disciplina","detalhe"]);
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

    public function readAllByFK($k, $v)
    {
        if (!is_string($k)) {
            $sql = "SELECT " . $this->table_name . ', ' . $this->getKeys() .
            " FROM " . $this->table_name .
            " WHERE " . $k[0] . " = '" . $v[0] . "'".
            " AND " . $k[1] . " = '" . $v[1] . "'".
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
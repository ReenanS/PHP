<?php
namespace DBO\Business;
use \DBO\DBO;

class MatriculaDBO extends DBO
{
    private $criado;
    private $modificado;

    public $aluno;
    public $disciplina;
    
    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("matricula");
        $this->setType("matricula");
        $this->setFK(["aluno","disciplina"]);
        $this->setRelations(["nota"]);
    }


    // public function matricular($aid,$did) {
    //     $sql =  "INSERT INTO matricula(aluno,disciplina) VALUES " .
    //             " (" . $aid .','. $did .');';
    //     var_export($sql);
    //     $stmt = $this->db->exec($sql);
    //     return $this->readId();
    // }

    // public function desmatricular($aid,$did) {
    //     $sql =  "DELETE FROM matricula " .
    //             "WHERE aluno = '" . $aid."' ".
    //             "AND disciplina = '" . $did ."';";
    //     var_export($sql);
    //     $stmt = $this->db->exec($sql);
    //     return $this->readId();
    // }

    // public function instantiateSelf()
    // {
    //     return new self($this->db);
    // }

    // public function readAlunoMatriculados($disciplina)
    // {
    //     $sql =  "SELECT matricula, " . $this->table_name . ', ' . $this->getKeys() .
    //             " FROM matricula " .
    //             " LEFT JOIN ". $this->table_name . " USING (". $this->table_name . ") ".
    //             " WHERE disciplina = '" . $disciplina . "';";
    //     var_export($sql);
    //     $stmt = $this->db->query($sql);
    //     $response = array();
    //     while ($row = $stmt->fetch()) {
    //         $object = $this->instantiateSelf();
    //         $object->set($row);
    //         $object->setId($row[$this->table_name]);
    //         array_push($response, $object);
    //     }
    //     return $response;
    // }


}

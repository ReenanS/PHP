<?php
namespace DBO\Business;
use \DBO\DBO;

class LecionaDBO extends DBO
{
    private $criado;
    private $modificado;

    public $professor;
    public $disciplina;

    public $notificado;
    public $status;
    
    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("leciona");
        $this->setType("leciona");
        $this->setFK(["professor","disciplina"]);
        // $this->setRelations(["disciplina"]);
    }

    public function instantiateSelf()
    {
        return new self($this->db);
    }

    public function readByFK($k = null, $v = null)
    {
        if ($k != null) {
            return parent::readByFK($k,$v);
        } else {
            $sql = "SELECT " . $this->table_name . ',' . $this->getKeys() .
                " FROM " . $this->table_name .
                " WHERE professor = '" . $this->professor . "'".
                " AND disciplina = '" . $this->disciplina .  "';";
            var_export($sql);
            $stmt = $this->db->query($sql);
            if ($row = $stmt->fetch()) {
                $this->setId($row[$this->table_name]);
                $this->set($row);
            }
            return $this->get();
        }
    }

    // public function readAllByFK($k, $v)
    // {
    //     $sql = "SELECT " . $this->table_name . ', ' . $this->getKeys() .
    //         " FROM " . $this->table_name .
    //         " WHERE " . $k . " = '" . $v . "';";
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

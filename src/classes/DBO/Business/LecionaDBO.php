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

    public function readByFK($k = null, $v = null)
    {
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

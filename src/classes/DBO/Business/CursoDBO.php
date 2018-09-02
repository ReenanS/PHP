<?php
namespace DBO\Business;

use \DBO\DBO;

class CursoDBO extends DBO
{
    private $criado;
    private $modificado;

    public $curso;
    public $nome;
    public $periodo;
    public $qualidade;
    public $professor;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->setTableName("curso");
        $this->setType("curso");
        $this->setFK(["professor"]);
        $this->setRelations(["disciplina"]);
    }

    public function validarCurso($curso)
    {
        $sql = "SELECT " . $this->table_name . ', ' . $this->getKeys() .
            " FROM curso " .
            " LEFT JOIN " . $this->table_name . " USING (" . $this->table_name . ") " .
            " WHERE professor = '" . $professor . "' " .
            " AND curso = '" . $this->curso . "';";
        var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            if (isset($row['leciona'])) return true;
        }
        return false;
    }


}

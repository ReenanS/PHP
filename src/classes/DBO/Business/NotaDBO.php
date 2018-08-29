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
    
    public $matricula;
    public $aluno;
    public $disciplina;
    public $detalhe;
    
    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    // public $nota;
    public $valor;
    public $lancado;

    public function __construct($db)
    {
        // chama o contrutor do pai (hierarquia - extends)
        parent::__construct($db);

        // set o nome da tabela no BD
        $this->setTableName("nota");
        $this->setType("nota");
        $this->setFK(["matricula","aluno","disciplina","detalhe"]);
        // $this->setRelations([""]);
    }

    public function getInfo() {
        $cols = $this->get();
        $colsDetalhe = $this->readDetalhe();
        foreach($colsDetalhe as $k => $v) {
            $cols[$k] = $v;
        }
        // var_export($cols);
        return $cols;
    }

    public function readDetalhe() {
        $sql =  "SELECT tipo, numero, peso " .
                " FROM detalhe" . 
                " WHERE detalhe = '" . $this->detalhe . "';";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $info = array(
                "tipo" => $row['tipo'],
                "numero" => $row['numero'],
                "peso" => $row['peso']
            );
        }
        return $info;
    }

    public function readNota($a, $d)
    {
        $sql = "SELECT " . $this->table_name . ',' . $this->getKeys() .
            " FROM " . $this->table_name .
            " WHERE aluno = '" . $a . "' ".
            " AND disciplina = '". $d ."';";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->setId($row[$this->table_name]);
            $this->set($row);
        }
        return $this->get();
    }


}
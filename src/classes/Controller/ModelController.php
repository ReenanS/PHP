<?php
namespace Controller;

// Users
use \DBO\Users\ProfessorDBO as Professor;
use \DBO\Users\AlunoDBO as Aluno;
use \DBO\Users\UserDBO as User;

// Grade Curricular
use \DBO\Materia\DisciplinaDBO as Disciplina;
use \DBO\Materia\NotaDBO as Nota;
use \DBO\Materia\MatriculaDBO as Matricula;
use \DBO\Materia\CursoDBO as Curso;
use \DBO\Materia\LecionaDBO as Leciona;


class ModelController
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Usar essa classe para declarar todos os DBOs
    // Desse jeito nao e necessario declarar em mais nenhum lugar do codigo
    // chamar as classes usando:
    // $modelController->{'professor'}() ou $modelController->professor()

    public function professor()
    {
        return new Professor($this->db);
    }

    public function aluno()
    {
        return new Aluno($this->db);
    }

    public function user()
    {
        return new User($this->db);
    }

    public function disciplina()
    {
        return new Disciplina($this->db);
    }
    
    public function nota()
    {
        return new Nota($this->db);
    }
    
    public function matricula()
    {
        return new Matricula($this->db);
    }

    public function leciona()
    {
        return new Leciona($this->db);
    }

    public function curso()
    {
        return new Curso($this->db);
    }
}
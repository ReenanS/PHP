<?php
namespace Controller;

// Users
use \DBO\Users\ProfessorDBO as Professor;
use \DBO\Users\AlunoDBO as Aluno;
use \DBO\Users\UserDBO as User;
use \DBO\DisciplinaDBO as Disciplina;
use \DBO\NotaDBO as Nota;


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




}
<?php
namespace Controller;

// Users
use \DBO\Users\AlunoDBO as Aluno;
use \DBO\Users\MensagemDBO as Mensagem;
use \DBO\Users\NotificacaoDBO as Notificacao;
use \DBO\Users\ProfessorDBO as Professor;
use \DBO\Users\UserDBO as User;

// Grade Curricular
use \DBO\Business\CursoDBO as Curso;
use \DBO\Business\DetalheDBO as Detalhe;
use \DBO\Business\DisciplinaDBO as Disciplina;
use \DBO\Business\LecionaDBO as Leciona;
use \DBO\Business\MatriculaDBO as Matricula;
use \DBO\Business\NotaDBO as Nota;


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

    // USERS
    public function user()
    {
        return new User($this->db);
    }

    public function aluno()
    {
        return new Aluno($this->db);
    }

    public function professor()
    {
        return new Professor($this->db);
    }

    public function mensagem()
    {
        return new Mensagem($this->db);
    }

    public function notificacao()
    {
        return new Notificacao($this->db);
    }

    // BUSINESS
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

    public function detalhe()
    {
        return new Detalhe($this->db);
    }
}
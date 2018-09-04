<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Controllers

// Users
use \Controller\Users\AdminController as AdminController;
use \Controller\Users\NotificacaoController as NotificacaoController;
use \Controller\Users\UserController as UserController;
use \Controller\Users\ProfessorController as ProfessorController;
use \Controller\Users\AlunoController as AlunoController;

// Business
use \Controller\Business\DisciplinaController as DisciplinaController;
use \Controller\Business\NotaController as NotaController;
use \Controller\Business\CursoController as CursoController;


// TODO: melhorar criacao do user + auth
// $app->group('/user', function () {
//     $this->get('/{uid}', UserController::class . ':auth');
//     $this->post('', UserController::class . ':create');
//     $this->put('/{uid}', UserController::class . ':edit');
//     $this->delete('/{uid}', UserController::class . ':del');
// });

// route generica para um user (prof ou aluno)
$app->group('/{tipo:professor|aluno}', function () {

    $this->get('', UserController::class . ':getAllUser');
    $this->get('/{uid}', UserController::class . ':getUser');
    $this->post('', UserController::class . ':addUser');
    $this->put('/{uid}', UserController::class . ':editUser');
    $this->delete('/{uid}', UserController::class . ':delUser');

    $this->group('/{uid}/notificacao', function () {
        // $this->get('', NotificacaoController::class . ':getAllMsg');
        //$this->get('/{nid}', NotificacaoController::class . ':getMsg');
        // $this->post('', NotificacaoController::class . ':addMsg');
        // $this->put('/{nid}', NotificacaoController::class . ':editMsg');
        // $this->delete('/{nid}', NotificacaoController::class . ':delMsg');
    });
});

// especifico do professor
$app->group('/professor/{pid}', function () {

    $this->get('/disciplina', ProfessorController::class . ':getAllLecionaDisciplina');
    $this->group('/disciplina/{did}', function () {
        $this->get('', ProfessorController::class . ':getLeciona');
        $this->post('', ProfessorController::class . ':addLeciona');
        $this->put('', ProfessorController::class . ':editLeciona');
        $this->delete('', ProfessorController::class . ':delLeciona');

        $this->group('/nota', function () {
            $this->get('', NotaController::class . ':getAllDetalheNota');
            $this->get('/{nid}', NotaController::class . ':getDetalheNota');

            $this->post('', NotaController::class . ':addDetalheNota');
            $this->put('/{nid}', NotaController::class . ':editDetalheNota');
            $this->delete('/{nid}', NotaController::class . ':delDetalheNota');
        });
    });

});

// especifico do aluno
$app->group('/aluno/{aid}', function () {

    $this->get('/disciplina', AlunoController::class . ':getAllMatriculaDisciplina');
    $this->group('/disciplina/{did}', function () {
        $this->get('', AlunoController::class . ':getMatricula');
        $this->post('', AlunoController::class . ':addMatricula');
        $this->delete('', AlunoController::class . ':delMatricula');

        $this->group('/nota', function () {
            $this->get('', NotaController::class . ':getAllNota');
            $this->get('/{nid}', NotaController::class . ':getNota');

            $this->post('', NotaController::class . ':addNota');
            $this->put('/{nid}', NotaController::class . ':editNota');
            $this->delete('/{nid}', NotaController::class . ':delNota');
        });
    });

});

// especifico da disciplina
$app->group('/disciplina', function () {
    $this->get('', DisciplinaController::class . ':getAllDisciplina');
    $this->post('', DisciplinaController::class . ':addDisciplina');

    $this->group('/{did}', function () {
        $this->get('', DisciplinaController::class . ':getDisciplina');
        $this->put('', DisciplinaController::class . ':editDisciplina');
        $this->delete('', DisciplinaController::class . ':delDisciplina');
        
        $this->group('/aluno', function() {
            $this->get('', AlunoController::class . ':getAllMatriculaAluno');

            $this->get('/{aid}', AlunoController::class . ':getMatricula');
            $this->post('/{aid}', AlunoController::class . ':addMatricula');
            $this->delete('/{aid}', AlunoController::class . ':delMatricula');

            $this->group('/{aid}/nota', function () {
                $this->get('', NotaController::class . ':getAllNota');
                $this->get('/{nid}', NotaController::class . ':getNota');

                $this->post('', NotaController::class . ':addNota');
                $this->put('/{nid}', NotaController::class . ':editNota');
                $this->delete('/{nid}', NotaController::class . ':delNota');
            });
        });
        
        $this->group('/professor/{pid}', function() {
            $this->post('', ProfessorController::class . ':addLeciona');
            $this->put('', ProfessorController::class . ':editLeciona');
            $this->delete('', ProfessorController::class . ':delLeciona');

            $this->group('/nota', function () {
                $this->get('', NotaController::class . ':getAllDetalheNota');
                $this->get('/{nid}', NotaController::class . ':getDetalheNota');

                $this->post('', NotaController::class . ':addDetalheNota');
                $this->put('/{nid}', NotaController::class . ':editDetalheNota');
                $this->delete('/{nid}', NotaController::class . ':delDetalheNota');
            });
        });
    });
});

// especifico para admin
$app->group('/admin', function () {
    // $this->get('/users', AdminController::class . ':getAllUser');
});

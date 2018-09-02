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

// route generica para um user (prof ou aluno)
$app->group('/{_:user|professor|aluno}', function () {

    //$this->get('', UserController::class . ':getAllUser');
    $this->get('/{uid}', UserController::class . ':getUser');

    // 1
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

    $this->group('/disciplina', function () {
        // $this->get('', ProfessorController::class . ':getAllLecionaDisciplina');
        $this->get('/{did}', ProfessorController::class . ':getLeciona');
        $this->post('/{did}', ProfessorController::class . ':addLeciona');
        $this->put('/{did}', ProfessorController::class . ':editLeciona');
        $this->delete('/{did}', ProfessorController::class . ':delLeciona');

        $this->group('/nota', function () {
            // $this->get('', NotaController::class . ':getAllDetalheNota');
            $this->get('/{nid}', NotaController::class . ':getDetalheNota');

            // 6
            // $this->post('', NotaController::class . ':addDetalheNota');
            // $this->put('/{nid}', NotaController::class . ':editDetalheNota');
            // $this->delete('/{nid}', NotaController::class . ':delDetalheNota');
        });
    });

    $this->group('/curso', function () {
        $this->get('/{cid}', CursoController::class . ':getCurso');

        // 7
        // $this->post('', CursoController::class . ':addCurso');
        // $this->put('/{cid}', CursoController::class . ':editCurso');
        // $this->delete('/{cid}', CursoController::class . ':delCurso');
    });
});

// especifico do aluno
$app->group('/aluno/{aid}', function () {

    $this->group('/disciplina', function () {
        // $this->get('', AlunoController::class . ':getAllMatriculaDisciplina');
        $this->get('/{did}', AlunoController::class . ':getMatricula');

        // 3
        // $this->post('/{did}', AlunoController::class . ':addMatricula');
        // $this->delete('/{did}', AlunoController::class . ':delMatricula');


        $this->group('/nota', function () {
            $this->get('', NotaController::class . ':getAllNota');
            $this->get('/{nid}', NotaController::class . ':getNota');
            // $this->post('', NotaController::class . ':addNota');
            // $this->put('/{nid}', NotaController::class . ':editNota');
            // $this->delete('/{nid}', NotaController::class . ':delNota');
        });
    });

});

// especifico da disciplina
$app->group('/disciplina', function () {
    // $this->get('', DisciplinaController::class . ':getAllDisciplina');

    // 4
    // $this->post('', DisciplinaController::class . ':addDisciplina');

    $this->group('/{did}', function () {
        $this->get('', DisciplinaController::class . ':getDisciplina');
        // $this->put('', DisciplinaController::class . ':editDisciplina');
        // $this->delete('', DisciplinaController::class . ':delDisciplina');
        
        $this->group('/aluno', function() {
            $this->get('', AlunoController::class . ':getAllMatriculaAluno');
            $this->get('/{aid}', AlunoController::class . ':getMatricula');
            // $this->post('/{aid}', AlunoController::class . ':addMatricula');
            // $this->delete('/{aid}', AlunoController::class . ':delMatricula');

            $this->group('/nota', function () {
                // $this->get('', NotaController::class . ':getAllNota');
                // $this->get('/{nid}', NotaController::class . ':getNota');

                // 5
                // $this->post('', NotaController::class . ':addNota');
                // $this->put('/{nid}', NotaController::class . ':editNota');
                // $this->delete('/{nid}', NotaController::class . ':delNota');
            });
        });
        
        $this->group('/professor', function() {
            // $this->post('', ProfessorController::class . ':addLeciona');
            
            // 2
            // $this->put('/{pid}', ProfessorController::class . ':editLeciona');
            // $this->delete('/{pid}', ProfessorController::class . ':delLeciona');

            $this->group('/nota', function () {
                // $this->get('', NotaController::class . ':getAllDetalheNota');
                // $this->get('/{nid}', NotaController::class . ':getDetalheNota');

                // 6
                // $this->post('', NotaController::class . ':addDetalheNota');
                // $this->put('/{nid}', NotaController::class . ':editDetalheNota');
                // $this->delete('/{nid}', NotaController::class . ':delDetalheNota');
            });
        });
    });
});

$app->group('/curso', function () {

    // $this->get('/', CursoController::class . ':getAllCurso');
    $this->get('/{id}', CursoController::class . ':getCurso');

    // 7
    // $this->post('', CursoController::class . ':addCurso');
    // $this->put('/{lid}', CursoController::class . ':editCurso');
    // $this->delete('/{lid}', CursoController::class . ':delCurso');

    $this->group('/{lid}/disciplina', function () {
        // $this->get('', DisciplinaController::class . ':getAllCurso');

        // 8
        // $this->put('/{did}', DisciplinaController::class . ':editCurso');
        // $this->delete('/{did}', DisciplinaController::class . ':delCurso');
    });
});


// especifico para admin
$app->group('/admin', function () {
    // $this->get('/users', AdminController::class . ':getAllUser');
});

<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Controllers
use \Controller\UserController as UserController;
use \Controller\DisciplinaController as DisciplinaController;
use \Controller\NotasController as NotasController;
use \Controller\AdminController as AdminController;
use \Controller\NotificacaoController as NotificacaoController;
// use \Controller\AuthController as AuthController;

// route generica para um user (prof ou aluno)
$app->group('/{_:user|professor|aluno}', function () {

    $this->get('', UserController::class . ':getAllUser');
    $this->get('/{uid}', UserController::class . ':getUser');
    $this->post('', UserController::class . ':addUser');
    $this->put('/{uid}', UserController::class . ':editUser');
    $this->delete('/{uid}', UserController::class . ':delUser');

    $this->group('/{uid}/notificacao', function () {
        $this->get('', NotificacaoController::class . ':getAllMsg');
        $this->get('/{nid}', NotificacaoController::class . ':getMsg');
        $this->post('', NotificacaoController::class . ':addMsg');
        $this->put('/{nid}', NotificacaoController::class . ':editMsg');
        $this->delete('/{nid}', NotificacaoController::class . ':delMsg');
    });
});

// especifico do professor
$app->group('/professor/{pid}', function () {

    $this->group('/disciplina', function () {
        $this->get('', UserController::class . ':getAllLecionaDisciplina');
        $this->get('/{did}', UserController::class . ':getLeciona');

        $this->post('/{did}', UserController::class . ':addLeciona');
        $this->put('/{did}', UserController::class . ':editLeciona');
        $this->delete('/{did}', UserController::class . ':delLeciona');

        $this->group('/nota', function () {
            $this->get('', NotaController::class . ':getAllDetalheNota');
            $this->get('/{nid}', NotaController::class . ':getDetalheNota');
            
            $this->post('', NotaController::class . ':addDetalheNota');
            $this->put('/{nid}', NotaController::class . ':editDetalheNota');
            $this->delete('/{nid}', NotaController::class . ':delDetalheNota');
        });
    });

    $this->group('/curso', function () {
        // $this->get('/{cid}', DisciplinaController::class . ':getCurso');
        // $this->post('', DisciplinaController::class . ':addCurso');
        // $this->put('/{cid}', DisciplinaController::class . ':editCurso');
        // $this->delete('/{cid}', DisciplinaController::class . ':delCurso');
    });
});

// especifico do aluno
$app->group('/aluno/{aid}', function () {

    $this->group('/disciplina', function () {
        $this->get('', UserController::class . ':getAllMatriculaDisciplina');
        $this->get('/{did}', UserController::class . ':getMatricula');
        $this->post('/{did}', UserController::class . ':addMatricula');
        $this->delete('/{did}', UserController::class . ':delMatricula');


        $this->group('/nota', function () {
            $this->get('', NotaController::class . ':getAllNota');
            $this->get('/{nid}', NotaController::class . ':getNota');
            $this->post('', NotaController::class . ':addNota');
            $this->put('/{nid}', NotaController::class . ':editNota');
            $this->delete('/{nid}', NotaController::class . ':delNota');
        });
    });

    // [??]
    // $this->group('/nota', function () {
    //     // $this->get('', NotaController::class . ':getAllNota');
    //     // $this->get('/{nid}', NotaController::class . ':getNota');
    // });
    // [??]
});

// especifico da disciplina
$app->group('/disciplina', function () {
    // $this->post('', DisciplinaController::class . ':addDisciplina');

    $this->group('/{did}', function () {
        // $this->put('', DisciplinaController::class . ':editDisciplina');
        // $this->delete('', DisciplinaController::class . ':delDisciplina');
        
        $this->group('/aluno', function() {
            $this->get('', UserController::class . ':getAllMatriculaAluno');
            $this->get('/{aid}', UserController::class . ':getMatricula');
            $this->post('/{aid}', UserController::class . ':addMatricula');
            $this->delete('/{aid}', UserController::class . ':delMatricula');

            $this->group('/nota', function () {
                // $this->get('', NotaController::class . ':getAllNota');
                // $this->get('/{nid}', NotaController::class . ':getNota');
                // $this->post('', NotaController::class . ':addNota');
                // $this->put('/{nid}', NotaController::class . ':editNota');
                // $this->delete('/{nid}', NotaController::class . ':delNota');
            });
        });
        
        $this->group('/professor', function() {
            $this->post('', UserController::class . ':addLeciona');
            $this->put('/{pid}', UserController::class . ':editLeciona');
            $this->delete('/{pid}', UserController::class . ':delLeciona');
        });
    });
});

$app->group('/curso', function () {

    // $this->get('/', DisciplinaController::class . ':getAllCurso');
    // $this->get('/{id}', DisciplinaController::class . ':getCurso');
    // $this->post('', DisciplinaController::class . ':addCurso');
    // $this->put('/{lid}', DisciplinaController::class . ':editCurso');
    // $this->delete('/{lid}', DisciplinaController::class . ':delCurso');

    $this->group('/disciplina', function () {
        // $this->get('', DisciplinaController::class . ':MatriculaCurso');
        // $this->get('/{id}', DisciplinaController::class . ':getDisciplinaCurso');

        // $this->post('/{did}', DisciplinaController::class . ':addDisciplinaCurso');
        // $this->delete('/{did}', DisciplinaController::class . ':delDisciplinaCurso');
    });
});


// especifico para admin
$app->group('/admin', function () {
    // $this->get('/users', AdminController::class . ':getAllUser');
});

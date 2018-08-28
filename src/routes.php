<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Controllers
use \Controller\UserController as UserController;
use \Controller\DisciplinaController as DisciplinaController;
use \Controller\NotasController as NotasController;
use \Controller\AdminController as AdminController;
use \Controller\AuthController as AuthController;

// criar os controllers materia, notas e etc. para separar a logica de cada grupo de rotas

// Routes

// route generica para um user (prof ou aluno)
$app->group('/{_:user|professor|aluno}/{uid}', function () {

    // call controller
    // CRUD User
    $this->get('', UserController::class . ':detalheUser');
});

// especifico do professor
$app->group('/professor/{uid}', function () {

    // CRUD recebiveis
    $this->group('/disciplina', function () {
        $this->get('', DisciplinaController::class . ':todasMaterias');
        $this->get('/{id}', DisciplinaController::class . ':detalheMateriaProf');

        // pode-se colocar group dentro de group para facilitar a organizacao
        // a route para chegar aqui vai ser a soma de todos os group anteriores + a route de dentro do method
        $this->group('/notas', function () {
            $this->get('', NotasController::class . ':todasNotas');
            // $this->get('/{id}', UserController::class . ':detalheNota');
        });
    });

    $this->group('/leciona', function () {
        $this->post('', UserController::class . ':addProfMateria');
        $this->put('/{lid}', UserController::class . ':editProfMateria');
        $this->delete('/{lid}', UserController::class . ':delProfMateria');
    });
});

// especifico do aluno
$app->group('/aluno/{uid}', function () {

    $this->group('/disciplina', function () {
        $this->get('', DisciplinaController::class . ':todasMaterias');
        $this->get('/{id}', DisciplinaController::class . ':detalheMateriaAluno');
    });

    $this->group('/matricula', function () {
        $this->post('', UserController::class . ':addAlunoMateria');
        $this->put('/{mid}', UserController::class . ':editAlunoMateria');
        $this->delete('/{mid}', UserController::class . ':delAlunoMateria');
    });

    $this->get('/notas', UserController::class . ':notas');
});

// especifico da disciplina
$app->group('/disciplina/{uid}', function () {

    // CRUD recebiveis
    $this->group('/nota', function () {
        $this->get('', DisciplinaController::class . ':todasMaterias');
        $this->get('/{id}', DisciplinaController::class . ':detalheMateriaProf');

        // pode-se colocar group dentro de group para facilitar a organizacao
        // a route para chegar aqui vai ser a soma de todos os group anteriores + a route de dentro do method
        $this->group('/nota', function () {
            $this->get('', NotasController::class . ':todasNotas');
            // $this->get('/{id}', UserController::class . ':detalheNota');
        });
    });
    
    $this->group('/aluno', function() {
        $this->post('', UserController::class . ':addAlunoMateria');
        // $this->put('/{id}', UserController::class . ':editAlunoMateria');
        $this->delete('/{id}', UserController::class . ':delAlunoMateria');
    });

    $this->group('/professor', function() {
        $this->post('', UserController::class . ':addProfMateria');
        $this->put('/{id}', UserController::class . ':editProfMateria');
        $this->delete('/{id}', UserController::class . ':delProfMateria');
    });
});

// especifico para admin
$app->group('/admin', function () {
    $this->get('/users', AdminController::class . ':todosAlunos');

});

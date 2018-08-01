<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// use \Service\DBOController as DBOController;

// Controllers
use \Controller\UserController as UserController;
// create the controllers matter, grades and etc. to separate the logic of each group of routes

// Routes

// Generic route for a user (teacher or student)
$app->group('/{_:user|teacher|student}/{uid}', function () {

    // call controller
    // CRUD User
    $this->post('', UserController::class . ':new');
    $this->get('', UserController::class . ':detailUser');
    $this->put('', UserController::class . ':update');
    $this->delete('', UserController::class . ':delete');
});

// teacher specific route
$app->group('/teacher/{uid}', function () {
    
    // CRUD recebiveis
    $this->group('/schoolsubject', function() {
        $this->post('', UserController::class . ':addMateria');
        $this->get('', UserController::class . ':allSubjects');
        $this->get('/{id}', UserController::class . ':detailSubject');
        $this->put('/{id}', UserController::class . ':changeSubject');
        $this->delete('/{id}', UserController::class . ':removeSubject');

        // you can put group into group to facilitate the organization
        // the route to get here will be the sum of all the previous group + the route from within the method
        $this->group('/grade', function() {
            $this->post('', UserController::class . ':addGrade');
            $this->get('', UserController::class . ':allGrades');
            $this->get('/{id}', UserController::class . ':detailGrade');
            $this->put('/{id}', UserController::class . ':changeGrade');
            $this->delete('/{id}', UserController::class . ':removeGrade');
        });
    });
});

// student specific route
$app->group('/student/{uid}', function () {

    $this->group('/schoolsubject', function() {
        $this->post('', UserController::class . ':addSubject');
        $this->get('', UserController::class . ':allSubject');
        $this->get('/{id}', UserController::class . ':detailSubject');
        $this->put('/{id}', UserController::class . ':changeSubject');
        $this->delete('/{id}', UserController::class . ':removeSubject');
    });

    $this->get('/grades', UserController::class . ':grades');
});

// administrator specific route
$app->group('/admin/{uid}', function () {
    // TODO
});

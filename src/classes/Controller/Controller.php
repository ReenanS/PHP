<?php
namespace Controller;

use \Slim\Container as ContainerInterface;

use \View\JSONAPI;
use \Service\Security;
use \DBO\Users\UserDBO as User;
use \Controller\ModelController;

abstract class Controller
{
    protected $container;
    protected $db;
    protected $logger;
    protected $view;
    protected $security;
    protected $user;
    protected $models;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = $container->db;
        $this->logger = $container->logger;
        $this->view = new JSONAPI();
        $this->security = new Security($this->db);
        $this->user = new User($this->db);
        $this->models = new ModelController($this->db);
    }

    public function profLecionaDisciplina($professor,$disciplina) {
        // get prof leciona a disciplina
        $model = $this->models->leciona();
        $model->professor = $professor;
        $model->disciplina = $disciplina;
        $model->readByFk();
        return ($model->getId() == null);
    }

    public function alunoMatriculado($aluno,$disciplina) {
        // aluno esta matriculado
        $model = $this->models->matricula();
        $model->disciplina = $disciplina;
        $model->aluno = $aluno;
        $model->readByFk();
        return ($model->getId() == null);
    }

    // CRUD Operations

    // cria uma nova classe no BD de um model generico
    // $data é a classe DBO ja preenchida
    // $fk é a as chaves da tabela q a serem adcionadas
    protected function create($data, $fk = [])
    {
        $model = $this->models->{$data->getType()}();
        $info = $data->getAttributes();
        foreach ($fk as $k => $v) {
            $info[$k] = $v;
        }
        $model->set($info);
        return $model->create();
    }

    // le os attributos de um modelo dbo generico no formato da JSON API
    // $model é a classe DBO
    protected function read($model) {
        try {
            $data = $this->view->getData();
            $data->setType($model->getType());
            $data->setAttributes($model->get());
            $data->setId($model->getId());
            $relations = $model->getRelations();

            foreach ($relations as $rType) {
                $rModel = $this->models->{$rType}();
                $allR = $rModel->readAllByFK($model->getType(), $model->getId());
                foreach($allR as $r) {
                    if ($r->getId() == null) continue;
                    $item = $this->view->newItem();

                    if ($r->getType() == "leciona") {
                        if ($model->getType() == "disciplina") {
                            $ri = $this->models->professor();
                            $ri->setId($r->professor);
                            $ri->read();
                        } else if ($model->getType() == "professor") {
                            $ri = $this->models->disciplina();
                            $ri->setId($r->disciplina);
                            $ri->read();
                        }
                    } else if ($r->getType() == "matricula") {
                        if ($model->getType() == "disciplina") {
                            $ri = $this->models->aluno();
                            $ri->setId($r->aluno);
                            $ri->read();
                        } else if ($model->getType() == "aluno") {
                            $ri = $this->models->disciplina();
                            $ri->setId($r->disciplina);
                            $ri->read();
                        }
                    } else {
                        $ri = $r;
                    }

                    $item->setId($ri->getId());
                    $item->setType($ri->getType());
                    $data->addRelationships($item->get());

                    $item->setAttributes($ri->get());
                    $this->view->addIncluded($item);
                }
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    protected function readAll($model, $restriction = null) {
        try {
            $allInfo = ($restriction == null) ? 
                        $model->readAll():
                        $model->readAllByFk($restriction['filter'],$restriction['id']);
            $relations = $model->getRelations();
            foreach($allInfo as $info) {
                $item = $this->view->newItem();
                $item->setType($info->getType());
                $item->setId($info->getId());
                $item->setAttributes($info->get());
                
                foreach ($relations as $rType) {
                    $rModel = $this->models->{$rType}();
                    $allR = $rModel->readAllByFK($info->getType(), $info->getId());
                    foreach($allR as $r) {
                        if ($r->getId() == null) continue;
                        $rItem = $this->view->newItem();

                        if ($r->getType() == "leciona") {
                            if ($model->getType() == "disciplina") {
                                $ri = $this->models->professor();
                                $ri->setId($r->professor);
                                $ri->read();
                            } else if ($model->getType() == "professor") {
                                $ri = $this->models->disciplina();
                                $ri->setId($r->disciplina);
                                $ri->read();
                            }
                        } else if ($r->getType() == "matricula") {
                            if ($model->getType() == "disciplina") {
                                $ri = $this->models->aluno();
                                $ri->setId($r->aluno);
                                $ri->read();
                            } else if ($model->getType() == "aluno") {
                                $ri = $this->models->disciplina();
                                $ri->setId($r->disciplina);
                                $ri->read();
                            }
                        } else {
                            $ri = $r;
                        }

                        $rItem->setId($ri->getId());
                        $rItem->setType($ri->getType());
                        $item->addRelationships($rItem->get());
                    }
                }
                $this->view->addData($item);
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // protected function update($data, $fk = [])
    // {
    //     $model = $this->models->{$data->getType()}();
    //     $info = $data->getAttributes();
    //     foreach ($fk as $k => $v) {
    //         $info[$k] = $v;
    //     }
    //     $model->set($info);
    //     return $model->update();
    // }

    // protected function delete($data, $fk = [])
    // {
    //     $model = $this->models->{$data->getType()}();
    //     $info = $data->getAttributes();
    //     foreach ($fk as $k => $v) {
    //         $info[$k] = $v;
    //     }
    //     $model->set($info);
    //     return $model->delete();
    // }
}
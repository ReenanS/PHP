<?php
namespace Controller;

use \Slim\Container as ContainerInterface;

use \View\JSONAPI;
use \Service\Security;
use \DBO\Users\UserDBO as User;
use \DBO\DisciplinaDBO as Disciplina;
use \DBO\NotaDBO as Nota;
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
        //$this->disciplina = new Disciplina($this->db);
        //$this->nota = new Nota($this->db);
        $this->models = new ModelController($this->db);
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
    protected function read($model)
    {
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        /*foreach ($model->getRelations() as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK($model->getType(), $model->getId());
            if ($rModel->getId() == null) continue;

            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $data->addRelationships($item->get());
            $item->setAttributes($rModel->get());
            $this->view->addIncluded($item);
        }
        */
    }

    protected function update($data, $fk = [])
    {
        $model = $this->models->{$data->getType()}();
        $info = $data->getAttributes();
        foreach ($fk as $k => $v) {
            $info[$k] = $v;
        }
        $model->set($info);
        return $model->update();
    }

    protected function delete($data, $fk = [])
    {
        $model = $this->models->{$data->getType()}();
        $info = $data->getAttributes();
        foreach ($fk as $k => $v) {
            $info[$k] = $v;
        }
        $model->set($info);
        return $model->delete();
    }
}
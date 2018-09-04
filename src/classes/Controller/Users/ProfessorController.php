<?php
namespace Controller\Users;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class ProfessorController extends Controller
{
    public function getAllLecionaDisciplina($request, $response, $args) {
        $professor = $args['pid'];

        try {
            $model = $this->models->disciplina();
            $filter = array(
                "filter" => "professor",
                "id" => $professor
            );
            $this->readAll($model, $filter);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: all leciona: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function getLeciona($request, $response, $args)
    {
        $professor = $args['pid'];
        $disciplina = $args['did'];

        try {
            // // get prof leciona a disciplina
            // $model = $this->models->leciona();
            // $model->disciplina = $disciplina;
            // $model->professor = $professor;
            // $model->readByFk();
            // if ($model->getId() == null) return $response->withStatus(401);
            if ($this->profLecionaDisciplina($professor, $disciplina)) return $response->withStatus(401);
            
            $model = $this->models->disciplina();
            $model->setId($disciplina);
            $model->read();

            $this->read($model);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: user: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 

        return $response;
    }

    public function addLeciona($request, $response, $args) {
        $professor = $args['pid'];
        $disciplina = $args['did'];

        $this->db->beginTransaction();
        try {
            $model = $this->models->leciona();
            $data = array(
                "professor" => $professor,
                "disciplina" => $disciplina,
                "notificado" => 0,
                "status" => 0
            );
            $model->set($data);
            $id = $model->create();
            if ($id == null) return $response->withStatus(400);
            
            $path = $request->getUri()->getPath();
            $response = $response->withStatus(201)->withHeader('location', $path);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }

    public function editLeciona($request, $response, $args) {
        $professor = $args['pid'];
        $disciplina = $args['did'];

        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();

            // get prof leciona a disciplina
            $model = $this->models->leciona();
            $model->disciplina = $disciplina;
            $model->professor = $professor;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

            $model->setId($model->getId());
            $model->set($atrib);
            $model->update();

            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }

    public function delLeciona($request, $response, $args) {
        $professor = $args['pid'];
        $disciplina = $args['did'];

        $this->db->beginTransaction();
        try {
            // get prof leciona a disciplina
            $model = $this->models->leciona();
            $model->disciplina = $disciplina;
            $model->professor = $professor;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

            $model->delete();
            $response = $response->withStatus(204);

            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }
}
<?php
namespace Controller\Business;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class DisciplinaController extends Controller
{
    // DISCIPLINA

    public function getAllDisciplina($request, $response, $args)
    {
        try {
            $model = $this->models->disciplina();
            $this->readAll($model);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: all disciplina: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function getDisciplina($request, $response, $args)
    {
        $disciplina = $args['did'];

        try {
            $model = $this->models->disciplina();
            $model->setId($disciplina);
            $model->read();
            if (empty($model->get())) return $response->withStatus(404);

            $this->read($model);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: user: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function addDisciplina($request, $response, $args)
    {
        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();

            $model = $this->models->disciplina();
            $model->set($atrib);
            $id = $model->create();
            if ($id == null) return $response->withStatus(400);

            $path = $request->getUri()->getPath() . "/" . $id;
            $response = $response->withStatus(201)->withHeader('location', $path);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }

        return $response;
    }

    public function editDisciplina($request, $response, $args)
    {
        $disciplina = $args['did'];

        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();

            $model = $this->models->disciplina();
            $model->setId($disciplina);
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

    public function delDisciplina($request, $response, $args)
    {
        $disciplina = $args['did'];

        $this->db->beginTransaction();
        try {
            $model = $this->models->disciplina();
            $model->setId($disciplina);
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


    // CURSO

    public function getAllCurso($request, $response, $args)
    {
        // TODO
        // Retorna todas as disciplinas que sao daquele curso
        return $response;
    }

    public function editCurso($request, $response, $args)
    {
        // TODO
        // Altera o curso da disciplina
        return $response;
    }

    public function delCurso($request, $response, $args)
    {
        // TODO
        // Deleta a disciplina daquele curso
        return $response;
    }


    public function detalheMateriaAluno($request, $response, $args)
    {

        
    }

    public function detalheMateriadisciplina($request, $response, $args)
    {

        
    }

}

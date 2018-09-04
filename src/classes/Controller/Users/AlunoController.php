<?php
namespace Controller\Users;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

// Exemplo de Implementação
class AlunoController extends Controller
{
    public function getAllMatriculaDisciplina($request, $response, $args)
    {
        $aluno = $args['aid'];

        try {
            $model = $this->models->disciplina();
            $filter = array(
                "filter" => "aluno",
                "id" => $aluno
            );
            $this->readAll($model, $filter);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: all matricula: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function getAllMatriculaAluno($request, $response, $args)
    {
        $disciplina = $args['did'];

        try {
            $model = $this->models->aluno();
            $filter = array(
                "filter" => "disciplina",
                "id" => $disciplina
            );
            $this->readAll($model, $filter);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: all matricula: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function getMatricula($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];

        try {
            if ($this->alunoMatriculado($aluno,$disciplina)) return $response->withStatus(401);

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

    public function addMatricula($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];

        $this->db->beginTransaction();
        try {
            $model = $this->models->matricula();
            $data = array(
                "aluno" => $aluno,
                "disciplina" => $disciplina
            );
            $model->set($data);
            $mid = $model->create();
            if ($mid == null) return $response->withStatus(400);

            $path = $request->getUri()->getPath();
            $response = $response->withStatus(201)->withHeader('location', $path);
            
            $this->db->commit();
        } catch (PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }

        return $response;
    }

    public function delMatricula($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];

        $this->db->beginTransaction();
        try {
            $model = $this->models->matricula();
            $model->disciplina = $disciplina;
            $model->aluno = $aluno;
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

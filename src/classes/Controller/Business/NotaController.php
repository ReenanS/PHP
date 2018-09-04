<?php
namespace Controller\Business;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class NotaController extends Controller
{
    // PROFESSOR

    public function getAllDetalheNota($request, $response, $args)
    {
        $professor = $args['pid'];
        $disciplina = $args['did'];

        try {
            if ($this->profLecionaDisciplina($professor, $disciplina)) return $response->withStatus(401);
            
            $model = $this->models->detalhe();
            $filter = array(
                "filter" => "disciplina",
                "id" => $disciplina
            );
            $this->readAll($model, $filter);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: all detalhe nota: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function getDetalheNota($request, $response, $args)
    {
        $professor = $args['pid'];
        $disciplina = $args['did'];
        $id = $args['nid'];

        try {
            if ($this->profLecionaDisciplina($professor, $disciplina)) return $response->withStatus(401);

            $model = $this->models->detalhe();
            $model->setId($id);
            $model->read();
            if ($model->disciplina != $disciplina) return $response->withStatus(401);

            $this->read($model);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: detalhe: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 

        return $response;
    }

    public function addDetalheNota($request, $response, $args)
    {
        $professor = $args['pid'];
        $disciplina = $args['did'];

        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();
        
            if ($this->profLecionaDisciplina($professor, $disciplina)) return $response->withStatus(401);

            // set fk
            $atrib['disciplina'] = $disciplina;
            
            $model = $this->models->detalhe();
            $model->set($atrib);
            $id = $model->create();
            if ($id == null) return $response->withStatus(400);

            $path = $request->getUri()->getPath() . "/" . $id;
            $response = $response->withStatus(201)->withHeader('location', $path);

            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo detalhe: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }

        return $response;
    }

    public function editDetalheNota($request, $response, $args)
    {
        $professor = $args['pid'];
        $disciplina = $args['did'];
        $detalhe = $args['nid'];

        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();

            if ($this->profLecionaDisciplina($professor, $disciplina)) return $response->withStatus(401);

            // detalhe pertence a materia
            $model = $this->models->detalhe();
            $model->setId($detalhe);
            $model->read();
            if ($model->disciplina != $disciplina) return $response->withStatus(401);
            
            $model->set($atrib);
            $model->update();

            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: edit detalhe: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }

    public function delDetalheNota($request, $response, $args)
    {
        $professor = $args['pid'];
        $disciplina = $args['did'];
        $detalhe = $args['nid'];

        $this->db->beginTransaction();
        try {
            if ($this->profLecionaDisciplina($professor, $disciplina)) return $response->withStatus(401);

            // detalhe pertence a materia
            $model = $this->models->detalhe();
            $model->setId($detalhe);
            $model->read();
            if ($model->disciplina != $disciplina) return $response->withStatus(401);
            
            $model->delete();
            $response = $response->withStatus(204);

            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: delete detalhe: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }

    // ALUNO

    public function getAllNota($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];

        try {
            if ($this->alunoMatriculado($aluno, $disciplina)) return $response->withStatus(401);
            
            $model = $this->models->nota();
            $filter = array(
                "filter" => ["disciplina","aluno"],
                "id" => [$disciplina,$aluno]
            );
            $this->readAll($model, $filter);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: all nota: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function getNota($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];
        $nota = $args['nid'];

        try {
            if ($this->alunoMatriculado($aluno, $disciplina)) return $response->withStatus(401);

            $model = $this->models->nota();
            $model->setId($nota);
            $model->read();
            if ($model->aluno != $aluno ||
                $model->disciplina != $disciplina) 
                    return $response->withStatus(401);

            $this->read($model);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: detalhe: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 

        return $response;
    }

    public function addNota($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];

        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();
        
            $model = $this->models->matricula();
            $model->disciplina = $disciplina;
            $model->aluno = $aluno;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

            // set fk
            $atrib['disciplina'] = $disciplina;
            $atrib['aluno'] = $aluno;
            $atrib['matricula'] = $model->getId();
            
            var_export($atrib);
            $model = $this->models->nota();
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

    public function editNota($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];
        $nota = $args['nid'];

        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();

            if ($this->alunoMatriculado($aluno,$disciplina)) return $response->withStatus(401);

            // nota pertence ao aluno
            $model = $this->models->nota();
            $model->setId($nota);
            $model->read();
            if ($model->aluno != $aluno ||
                $model->disciplina != $disciplina) 
                    return $response->withStatus(401);
            
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

    public function delNota($request, $response, $args)
    {
        $aluno = $args['aid'];
        $disciplina = $args['did'];
        $nota = $args['nid'];

        $this->db->beginTransaction();
        try {
            if ($this->alunoMatriculado($aluno,$disciplina)) return $response->withStatus(401);

            // nota pertence ao aluno
            $model = $this->models->nota();
            $model->setId($nota);
            $model->read();
            if ($model->aluno != $aluno ||
                $model->disciplina != $disciplina) 
                    return $response->withStatus(401);
            
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
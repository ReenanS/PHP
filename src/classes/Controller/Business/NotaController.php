<?php
namespace Controller\Business;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class NotaController extends Controller
{
    // alunoESSOR

    public function getAllDetalheNota($request, $response, $args)
    {
        // TODO
        // Retorna todas as infos (detalhe) das notas daquela disciplina

    }

    public function getDetalheNota($request, $response, $args)
    {
        // TODO
        // Retorna as infos (detalhe) de um nota daquela disciplina
        
        /*Prof*/
        $disciplina = $args['did']; //pega o id do usuario
        $model = $this->models->professor();
        
        /*Nota*/
        $nota = $args['nid']; //pega o id da nota

        // cria uma classe dbo baseado na nota
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->detalhe();
        $model->setId($nota); //setei o ID
        
        // busca os dados no BD
        $model->read();

        //if (!$model->validarDocente($prof)) return $response->withStatus(401);
        
        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAlunoMatriculados($disciplina);

        foreach ($alunos as $aluno) {
            $item = $this->view->newItem();
            $item->setId($aluno->getId());
            $item->setType($aluno->getType());
            //$this->view->getData()->addRelationships($item->get());
            $item->setAttributes($aluno->get());
            $this->view->addIncluded($item);

        }

        $response = $response->withJSON($this->view->get());
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
        
            // get prof leciona a disciplina
            $model = $this->models->leciona();
            $model->disciplina = $disciplina;
            $model->professor = $professor;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

            // set fk
            $atrib['disciplina'] = $disciplina;
            
            var_export($atrib);
            $model = $this->models->detalhe();
            $model->set($atrib);
            $did = $model->create();
            if ($did == null) return $response->withStatus(400);

            $response = $response->withStatus(201);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
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

            // get prof leciona a disciplina
            $model = $this->models->leciona();
            $model->disciplina = $disciplina;
            $model->professor = $professor;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

            // detalhe pertence a materia
            $model = $this->models->detalhe();
            $model->setId($detalhe);
            $model->read();
            if ($model->disciplina != $disciplina) return $response->withStatus(401);
            
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

    public function delDetalheNota($request, $response, $args)
    {
        $professor = $args['pid'];
        $disciplina = $args['did'];
        $detalhe = $args['nid'];

        $this->db->beginTransaction();
        try {
            // get prof leciona a disciplina
            $model = $this->models->leciona();
            $model->disciplina = $disciplina;
            $model->professor = $professor;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

            // detalhe pertence a materia
            $model = $this->models->detalhe();
            $model->setId($detalhe);
            $model->read();
            if ($model->disciplina != $disciplina) return $response->withStatus(401);
            
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

    // ALUNO

    public function getAllNota($request, $response, $args)
    {
        // TODO
        // Retorna todas as notas na discilplina do aluno
        /*aluno*/
        $aluno = $args['aid']; //pega o id do usuario
        $model = $this->models->aluno();
        $model->setId($aluno); //setei o ID
        
        // busca os dados no BD
        $model->read();

       // if (!$model->validarDocente($aluno)) return $response->withStatus(401);

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "disciplina";
        $rModel = $this->models->{$r}();
        $disciplinas = $rModel->readAll();
        foreach ($disciplinas as $disciplina) {
            $item = $this->view->newItem();
            $item->setId($disciplina->getId());
            $item->setType($disciplina->getType());
            //$this->view->getData()->addRelationships($item->get());
            $item->setAttributes($disciplina->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function getNota($request, $response, $args)
    {
        // TODO
        // Retorna todas as info da nota na discilplina do aluno
        
        /*aluno*/
        $aluno = $args['aid']; //pega o id do usuario
        $model = $this->models->aluno();
        
        /*nota*/
        $nota = $args['nid']; //pega o id da nota

        // cria uma classe dbo baseado no tipo do nota
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->nota();
        $model->setId($nota); //setei o ID
        
        // busca os dados no BD
        $model->read();

       // if (!$model->validarDocente($aluno)) return $response->withStatus(401);

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAlunoMatriculados($nota);
        foreach ($alunos as $aluno) {
            $item = $this->view->newItem();
            $item->setId($aluno->getId());
            $item->setType($aluno->getType());
            //$this->view->getData()->addRelationships($item->get());
            $item->setAttributes($aluno->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
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
        
            // get matricula
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
            $nid = $model->create();
            if ($nid == null) return $response->withStatus(400);

            $response = $response->withStatus(201);
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

            // aluno esta matriculado
            $model = $this->models->matricula();
            $model->disciplina = $disciplina;
            $model->aluno = $aluno;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

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

            // aluno esta matriculado
            $model = $this->models->matricula();
            $model->disciplina = $disciplina;
            $model->aluno = $aluno;
            $model->readByFk();
            if ($model->getId() == null) return $response->withStatus(401);

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
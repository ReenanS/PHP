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
        // TODO
        // Retorna todas as disciplinas

        try {
            $fields = $this->models->disciplina();
            $allDisciplina = $fields->readAll();
            foreach ($allDisciplina as $f) {
                $item = $this->view->newItem();
                $item->setType($f->getType());
                $item->setId($f->getId());
                $item->setAttributes($f->get());
                $this->view->addData($item);
            }

            $response = $response->withJSON($this->view->get());

        } catch (PDOException $e) {
            $this->logger->addInfo("ERRO: fields: " . $e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;

    }

    public function getDisciplina($request, $response, $args)
    {
        // TODO
        // Retorna todas as infos da disciplina

        /*disciplina*/
        $disciplina = $args['did']; //pega o id do usuario
        $model = $this->models->disciplina();
        $model->setId($disciplina); //setei o ID
        
        // busca os dados no BD
        $model->read();

       // if (!$model->validarDocente($disciplina)) return $response->withStatus(401);

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAll();
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
<<<<<<< HEAD

=======
        
>>>>>>> parent of 88af8e2... added CRUD curso
    }

    public function detalheMateriadisciplina($request, $response, $args)
    {
<<<<<<< HEAD

=======
        
>>>>>>> parent of 88af8e2... added CRUD curso
    }

}

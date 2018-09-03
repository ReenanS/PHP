<?php
namespace Controller\Users;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class ProfessorController extends Controller
{
    public function getAllLecionaDisciplina($request, $response, $args) {
        // TODO
        // Retorna todas as disciplinas que o docente leciona
        return $response;
    }

    public function getLeciona($request, $response, $args) {
        // TODO
        // Retorna as infos da disciplina se o professor estiver lecionando ela
        
        /*Prof*/
        $prof = $args['pid']; //pega o id do usuario
        $model = $this->models->professor();
        
        /*Disciplina*/
        $disciplina = $args['did']; //pega o id da disciplina

        // cria uma classe dbo baseado no tipo do disciplina
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->disciplina();
        $model->setId($disciplina); //setei o ID
        
        // busca os dados no BD
        $model->read();

        if (!$model->validarDocente($prof)) return $response->withStatus(401);

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAlunoMatriculados($disciplina);
        // var_export($rModel);
        foreach ($alunos as $aluno) {
            $item = $this->view->newItem();
            $item->setId($aluno->getId());
            $item->setType($aluno->getType());
            $this->view->getData()->addRelationships($item->get());
            // $aluno->get(): não faz get em um array
            // colocar um foreach aqui
            $item->setAttributes($aluno->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function addLeciona($request, $response, $args) {
        // TODO
        // Cria info que o prof leciona a disciplina

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
            $lid = $model->create();
            if ($lid == null) return $response->withStatus(400);

            $response = $response->withStatus(201);
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

            $model = $this->models->leciona();
            // $model->professor = $professor;
            // $model->disciplina = $disciplina;
            // $model->readByFK();
            $model->setId($data->getId());
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
            $model = $this->models->leciona();
            $model->professor = $professor;
            $model->disciplina = $disciplina;
            $model->readByFK();
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
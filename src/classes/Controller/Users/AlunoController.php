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
        // TODO
        // Retorna todas as disciplinas que o aluno está matriculado
        return $response;
    }

    public function getAllMatriculaAluno($request, $response, $args)
    {
        // TODO
        // Retorna todos os alunos matriculados na disciplina

        /*Disciplina*/
        $disciplina = $args['did']; //pega o id da disciplina

        // cria uma classe dbo baseado no tipo do disciplina
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->disciplina();
        $model->setId($disciplina); //setei o ID
        
        // busca os dados no BD
        $model->read();

        //if (!$model->validarMatricula($aluno)) return $response->withStatus(401);

        // $model->get();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Fazer as relations pegar da tabela notas/alunos
        // $relations = $this->disciplina->getRelations();
        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $rModel->readNota($aluno, $disciplina);
        if ($rModel->getId() != null) {
            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($rModel->getInfo());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function getMatricula($request, $response, $args)
    {
        // TODO
        // Retorna as infos da disciplina se o aluno estiver matriculado
        
        /*Aluno*/
        $aluno = $args['aid']; //pega o id do usuario
        $model = $this->models->aluno();
        
        /*Disciplina*/
        $disciplina = $args['did']; //pega o id da disciplina

        // cria uma classe dbo baseado no tipo do disciplina
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->disciplina();
        $model->setId($disciplina); //setei o ID
        
        // busca os dados no BD
        $model->read();

        //if (!$model->validarMatricula($aluno)) return $response->withStatus(401);

        // $model->get();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Fazer as relations pegar da tabela notas/alunos
        // $relations = $this->disciplina->getRelations();
        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "nota";
        $rModel = $this->models->{$r}();
        $rModel->readNota($aluno, $disciplina);
        if ($rModel->getId() != null) {
            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($rModel->getInfo());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function addMatricula($request, $response, $args)
    {
        // $token = $request->getHeader('Authorization')[0];
        // if (!$this->security->validar($token)) 
        //     return $response->withStatus(401);
        $aluno = $args['aid'];
        $disciplina = $args['did'];

        // $body = $request->getParsedBody();
        // if (!isset($body['data'])) return $response->withStatus(400);
        // $this->view->set($body);

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

            $response = $response->withStatus(201);
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
            $model->aluno = $aluno;
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

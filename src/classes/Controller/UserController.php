<?php
namespace Controller;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

// Exemplo de Implementação
class UserController extends Controller
{
    
    // USERS

    public function getAllUser($request, $response, $args) 
    {
        // TODO
        // Retorna todos os usuários, professores ou alunos
        // que estão na base, dependendo do tipo
        return $response;
    }

    public function getUser($request, $response, $args)
    {
        $uid = $args['uid'];
        $tipo = $args['_'];

        if ($this->user->readByFK('user', $uid) == null) return $response->withStatus(403);

        $this->user->setTipoByKey($tipo);

        // cria uma classe dbo baseado no tipo do user (prof ou aluno)
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->{$this->user->getTipo()}();

        $model->readByFK('user', $this->user->getId());

        // busca os dados na BD
        $this->read($model);

        // busca todas as tbl relacionadas com esse user
        // no caso seria todas as tbl q possuem a fk do user
        $relations = $model->getRelations();

        // Preenche a view (JSON API) para retornar um JSON apropriado
        foreach ($relations as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK($this->user->getTipo(), $this->user->getId());
            if ($rModel->getId() == null) continue;
            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($rModel->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function addUser($request, $response, $args) {
        // TODO
        // Cria um novo usuário na base
        // Dependendo do tipo tb cria um prof ou aluno
        return $response;
    }

    public function editUser($request, $response, $args) {
        // TODO
        // Altera as infos de um user já criado na base
        // Dependendo do tipo tb altera um prof ou aluno
        return $response;
    }

    public function delUser($request, $response, $args) {
        // TODO
        // Deleta um usuário na base
        // Dependendo do tipo tb deleta um prof ou aluno -> (talvez seja melhor colocar isso no Banco de Dados [delete on cascade])
        return $response;
    }










    // ALUNO

    public function getAllMatriculaDisciplina($request, $response, $args) {
        // TODO
        // Retorna todas as disciplinas que o aluno está matriculado
        return $response;
    }

    public function getAllMatriculaAluno($request, $response, $args) {
        // TODO
        // Retorna todos os alunos matriculados na disciplina
        return $response;
    }

    public function getMatricula($request, $response, $args) {
        // TODO
        // Retorna as infos da disciplina se o aluno estiver matriculado
        return $response;
    }

    public function addMatricula($request, $response, $args) {
        // TODO
        // Cria matricula do aluno na disciplina


        // $token = $request->getHeader('Authorization')[0];
        // if (!$this->security->validar($token)) 
        //     return $response->withStatus(401);
        $disciplina = $args['uid'];

        $body = $request->getParsedBody();
        $this->view->set($body);
        
        $this->db->beginTransaction();
        try {
            foreach($this->view->getData() as $data) {
                $model = $this->models->aluno();
                $data = $this->view->getData();
                $atrib = $data->getAttributes();
                var_export($atrib);
                var_export($disciplina);
                $model->matricular($atrib['id'],$disciplina);
            }

            $response = $response->withStatus(201);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        } 
        
        return $response;
    }

    public function delMatricula($request, $response, $args) {
        // TODO
        // Deleta a matricula do aluno na disciplina
        return $response;
    }








    

    // PROFESSOR

    public function getAllLecionaDisciplina($request, $response, $args) {
        // TODO
        // Retorna todas as disciplinas que o docente leciona
        return $response;
    }

    public function getLeciona($request, $response, $args) {
        // TODO
        // Retorna as infos da disciplina se o professor estiver lecionando ela
        return $response;
    }

    public function addLeciona($request, $response, $args) {
        // TODO
        // Cria info que o prof leciona a disciplina
        return $response;
    }

    public function editLeciona($request, $response, $args) {
        // TODO
        // Altera a info que o prof leciona a disciplina
        return $response;
    }

    public function delLeciona($request, $response, $args) {
        // TODO
        // Deleta a info que o prof leciona a disciplina
        return $response;
    }

}
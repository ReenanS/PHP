<?php
namespace Controller\Users;

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

                
        // cria uma classe dbo baseado no tipo do user (prof ou aluno)
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->{$this->user->getTipo()}();

        $model->readByFK('user', $this->user->getId());

        // busca os dados na BD
        $this->read($model);


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
        $tipo = $args['_'];

        $body = $request->getParsedBody();
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();

            // TODO: separar logica de criar usuarios
            $user = $this->models->user();
            $userData = array(
                "tipo" => $tipo,
                "email" => $atrib['email'],
                "pwd" => $atrib['pwd']
            );
            $user->set($userData);
            $uid = $user->create();
            if ($uid == null) return $response->withStatus(400);

            $model = $this->models->{$tipo}();
            $atrib['user'] = $uid;
            $model->set($atrib);
            $id = $model->create();
            if ($id == null) return $response->withStatus(400);

            $response = $response->withStatus(201);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }

        return $response;
    }

    public function editUser($request, $response, $args) {
        $tipo = $args['_'];
        $user = $args['uid'];

        $body = $request->getParsedBody();
        if (!isset($body['data'])) return $response->withStatus(400);
        $this->view->set($body);

        $this->db->beginTransaction();
        try {
            $data = $this->view->getData();
            $atrib = $data->getAttributes();

            // TODO: separate user
            $model = $this->models->{$tipo}();
            if ($tipo == "user") {
                $model->setId($user);
            } else {
                $model->user = $user;
                $model->readByFK('user',$user);
            }
            var_export($model->getId());
            $model->set($atrib);
            $model->update();
            // if ($id == null) return $response->withStatus(400);

            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }

        return $response;
    }

    public function delUser($request, $response, $args) {
        $tipo = $args['_'];
        $user = $args['uid'];

        $this->db->beginTransaction();
        try {
            // TODO: separate user
            $model = $this->models->{$tipo}();
            if ($tipo != "user") {
                $model->user = $user;
                $model->readByFK('user',$user);
                var_export($model->getId());
                $model->delete();
                $model = $this->models->user();
            }
            $model->setId($user);
            var_export($model->getId());
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
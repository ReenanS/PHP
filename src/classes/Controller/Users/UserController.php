<?php
namespace Controller\Users;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

// Exemplo de Implementação
class UserController extends Controller
{   
    // USERS

    public function getAllUser($request, $response, $args) {
        $tipo = $args['tipo'];

        try {
            $model = $this->models->{$tipo}();
            $this->readAll($model);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: all users: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 
        return $response;
    }

    public function getUser($request, $response, $args) {
        $id = $args['uid'];
        $tipo = $args['tipo'];

        try {
            $model = $this->models->{$tipo}();
            $model->setId($id);
            $model->read();
            if ($model->user == null) return $response->withStatus(404);
            $this->read($model);
            $response = $response->withJSON($this->view->get());
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: user: ".$e->getMessage());
            $response = $response->withStatus(400);
        } 

        return $response;
    }

    public function addUser($request, $response, $args) {
        $tipo = $args['tipo'];

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

    public function editUser($request, $response, $args) {
        $tipo = $args['tipo'];
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
            // if ($tipo == "user") {
            //     $model->setId($user);
            // } else {
            //     $model->user = $user;
            //     $model->readByFK('user',$user);
            // }
            $model->setId($user);
            // var_export($model->getId());
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
        $tipo = $args['tipo'];
        $id = $args['uid'];

        $this->db->beginTransaction();
        try {
            // TODO: separate user
            $model = $this->models->{$tipo}();
            $model->setId($id);
            $model->read();
            $user = $model->user;
            // var_export($model->getId());
            $model->delete();

            $model = $this->models->user();
            $model->setId($user);
            // var_export($model->getId());
            $model->delete();
            $response = $response->withStatus(204);

            // if ($tipo != "user") {
            //     $model->user = $user;
            //     $model->readByFK('user',$user);
            //     var_export($model->getId());
            //     $model->delete();
            //     $model = $this->models->user();
            // }
            // $model->setId($user);
            // var_export($model->getId());
            // $model->delete();
            // $response = $response->withStatus(204);

            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo Field: ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }
}
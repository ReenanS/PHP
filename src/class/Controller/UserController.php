<?php
namespace Controller;

// Controllers
use \Controller\Controller;
use \Controller\GameficationController;
// use \Controller\NotificationController;

use \View\ResourceObject;

// Implementation Example
class UserController extends Controller {
    
    // Routes Implementation

    // Example of Basic User Operations

    // Shows how to use the controller

    public function new($request, $response, $args) {
        // get url info
        $uid = $args['uid'];

        // get body request
        $body = $request->getParsedBody();
        if (!isset($body['data']['type'])) return $response->withStatus(400);

        // Use JSONAPI to read the JSON body of the message
        $this->view->set($body);

        // Set the user
        $this->user->setTypeByKey($this->view->getData()->getType());
        $this->user->setUID($uid);

        // start the process of creating the user in database
        // transaction guarantees that nothing will be written if it gives some error in the middle of the path
        // so it avoids errors
        $this->db->beginTransaction();
        try {
            // validate that the user was successfully created
            if ($this->createUser() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }

            // get the URL and send it as a location
            $url = $request->getUri()->getPath(); // . "/" . $uid;
            $response = $response->withStatus(201)->withHeader('location', $url);

            // log of the event
            $this->logger->addInfo("Sucess: New User ".$uid."|".$type." - ". $data['id']);

            // Saves the database
            $this->db->commit();
        } catch(PDOException $e) {
            // in case of error: creates a log, sends status 400 (bad request) and clears the DataBase operations
            $this->logger->addInfo("ERRO: New User ".$uid."|".$type.": ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        } 
        return $response;
    }

    public function detailUser($request, $response, $args) {
        $uid = $args['uid'];
        if ($this->user->readByFK('uid',$uid) == null) return $response->withStatus(403);

        // search all tables related to this user
        // in case it would be all the tables that have the foreign key of the user
        $relations = $this->user->getRelations();
        
        // create a dbo class based on user type (teacher or student)
        // the {'x'} calls a function from within the class passing a string to it (dynamic)
        $model = $this->models->{$this->user->getType()}();
        $model->readByFK('user',$this->user->getId());

        // search the data in the database
        $this->read($model);

        // Fill in the view (JSON API) to return an appropriate JSON
        foreach($relations as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK('user',$this->user->getId());
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
    
    public function update($request, $response, $args) {
        // TODO
        $response = $response->withStatus(202);
        return $response;
    }

    public function delete($request, $response, $args) {
        // TODO
        $response = $response->withStatus(204);
        return $response;
    }

    // How to Create Mock Server
    // Show how to use JSONAPI to return basic CRUD routes
    
    public function createMockRoute($request, $response, $args) {
        $response = $response->withStatus(201);
        return $response;
    }

    public function exampleMockManyInfos($request, $response, $args) {
        $item = $this->view->newItem();
        $item->setType('example');
        $item->setId('1');
        
        $mock = array(
            'name' => 'Mock 1',
            'email' => 'mock1@teste.com',
            'description' => 'NaN Rules!'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('exemplo');
        $item->setId('2');
        
        $mock = array(
            'name' => 'Mock 2',
            'email' => 'mock2@teste.com',
            'description' => 'NaN Rules!!'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('example');
        $item->setId('3');
        
        $mock = array(
            'name' => 'Mock 3',
            'email' => 'mock3@teste.com',
            'description' => 'NaN Rules!!!'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }
    
    public function exampleMockDetail($request, $response, $args) {
        $data = $this->view->getData();
        $data->setType('example');
        $data->setId('1');
        
        $mock = array(
            'nome' => 'Mock 1',
            'email' => 'mock@teste.com',
            'description' => 'NaN Rules!'
        );
        $data->setAttributes($mock);

        // address
        $item = $this->view->newItem();
        $item->setId('2');
        $item->setType('address');
        $mock = array(
            'cep' => '01230111',
            'type' => 'street',
            'street' => 'Albuquerque Lins',
            'number' => '808',
            'complement' => '101',
            'neighborhood' => 'Santa Cecília',
            'City' => 'São Paulo',
            'State' => 'SP'
        );
        $item->setAttributes($mock);
        $this->view->addIncluded($item);
        
        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function updateMock($request, $response, $args) {
        $response = $response->withStatus(202);
        return $response;
    }

    public function deleteMock($request, $response, $args) {
        $response = $response->withStatus(204);
        return $response;
    }
    
    // Business Logic

    public function createUser() {
        $userId = $this->user->create();
        if ($userId == null) return null;
        $fk['user'] = $userId;
        if ($this->create($this->view->getData(),$fk) == null) return null;
        foreach($this->view->getIncluded() as $i) {
            $this->create($i,$fk);
        }
        return true;
    }

}
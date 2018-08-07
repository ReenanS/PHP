<?php
namespace Controller;

// Controllers
use \Controller\Controller;
use \Controller\GameficationController;
// use \Controller\NotificationController;

use \View\ResourceObject;

// Exemplo de Implementação
class UserController extends Controller
{
    
    // Routes Implementation

    // Exemplo de Operacoes Basicas de User

    // Mostra como usar o controller

    public function novo($request, $response, $args)
    {
        // pega info da url
        $uid = $args['uid'];

        // pega requisicao do corpo
        $body = $request->getParsedBody();
        if (!isset($body['data']['type'])) return $response->withStatus(400);

        // Usa JSONAPI para ler o corpo JSON da mensagem
        $this->view->set($body);

        // Set the user
        $this->user->setTipoByKey($this->view->getData()->getType());
        $this->user->setUID($uid);

        // inicia o processo de criacao do user no BD
        // transaction garante que nada será escrito se der algum erro no meio do caminho
        // assim evita erros
        $this->db->beginTransaction();
        try {
            // valida q o user foi criado com sucesso
            if ($this->createUser() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }

            // pega a url e envia ela como location
            $url = $request->getUri()->getPath(); // . "/" . $uid;
            $response = $response->withStatus(201)->withHeader('location', $url);

            // log do evento
            $this->logger->addInfo("Sucesso: Novo User " . $uid . "|" . $type . " - " . $data['id']);

            // salva o bd
            $this->db->commit();
        } catch (PDOException $e) {
            // em caso de erro: cria um log, envia status 400 (bad request) e apaga as operacoes da BD
            $this->logger->addInfo("ERRO: Novo User " . $uid . "|" . $type . ": " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }

    public function detalheUser($request, $response, $args)
    {
        $uid = $args['uid'];
        if ($this->user->readByFK('uid', $uid) == null) return $response->withStatus(403);

        // busca todas as tbl relacionadas com esse user
        // no caso seria todas as tbl q possuem a fk do user
        $relations = $this->user->getRelations();
        
        // cria uma classe dbo baseado no tipo do user (prof ou aluno)
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->{$this->user->getTipo()}();
        $model->readByFK('user', $this->user->getId());

        // busca os dados na BD
        $this->read($model);

        // Preenche a view (JSON API) para retornar um JSON apropriado
        foreach ($relations as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK('user', $this->user->getId());
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

    public function atualizar($request, $response, $args)
    {
        $uid = $args['uid'];

        // inicia o processo para atualizar o user no BD
        // transaction garante que nada será atualizado se der algum erro no meio do caminho
        // assim evita erros
        $this->db->beginTransaction();
        try {
            // valida q o user foi deletado com sucesso
            if ($this->updateUser() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }

            // pega a url e envia ela como location
            $url = $request->getUri()->getPath(); // . "/" . $uid;
            $response = $response->withStatus(201)->withHeader('location', $url);

            // log do evento
            $this->logger->addInfo("Sucesso: Updated User " . $uid . "|" . $type . " - " . $data['id']);

            // salva o bd
            $this->db->commit();
        } catch (PDOException $e) {
            // em caso de erro: cria um log, envia status 400 (bad request) e apaga as operacoes da BD
            $this->logger->addInfo("ERRO: Updated User " . $uid . "|" . $type . ": " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        $response = $response->withStatus(202);
        return $response;
    }

    public function deletar($request, $response, $args)
    {
        $uid = $args['uid'];

        // inicia o processo para deletar o user no BD
        // transaction garante que nada será apagado se der algum erro no meio do caminho
        // assim evita erros
        $this->db->beginTransaction();
        try {
            // valida q o user foi deletado com sucesso
            if ($this->deleteUser() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }

            // pega a url e envia ela como location
            $url = $request->getUri()->getPath(); // . "/" . $uid;
            $response = $response->withStatus(201)->withHeader('location', $url);

            // log do evento
            $this->logger->addInfo("Sucesso: Deleted User " . $uid . "|" . $type . " - " . $data['id']);

            // salva o bd
            $this->db->commit();
        } catch (PDOException $e) {
            // em caso de erro: cria um log, envia status 400 (bad request) e apaga as operacoes da BD
            $this->logger->addInfo("ERRO: Deleted User " . $uid . "|" . $type . ": " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        $response = $response->withStatus(204);
        return $response;
    }
  
    // Exemplo de Como Criar o Server Mockado

    // Mostra com usar o JSONAPI para retornar rotas básicas do CRUD

    public function createMockRoute($request, $response, $args)
    {
        $response = $response->withStatus(201);
        return $response;
    }

    public function exemploMockVariasInfos($request, $response, $args)
    {
        $item = $this->view->newItem();
        $item->setType('exemplo');
        $item->setId('1');

        $mock = array(
            'nome' => 'Mock 1',
            'email' => 'mock1@teste.com',
            'descricao' => 'NaN Rules!'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('exemplo');
        $item->setId('2');

        $mock = array(
            'nome' => 'Mock 2',
            'email' => 'mock2@teste.com',
            'descricao' => 'NaN Rules!!'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('exemplo');
        $item->setId('3');

        $mock = array(
            'nome' => 'Mock 3',
            'email' => 'mock3@teste.com',
            'descricao' => 'NaN Rules!!!'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function exemploMockDetalhe($request, $response, $args)
    {
        $data = $this->view->getData();
        $data->setType('exemplo');
        $data->setId('1');

        $mock = array(
            'nome' => 'Mock 1',
            'email' => 'mock@teste.com',
            'descricao' => 'NaN Rules!'
        );
        $data->setAttributes($mock);

        // endereco
        $item = $this->view->newItem();
        $item->setId('2');
        $item->setType('endereco');
        $mock = array(
            'cep' => '01230111',
            'tipo' => 'rua',
            'logradouro' => 'Albuquerque Lins',
            'numero' => '808',
            'complemento' => '101',
            'bairro' => 'Santa Cecília',
            'cidade' => 'São Paulo',
            'estado' => 'SP'
        );
        $item->setAttributes($mock);
        $this->view->addIncluded($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function atualizarMock($request, $response, $args)
    {
        $response = $response->withStatus(202);
        return $response;
    }

    public function deletarMock($request, $response, $args)
    {
        $response = $response->withStatus(204);
        return $response;
    }
    
    // Business Logic

    public function createUser()
    {
        $userId = $this->user->create();
        if ($userId == null) return null;
        $fk['user'] = $userId;
        if ($this->create($this->view->getData(), $fk) == null) return null;
        foreach ($this->view->getIncluded() as $i) {
            $this->create($i, $fk);
        }
        return true;
    }

    public function deleteUser()
    {
        $userId = $this->user->delete();
        if ($userId == null) return null;
        $fk['user'] = $userId;
        if ($this->delete($this->view->getData(), $fk) == null) return null;
        foreach ($this->view->getIncluded() as $i) {
            $this->delete($i, $fk);
        }
        return true;
    }

    public function updateUser()
    {
        $userId = $this->user->update();
        if ($userId == null) return null;
        $fk['user'] = $userId;
        if ($this->update($this->view->getData(), $fk) == null) return null;
        foreach ($this->view->getIncluded() as $i) {
            $this->update($i, $fk);
        }
        return true;
    }

}
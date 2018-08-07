<?php
namespace Controller;

class AdminController extends Controller
{
    // Add Admin specific routes

    // Exemplo
    public function addAluno($request, $response, $args)
    {
        // pega info da url
        $alunoid = $args['alunoid'];

        // pega requisicao do corpo
        $body = $request->getParsedBody();
        if (!isset($body['data']['type'])) return $response->withStatus(400);

        // Usa JSONAPI para ler o corpo JSON da mensagem
        $this->view->set($body);

        // Set the user
        $this->user->setTipoByKey($this->view->getData()->getType());
        $this->user->setAlunoID($alunoid);

        // inicia o processo de criacao do user no BD
        // transaction garante que nada será escrito se der algum erro no meio do caminho
        // assim evita erros
        $this->db->beginTransaction();
        try {
            // valida q o user foi criado com sucesso
            if ($this->adicionaAluno() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }

            // pega a url e envia ela como location
            $url = $request->getUri()->getPath(); // . "/" . $alunoid;
            $response = $response->withStatus(201)->withHeader('location', $url);

            // log do evento
            $this->logger->addInfo("Sucesso: Novo User " . $alunoid . "|" . $type . " - " . $data['id']);

            // salva o bd
            $this->db->commit();
        } catch (PDOException $e) {
            // em caso de erro: cria um log, envia status 400 (bad request) e apaga as operacoes da BD
            $this->logger->addInfo("ERRO: Novo User " . $alunoid . "|" . $type . ": " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }

    // Exemplo
    public function todosAlunos($request, $response, $args)
    {
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setId($model->getId());

        foreach ($model->getRelations() as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK($model->getType(), $model->getId());
            if ($rModel->getId() == null) continue;

            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $data->addRelationships($item->get());
            $item->setAttributes($rModel->get());
            $this->view->addIncluded($item);
        }

        $data->setAttributes($model->get());

        $response = $response->withJSON($this->view->get());
        return $response;

    }

// Business Logic

    public function adicionaAluno()
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
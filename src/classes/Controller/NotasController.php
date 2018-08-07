<?php
namespace Controller;

class NotasController extends Controller
{
    // Add Notas specific routes

    // Exemplo
    public function adcNota($request, $response, $args)
    {
        // pega info da url
        $notaid = $args['notaid'];

        // pega requisicao do corpo
        $body = $request->getParsedBody();
        if (!isset($body['data']['type'])) return $response->withStatus(400);
        
        // Usa JSONAPI para ler o corpo JSON da mensagem
        $this->view->set($body);
        
        // Set the nota
        $this->nota->setTipoByKey($this->view->getData()->getType());
        $this->nota->setNotaID($notaid);
        
        // inicia o processo de criacao do nota no BD
        // transaction garante que nada será escrito se der algum erro no meio do caminho
        // assim evita erros
        $this->db->beginTransaction();
        try {
            // valida q o nota foi criado com sucesso
            if ($this->addNota() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }
        
             // pega a url e envia ela como location
            $url = $request->getUri()->getPath(); // . "/" . $notaid;
            $response = $response->withStatus(201)->withHeader('location', $url);
        
            // log do evento
            $this->logger->addInfo("Sucesso: Novo nota " . $notaid . "|" . $type . " - " . $data['id']);
        
            // salva o bd
            $this->db->commit();
        } catch (PDOException $e) {
            // em caso de erro: cria um log, envia status 400 (bad request) e apaga as operacoes da BD
            $this->logger->addInfo("ERRO: Novo nota " . $notaid . "|" . $type . ": " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;

    }

        // Exemplo
    public function todasNotas($request, $response, $args)
    {
            // TODO
        return $response;
    }


            // Exemplo
    public function mudarNota($request, $response, $args)
    {
        // TODO
        return $response;
    }


        // Exemplo
    public function removerNotas($request, $response, $args)
    {
        // pega info da url
        $notaid = $args['notaid'];

        // pega requisicao do corpo
        $body = $request->getParsedBody();
        if (!isset($body['data']['type'])) return $response->withStatus(400);
        
        // Usa JSONAPI para ler o corpo JSON da mensagem
        $this->view->set($body);
        
        // Set the nota
        $this->nota->setTipoByKey($this->view->getData()->getType());
        $this->nota->setNotaID($notaid);
        
        // inicia o processo de criacao do nota no BD
        // transaction garante que nada será escrito se der algum erro no meio do caminho
        // assim evita erros
        $this->db->beginTransaction();
        try {
            // valida q a nota foi removida com sucesso
            if ($this->removeNota() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }
        
             // pega a url e envia ela como location
            $url = $request->getUri()->getPath(); // . "/" . $notaid;
            $response = $response->withStatus(201)->withHeader('location', $url);
        
            // log do evento
            $this->logger->addInfo("Sucesso: Deleted Nota " . $notaid . "|" . $type . " - " . $data['id']);
        
            // salva o bd
            $this->db->commit();
        } catch (PDOException $e) {
            // em caso de erro: cria um log, envia status 400 (bad request) e apaga as operacoes da BD
            $this->logger->addInfo("ERRO: Deleted Nota " . $notaid . "|" . $type . ": " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }

    public function removeNota()
    {
        $notaId = $this->nota->delete();
        if ($notaId == null) return null;
        $fk['nota'] = $notaId;
        if ($this->delete($this->view->getData(), $fk) == null) return null;
        foreach ($this->view->getIncluded() as $i) {
            $this->delete($i, $fk);
        }
        return true;
    }


}
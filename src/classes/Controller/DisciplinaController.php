<?php
namespace Controller;

class DisciplinaController extends Controller
{
    // Add Disciplina specific routes

    // Exemplo

    public function adcMateria($request, $response, $args)
    {
        // pega info da url
        $disciplinaid = $args['disciplinaid'];

        // pega requisicao do corpo
        $body = $request->getParsedBody();
        if (!isset($body['data']['type'])) return $response->withStatus(400);

        // Usa JSONAPI para ler o corpo JSON da mensagem
        $this->view->set($body);

        // Set the disciplina
        $this->disciplina->setTipoByKey($this->view->getData()->getType());
        $this->disciplina->setDisciplinaID($disciplinaid);

        // inicia o processo de criacao do disciplina no BD
        // transaction garante que nada será escrito se der algum erro no meio do caminho
        // assim evita erros
        $this->db->beginTransaction();
        try {
            // valida q o disciplina foi criado com sucesso
            if ($this->createdisciplina() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }

            // pega a url e envia ela como location
            $url = $request->getUri()->getPath(); // . "/" . $disciplinaid;
            $response = $response->withStatus(201)->withHeader('location', $url);

            // log do evento
            $this->logger->addInfo("Sucesso: Novo disciplina " . $disciplinaid . "|" . $type . " - " . $data['id']);

            // salva o bd
            $this->db->commit();
        } catch (PDOException $e) {
            // em caso de erro: cria um log, envia status 400 (bad request) e apaga as operacoes da BD
            $this->logger->addInfo("ERRO: Novo disciplina " . $disciplinaid . "|" . $type . ": " . $e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        }
        return $response;
    }


    public function todasMaterias($request, $response, $args)
    {
        // TODO
        return $response;
    }


    public function detalheMateria($request, $response, $args)
    {
        $disciplina = $args['disciplina'];
        if ($this->disciplina->readByFK('disciplina', $disciplina) == null) return $response->withStatus(403);

        // busca todas as tbl relacionadas com esse disciplina
        // no caso seria todas as tbl q possuem a fk do disciplina
        $relations = $this->disciplina->getRelations();
        
        // cria uma classe dbo baseado no tipo do disciplina (prof ou aluno)
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->{$this->disciplina->getTipo()}();
        $model->readByFK('curso', $this->disciplina->getId());

        // busca os dados na BD
        $this->read($model);

        // Preenche a view (JSON API) para retornar um JSON apropriado
        foreach ($relations as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK('disciplina', $this->disciplina->getId());
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


    public function mudarMateria($request, $response, $args)
    {
        // TODO
        return $response;
    }

    public function removerMateria($request, $response, $args)
    {
        // TODO
        return $response;
    }
}
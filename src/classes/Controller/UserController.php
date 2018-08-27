<?php
namespace Controller;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

// Exemplo de Implementação
class UserController extends Controller
{
    
    // Routes Implementation

    public function detalheUser($request, $response, $args)
    {
        $uid = $args['uid'];

        $tipo = $args['_'];

        if ($this->user->readByFK('user', $uid) == null) return $response->withStatus(403);

        // busca todas as tbl relacionadas com esse user
        // no caso seria todas as tbl q possuem a fk do user
        $relations = $this->user->getRelations();

        $this->user->setTipoByKey($tipo);

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

}
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
        
        // cria uma classe dbo baseado no tipo do user (prof ou aluno)
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->{$this->user->getTipo()}();

        $model->readByFK('user', $this->user->getId());

        // busca os dados na BD
        $this->read($model);


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
}
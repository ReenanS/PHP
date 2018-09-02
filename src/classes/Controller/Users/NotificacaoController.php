<?php
namespace Controller\Users;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class NotificacaoController extends Controller
{
    public function getAllMsg($request, $response, $args) 
    {
        // TODO
        // Retorna todas as notificacoes do usuario
        return $response;
    }

    public function getMsg($request, $response, $args) 
    {
        // TODO
        // Retorna todos os detalhes da notificacao em especifico
        
        /*msg*/
        $msg = $args['nid']; //pega o id do usuario
        $model = $this->models->notificacao();
        
        // cria uma classe dbo baseado no tipo do curso
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model->setId($msg); //setei o ID
        
        // busca os dados no BD
        $model->read();

       // if (!$model->validarDocente($msg)) return $response->withStatus(401);

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAlunoMatriculados($curso);
        foreach ($alunos as $aluno) {
            $item = $this->view->newItem();
            $item->setId($aluno->getId());
            $item->setType($aluno->getType());
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($aluno->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function addMsg($request, $response, $args) 
    {
        // TODO
        // Cria uma nova mensagem no banco de dados e
        // cria um notificacao para todos os usuários apropriados
        return $response;
    }

    public function editMsg($request, $response, $args) 
    {
        // TODO
        // Altera as infos da mensagem ou notificacao
        return $response;
    }

    public function delMsg($request, $response, $args) 
    {
        // TODO
        // Delete a notificacao do usuario, mas não deleta a mensagem
        return $response;
    }
}
<?php
namespace Controller\Business;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class CursoController extends Controller
{
    public function getAllCurso($request, $response, $args)
    {
        // TODO
        // Retorna todos os cursos
        /*Disciplina*/
        $professor = $args['pid']; //pega o id da disciplina

        // cria uma classe dbo baseado no tipo do disciplina
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->professor();
        $model->setId($professor); //setei o ID
    
        // busca os dados no BD
        $model->read();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Fazer as relations pegar da tabela notas/alunos
        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "curso";
        $rModel = $this->models->{$r}();
        $cursos = $rModel->readAll();
        foreach ($cursos as $curso) {
            $item = $this->view->newItem();
            $item->setId($curso->getId());
            $item->setType($curso->getType());
            //$this->view->getData()->addRelationships($item->get()); //Atencao - Dava erro no get() linha 23 do resourceObject
            $item->setAttributes($curso->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function getCurso($request, $response, $args)
    {
        // TODO
        // Retorna as infos da curso se o professor estiver lecionando ela
        
        /*Professor*/
        $professor = $args['pid']; //pega o id do usuario
        $model = $this->models->professor();

        /*curso*/
        $curso = $args['cid']; //pega o id da curso

        // cria uma classe dbo baseado no tipo do curso
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->curso();

        $model->setId($curso); //setei o ID
        
        // busca os dados no BD
        $model->read();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Fazer as relations pegar da tabela notas/disciplinas
        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "disciplina";
        $rModel = $this->models->{$r}();
        $disciplinas = $rModel->readAll();
        foreach ($disciplinas as $disciplina) {
            $item = $this->view->newItem();
            $item->setId($disciplina->getId());
            $item->setType($disciplina->getType());
            //$this->view->getData()->addRelationships($item->get()); //Atencao - Dava erro no get() linha 23 do resourceObject
            $item->setAttributes($disciplina->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function addCurso($request, $response, $args)
    {
        // TODO
        // Cria um novo curso
        return $response;
    }

    public function editCurso($request, $response, $args)
    {
        // TODO
        // Altera as infos do curso
        return $response;
    }

    public function delCurso($request, $response, $args)
    {
        // TODO
        // Deleta o curso
        return $response;
    }
}
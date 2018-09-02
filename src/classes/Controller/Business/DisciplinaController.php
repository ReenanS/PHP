<?php
namespace Controller\Business;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class DisciplinaController extends Controller
{
    // DISCIPLINA

    public function getAllDisciplina($request, $response, $args) 
    {
        // TODO
        // Retorna todas as disciplinas

        $model = $this->models->disciplina();
        
        // busca os dados no BD
        $model->read();
        $model->get();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());

        // Fazer as relations pegar da tabela notas/alunos
        $relations = $this->disciplina->getRelations();
        // Preenche a view (JSON API) para retornar um JSON apropriado
        foreach ($relations as $r)
        {
                $rModel = $this->models->{$r}();
                $rModel->readByFK('aluno', $this->user->getId());
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

        return $response;
    }

    public function getDisciplina($request, $response, $args) 
    {
        // TODO
        // Retorna todas as infos da disciplina
        return $response;
    }

    public function addDisciplina($request, $response, $args) 
    {
        // TODO
        // Cria uma nova disciplina
        return $response;
    }

    public function editDisciplina($request, $response, $args) 
    {
        // TODO
        // Altera as infos da disciplina
        return $response;
    }

    public function delDisciplina($request, $response, $args) 
    {
        // TODO
        // Delete a disciplina
        return $response;
    }


    // CURSO

    public function getAllCurso($request, $response, $args) 
    {
        // TODO
        // Retorna todas as disciplinas que sao daquele curso
        return $response;
    }

    public function editCurso($request, $response, $args) 
    {
        // TODO
        // Altera o curso da disciplina
        return $response;
    }

    public function delCurso($request, $response, $args) 
    {
        // TODO
        // Deleta a disciplina daquele curso
        return $response;
    }


    public function detalheMateriaAluno($request, $response, $args)
    {
        
    }

    public function detalheMateriaProf($request, $response, $args)
    {
        
    }

}

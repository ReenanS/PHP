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
        return $response;
    }

    public function getCurso($request, $response, $args) 
    {
        // TODO
        // Retorna as infos da curso se o professor estiver lecionando ela
        
        /*Prof*/
        $prof = $args['pid']; //pega o id do usuario
        $model = $this->models->professor();
        
        /*curso*/
        $curso = $args['cid']; //pega o id da curso

        // cria uma classe dbo baseado no tipo do curso
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->curso();
        $model->setId($curso); //setei o ID
        
        // busca os dados no BD
        $model->read();

       // if (!$model->validarDocente($prof)) return $response->withStatus(401);

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
            var_export($item);
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($aluno->get());
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
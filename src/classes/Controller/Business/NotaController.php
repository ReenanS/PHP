<?php
namespace Controller\Business;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class NotaController extends Controller
{
    // alunoESSOR

    public function getAllDetalheNota($request, $response, $args)
    {
        // TODO
        // Retorna todas as infos (detalhe) das notas daquela disciplina
        return $response;
    }

    public function getDetalheNota($request, $response, $args)
    {
        // TODO
        // Retorna as infos (detalhe) de um nota daquela disciplina
        
        /*Prof*/
        $prof = $args['pid']; //pega o id do usuario
        $model = $this->models->professor();
        
        /*Nota*/
        $nota = $args['nid']; //pega o id da nota

        // cria uma classe dbo baseado na nota
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->nota();
        $model->setId($nota); //setei o ID
        
        // busca os dados no BD
        $model->read();

        //if (!$model->validarDocente($prof)) return $response->withStatus(401);
        
        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAlunoMatriculados($disciplina);

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

    public function addDetalheNota($request, $response, $args)
    {
        // TODO
        // Cria as infos (detalhe) sobre aquela nota
        return $response;
    }

    public function editDetalheNota($request, $response, $args)
    {
        // TODO
        // Altera as infos (detalhe) sobre aquela nota
        return $response;
    }

    public function delDetalheNota($request, $response, $args)
    {
        // TODO
        // Deleta as infos (detalhe) sobre aquela nota
        return $response;
    }

    // ALUNO

    public function getAllNota($request, $response, $args)
    {
        // TODO
        // Retorna todas as notas na discilplina do aluno
         /*aluno*/
         $aluno = $args['aid']; //pega o id do usuario

         // cria uma classe dbo baseado no tipo do disciplina
         // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->aluno();
        $model->setId($aluno); //setei o ID
         
         // busca os dados no BD
        $model->read();
 
         //if (!$model->validarMatricula($aluno)) return $response->withStatus(401);
 
         // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());
 
         // Fazer as relations pegar da tabela notas/alunos
         // $relations = $this->disciplina->getRelations();
         // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $rModel->readNota($aluno, $disciplina);
        if ($rModel->getId() != null) {
            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($rModel->getInfo());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function getNota($request, $response, $args)
    {
        // TODO
        // Retorna todas as info da nota na discilplina do aluno
        
        /*aluno*/
        $aluno = $args['aid']; //pega o id do usuario
        $model = $this->models->aluno();
        
        /*nota*/
        $nota = $args['nid']; //pega o id da nota

        // cria uma classe dbo baseado no tipo do nota
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->nota();
        $model->setId($nota); //setei o ID
        
        // busca os dados no BD
        $model->read();

       // if (!$model->validarDocente($aluno)) return $response->withStatus(401);

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Preenche a view (JSON API) para retornar um JSON apropriado
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAlunoMatriculados($nota);
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

    public function addNota($request, $response, $args)
    {
        // TODO
        // Cria uma nova nota para o aluno naquela disciplina
        return $response;
    }

    public function editNota($request, $response, $args)
    {
        // TODO
        // Altera uma nota do aluno naquela disciplina
        return $response;
    }

    public function delNota($request, $response, $args)
    {
        // TODO
        // Deleta uma nota do aluno naquela disciplina
        return $response;
    }
}
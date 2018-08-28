<?php
namespace Controller;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class DisciplinaController extends Controller
{
    // Add Disciplina specific routes

    // Exemplo

    public function todasMaterias($request, $response, $args)
    {

        echo ('TODO');
        //Preciso criar um função no DB0.php porque todos pegam pelo id 

        $model = $this->models->disciplina();
        
        // busca os dados no BD
        $model->read();
        $model->get();
        echo (var_dump($model));

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
                //echo(var_dump($rModel));
                if ($rModel->getId() == null) continue;
                $item = $this->view->newItem();
                $item->setId($rModel->getId());
                $item->setType($rModel->getType());
                $this->view->getData()->addRelationships($item->get());
                $item->setAttributes($rModel->get());
                $this->view->addIncluded($item);
                //echo(var_dump($this->view->addIncluded($item)));
        }

        $response = $response->withJSON($this->view->get());
        return $response;

    }


    public function detalheMateriaAluno($request, $response, $args)
    {
        /*Aluno*/
        $aluno = $args['uid']; //pega o id do usuario
        $model = $this->models->aluno();
        
        /*Disciplina*/
        $disciplina = $args['id']; //pega o id da disciplina

        // cria uma classe dbo baseado no tipo do disciplina
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->disciplina();
        $model->setId($disciplina); //setei o ID
        
        // busca os dados no BD
        $model->read();

        if (!$model->validarMatricula($aluno)) return $response->withStatus(401);

        // $model->get();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Fazer as relations pegar da tabela notas/alunos
        // $relations = $this->disciplina->getRelations();
        // Preenche a view (JSON API) para retornar um JSON apropriado
        // foreach ($relations as $r) {
            // if ($r == "nota") {
                $r = "nota";
                $rModel = $this->models->{$r}();
                $rModel->readNota($aluno, $disciplina);
                var_export($rModel);
                if ($rModel->getId() != null) {
                    var_export($rModel);
                    $item = $this->view->newItem();
                    $item->setId($rModel->getId());
                    $item->setType($rModel->getType());
                    var_export($item);
                    $this->view->getData()->addRelationships($item->get());
                    $item->setAttributes($rModel->getInfo());
                    $this->view->addIncluded($item);
                } 
                //echo(var_dump($this->view->addIncluded($item)));
            // }
        // }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function detalheMateriaProf($request, $response, $args)
    {
        /*Aluno*/
        $prof = $args['uid']; //pega o id do usuario
        $model = $this->models->professor();
        
        /*Disciplina*/
        $disciplina = $args['id']; //pega o id da disciplina

        // cria uma classe dbo baseado no tipo do disciplina
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->disciplina();
        $model->setId($disciplina); //setei o ID
        
        // busca os dados no BD
        $model->read();

        if (!$model->validarDocente($prof)) return $response->withStatus(401);

        // $model->get();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Fazer as relations pegar da tabela notas/alunos
        // $relations = $this->disciplina->getRelations();
        // Preenche a view (JSON API) para retornar um JSON apropriado
        // foreach ($relations as $r) {
            // if ($r == "nota") {
        $r = "aluno";
        $rModel = $this->models->{$r}();
        $alunos = $rModel->readAlunoMatriculados($disciplina);
        // var_export($rModel);
        foreach ($alunos as $aluno) {
            // $rModel->getId();
            var_export($aluno);
            $item = $this->view->newItem();
            $item->setId($aluno->getId());
            $item->setType($aluno->getType());
            var_export($item);
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($aluno->get());
            $this->view->addIncluded($item);
        }
                //echo(var_dump($this->view->addIncluded($item)));
            // }
        // }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

}

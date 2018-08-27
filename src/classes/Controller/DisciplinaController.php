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

        echo('TODO');
      
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
        $model->get();

        // Monta a view
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        // Fazer as relations pegar da tabela notas/alunos
        $relations = $this->disciplina->getRelations();
        // Preenche a view (JSON API) para retornar um JSON apropriado
        foreach ($relations as $r) {
            if ($r == "nota") {
               
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
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }

}

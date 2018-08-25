<?php
namespace Controller;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class DisciplinaController extends Controller
{
    // Add Disciplina specific routes

    // Exemplo

    public function todasMaterias()
    {
        echo("todo");
    }


    public function detalheMateriaAluno($request, $response, $args)
    {
        $disciplina = $args['id'];


        //if ($this->disciplina->readByFK('disciplina', $disciplina) == null) return $response->withStatus(403);
        //echo(var_dump($this->disciplina->readByFK('disciplina', $disciplina)));

        // busca todas as tbl relacionadas com esse disciplina
        // no caso seria todas as tbl q possuem a fk do disciplina
        //$relations = $this->disciplina->getRelations();
        //echo(var_dump($relations)); 
        
        // cria uma classe dbo baseado no tipo do disciplina (prof ou aluno)
        // os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
        $model = $this->models->disciplina();
        //$model->readByFK('disciplina', $this->disciplina->getId());
        $model->setId($disciplina);
        var_export($disciplina);
        // busca os dados na BD
        $model->read();
        var_export($model->get());
        //if ($this->read() == null) return $response->withStatus(403);
  
        //$relations = $this->disciplina->getRelations();
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

}
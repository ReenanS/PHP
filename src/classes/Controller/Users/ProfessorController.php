<?php
namespace Controller\Users;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class ProfessorController extends Controller
{
    public function getAllLecionaDisciplina($request, $response, $args) {
        // TODO
        // Retorna todas as disciplinas que o docente leciona
        return $response;
    }

    public function getLeciona($request, $response, $args) {
        // TODO
        // Retorna as infos da disciplina se o professor estiver lecionando ela


        
        /*Prof*/
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

    public function addLeciona($request, $response, $args) {
        // TODO
        // Cria info que o prof leciona a disciplina
        return $response;
    }

    public function editLeciona($request, $response, $args) {
        // TODO
        // Altera a info que o prof leciona a disciplina
        return $response;
    }

    public function delLeciona($request, $response, $args) {
        // TODO
        // Deleta a info que o prof leciona a disciplina
        return $response;
    }
}
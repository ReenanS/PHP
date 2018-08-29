<?php
namespace Controller;

// Controllers
use \Controller\Controller;

use \View\ResourceObject;

class NotaController extends Controller
{
    // PROFESSOR

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
        return $response;
    }

    public function getNota($request, $response, $args) 
    {
        // TODO
        // Retorna todas as info da nota na discilplina do aluno
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
<?php
namespace Controller;

//Aqui eu vou pegar todas as notas - lista de json - Dar um include na rotas

// cria uma classe dbo baseado no tipo do disciplina
// os {'x'} chama uma function de dentro da classe passando uma string para ela (dinamico)
$model = $this->models->nota();
//var_export($model); 

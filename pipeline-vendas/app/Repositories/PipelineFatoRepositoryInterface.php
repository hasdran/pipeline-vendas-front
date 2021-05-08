<?php

namespace App\Repositories;

interface PipelineFatoRepositoryInterface {

  public function mostrarTodos();
  
  public function salvar($pipeline);
  
  public function buscarPorId($id);
  
  public function buscarPorSituacao($id);
  
  public function buscarPorTipo($id);

  public function buscarPorData($data);
  
  public function remover($id);
  
  public function alterar($id, $campos);
  
}
<?php

namespace App\Repositories;
use App\Pipeline;

class PipelineRepository implements PipelineRepositoryInterface {

  public function mostrarTodos() {
    return Pipeline::where('SITUACAO', '=', '1')->orderBy("ID_PIPELINE", "DESC")->get();
  }

  public function salvar($pipeline) {
    
    Pipeline::insert([
      $pipeline,
    ]);
   // $pipeline->save();
  }

  public function buscarPorId($id) {
    return Pipeline::where('ID_PIPELINE', '=', $id);
  }

  public function buscarPorSituacao($id) {

  }

  public function buscarPorTipo($id) {

  }

  public function buscarPorData($data) {

  }

  public function remover($id) {
    return Pipeline::where('ID_PIPELINE', '=', $id)
      ->update(
        array(
          'SITUACAO' => 0,
        )
      );
  }

  public function alterar($id, $campos) {
    Pipeline::where('ID_PIPELINE', '=', $id)
      ->where('SITUACAO', '=', '1')
      ->update($campos);
  }
}
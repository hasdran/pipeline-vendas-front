<?php

namespace App\Repositories;
use App\PipelineFato;

class PipelineFatoRepository implements PipelineFatoRepositoryInterface {

  public function mostrarTodos() {
  }

  public function salvar($pipelineLista) {

    foreach ($pipelineLista as $pipeline) {
      PipelineFato::insert([
        $pipeline,
      ]);

    }

    return $pipeline;
  }

  public function buscarPorId($id) {

  }

  public function buscarPorSituacao($id) {

  }

  public function buscarPorTipo($id) {

  }

  public function buscarPorData($data) {
    return PipelineFato::where('ID_TAB_SITUACAO', '<>', '1')
      ->where('DT_REFERENCIA', '=', $data)
      ->get();

  }

  public function remover($id) {

  }

  public function alterar($id, $campos) {

  }
}
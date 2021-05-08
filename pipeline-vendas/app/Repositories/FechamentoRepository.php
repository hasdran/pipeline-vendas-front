<?php

namespace App\Repositories;
use App\Fechamento;

class FechamentoRepository implements FechamentoRepositoryInterface {

  public function buscaUltimoFechamento(){
    return Fechamento::orderBy("DT_REFERENCIA", "DESC")->limit(1)->get();
  }

  public function salvar($fechamento) {
    return Fechamento::insert([
      $fechamento,
    ]);
  }
}
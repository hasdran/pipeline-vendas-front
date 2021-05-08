<?php

namespace App\Repositories;
use App\FechamentoItens;

class FechamentoItensRepository implements FechamentoItensRepositoryInterface {

  public function buscaUltimoFechamento(){
    return FechamentoItens::orderBy("DT_REFERENCIA", "DESC")->limit(1)->get();
  }

  public function salvar($fechamentoItens) {
    return FechamentoItens::insert([
      $fechamentoItens,
    ]);
  }
}
<?php

namespace App\Repositories;
use App\Situacao;

class SituacaoRepository implements SituacaoRepositoryInterface {

  public function buscaPorSituacao($situacao) {
     return Situacao::where('SITUACAO', '=', $situacao)->get();
  }

}
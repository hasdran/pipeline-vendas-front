<?php

namespace App\Repositories;
use App\TipoTotal;

class TipoTotalRepository implements TipoTotalRepositoryInterface {

  public function buscaPorIdSituacao($id) {
     return TipoTotal::where('ID_TAB_SITUACAO', '=', $id)->get();
  }

}
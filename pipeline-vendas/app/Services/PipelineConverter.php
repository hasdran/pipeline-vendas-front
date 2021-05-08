<?php

namespace App\Services;
use App\Pipeline;

class PipelineConverter {

  public static function converterArrayParaObjeto($array) {
    $pipeline_objeto = new Pipeline();
    
    foreach ($array as $indice => $valor) {
      $pipeline_objeto->$indice = $valor;
    }
    // var_dump($pipeline_objeto instanceof Pipeline);
    return $pipeline_objeto;
  }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Pipeline extends Model {
  protected $table = 'PIPELINE';
  public $timestamps = false;

  public static function calculaPrazo($duracao) {
    return ($duracao > 11) ? "Longo prazo" : "Curto prazo";
  }

  public static function validaPipelineEntradas(Array $entradasDoPipeline, $id = 0) {
    $entradasDoPipeline['ID_PIPELINE']= $id;
    return Validator::make($entradasDoPipeline, [
      'ID_PIPELINE' => [
        'required',
        'integer',
      ],
      'CLIENTE' => [
        'required',
      ],
      'PROJETO' => [
        'required',
      ],
      'VALOR' => [
        'numeric',
        'required',
      ],
      'VOLUME' => [
        'numeric',
        'required',
      ],
      'DT_INICIO' => [
        'date_format:"Y/m/d"',
        'required',
      ],
      'DT_ABERTURA' => [
        'date_format:"Y/m/d"',
      ],
      'DT_ENCERR' => [
        'date_format:"Y/m/d"',
      ],
      'TEMPO' => [
        'integer',
        'required',
      ],
      'PRAZO' => [
        'integer',
        'required',
      ],
      'PROBAB' => [
        'integer',
        'required',
      ],
      'ID_TAB_SITUACAO' => [
        'required',
      ],
      'DTOPEINC' => [
        'date_format:"Y/m/d"',
        'required',
      ],
      // 'CLIENTE' => [
      //   'required',

      //    Rule::dimensions()->maxWidth(1000)->maxHeight(500)->ratio(3 / 2),
      // ],
      // 'PROJETO' => [
      //   'required',

      //    Rule::dimensions()->maxWidth(1000)->maxHeight(500)->ratio(3 / 2),
      // ],
    ]);
  }

  public static function validaEntradaId($id) {
    $entradaId['ID_PIPELINE'] = $id;

    return Validator::make($entradaId, [
      'ID_PIPELINE' => [
        'required',
        'integer',
      ],
    ]);
  }
}

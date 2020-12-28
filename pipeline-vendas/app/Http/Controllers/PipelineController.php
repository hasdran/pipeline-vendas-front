<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SituacaoController;
use App\Pipeline;
use App\PipelineFato;
use Illuminate\Http\Request;

class PipelineController extends Controller {

  public function show(Request $request) {
    if (isset($request)) {
      $uri = $request->path();
    } else {
      $uri = "";
    }

    $pipeline = Pipeline::where('SITUACAO', '=', '1')->get();
    $situacao_pipeline = 1;
    $situacoes_lst = SituacaoController::findByTipo($situacao_pipeline);

    $pipeline_lst = $pipeline;

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_TAB_SITUACAO);
      $value->ID_TAB_SITUACAO = $situacao;

      $value->REC_ESTIMADA = number_format($value->REC_ESTIMADA, 2, ".", '');
      $value->REC_ESPERADA = number_format($value->REC_ESPERADA, 2, ".", '');
      $value->IMPACTO = number_format($value->IMPACTO, 2, ".", '');

      $value->DT_INICIO = str_replace(" 00:00:00.000", "", $value->DT_INICIO);
      $value->DT_INICIO = date("d-m-Y", strtotime($value->DT_INICIO));

      $value->DT_ABERTURA = str_replace(" 00:00:00.000", "", $value->DT_ABERTURA);
      $value->DT_ABERTURA = date("d-m-Y", strtotime($value->DT_ABERTURA));

      if (isset($value->DT_ENCERR)) {
        $value->DT_ENCERR = str_replace(" 00:00:00.000", "", $value->DT_ENCERR);
        $value->DT_ENCERR = date("d-m-Y", strtotime($value->DT_ENCERR));
      }
    }
    // echo $pipeline;
    if ($uri == 'create') {
      return view('pipeline')->with('pipeline', $pipeline)
        ->with('pipeline_lst', $pipeline_lst)
        ->with('situacoes_lst', $situacoes_lst)
        ->with('op', $uri);
    }if ($uri == 'update') {
      return view('pipeline')->with('pipeline', $pipeline)
        ->with('pipeline_lst', $pipeline_lst)
        ->with('situacoes_lst', $situacoes_lst)
        ->with('op', $uri);
    } else {
      return view('pipeline')->with('pipeline', $pipeline)
        ->with('pipeline_lst', $pipeline_lst);
    }
  }

  public function showTeste(Request $request) {

    $pipeline = Pipeline::where('SITUACAO', '=', '1')->orderBy("ID_PIPELINE","DESC")->get();
    $situacoes_lst = SituacaoController::findByTipo(1);

    $pipeline_lst = $pipeline;

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_TAB_SITUACAO);
      $value->ID_TAB_SITUACAO = $situacao;

      $value->REC_ESTIMADA = number_format($value->REC_ESTIMADA, 2, ".", '');
      $value->REC_ESPERADA = number_format($value->REC_ESPERADA, 2, ".", '');
      $value->IMPACTO = number_format($value->IMPACTO, 2, ".", '');

      $value->DT_INICIO = str_replace(" 00:00:00.000", "", $value->DT_INICIO);
      $value->DT_INICIO = date("d-m-Y", strtotime($value->DT_INICIO));

      $value->DT_ABERTURA = str_replace(" 00:00:00.000", "", $value->DT_ABERTURA);
      $value->DT_ABERTURA = date("d-m-Y", strtotime($value->DT_ABERTURA));

      if (isset($value->DT_ENCERR)) {
        $value->DT_ENCERR = str_replace(" 00:00:00.000", "", $value->DT_ENCERR);
        $value->DT_ENCERR = date("d-m-Y", strtotime($value->DT_ENCERR));
      }
    }

    $result = array("pipeline" => $pipeline_lst);
/*    if ($uri == 'create') {
return view('pipeline')->with('pipeline', $pipeline)
->with('pipeline_lst', $pipeline_lst)
->with('situacoes_lst', $situacoes_lst)
->with('op', $uri);
}if ($uri == 'update') {
return view('pipeline')->with('pipeline', $pipeline)
->with('pipeline_lst', $pipeline_lst)
->with('situacoes_lst', $situacoes_lst)
->with('op', $uri);
} else {

}*/
    return view('teste')->with('pipeline', $pipeline)
      ->with('pipeline_lst', $pipeline_lst);

  }

  public function create(Request $request) {
    $op = "sucess";
    $code = 200;
    date_default_timezone_set('America/Sao_Paulo');

    $pipeline = new Pipeline();

    if (isset($request->cliente)) {
      if (isset($request->projeto)) {
        if (isset($request->valor_m3)) {
          if (isset($request->volume_m3)) {
            if (isset($request->dt_abertura)) {
              if (isset($request->dt_inicio_op)) {
                if (isset($request->prazo_contrato)) {
                  if (isset($request->probabilidade)) {
                    if (isset($request->id_tab_situacao)) {
                      if (isset($request->tempo)) {

                        $pipeline->CLIENTE = $request->cliente;
                        $pipeline->PROJETO = $request->projeto;
                        $pipeline->VALOR = $request->valor_m3;
                        $pipeline->VOLUME = $request->volume_m3;
                        $pipeline->DT_ABERTURA = $request->dt_abertura;
                        if (isset($request->dt_encerramento)) {
                          $pipeline->DT_ENCERRAMENTO = $request->dt_encerramento;
                        }
                        $pipeline->DT_INICIO = $request->dt_inicio_op;
                        $pipeline->PRAZO = $request->prazo_contrato;
                        $pipeline->PROBAB = $request->probabilidade;
                        $pipeline->ID_TAB_SITUACAO = $request->id_tab_situacao;
                        $pipeline->TEMPO = $request->tempo;
                        $pipeline->SITUACAO = 1;
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    if (isset($request->tempo)) {
      $pipeline->DURACAO = ($request->prazo_contrato > 11) ? "Longo prazo" : "Curto prazo";
    }

    $dt_atual = date('Y-m-d');

    $pipeline->DTOPEINC = $dt_atual; // DATA INCLUSAO

    $pipeline->MUDANCA_STS = "Nova"; // CONFIRMAR SE DEVE HAVER VALIDACAO

    $op = "sucess";

    try {
      $pipeline->save();
    } catch (\Throwable $th) {
      $op = "error";
      $code = 500;
    }

    return response()->json([
      'status' => $op,
    ], $code);
    // return redirect()->action(
    //   'PipelineController@show', ['op' => $op]
    // );
  }

  public function delete(Request $request) {
    $op = "sucess";
    $code = 200;
    try {
      $pipeline = Pipeline::where('ID_PIPELINE', '=', $request->id)
        ->update(
          array(
            'SITUACAO' => 0,
          )
        );

    } catch (\Throwable $th) {
      $op = "error";
      $code = 200;
    }
    return response()->json([
      'status' => $op,
    ], $code);
  }

  public function update(Request $request) {
    $pipeline = new Pipeline();
    $request_sts = false;

    if (isset($request->cliente)) {
      if (isset($request->projeto)) {
        if (isset($request->valor_m3)) {
          if (isset($request->volume_m3)) {
            if (isset($request->dt_abertura)) {
              if (isset($request->dt_inicio_op)) {
                if (isset($request->prazo_contrato)) {
                  if (isset($request->probabilidade)) {
                    if (isset($request->id_tab_situacao)) {
                      if (isset($request->tempo)) {
                        $pipeline->ID_PIPELINE = $request->id;
                        $pipeline->CLIENTE = $request->cliente;
                        $pipeline->PROJETO = $request->projeto;
                        $pipeline->VALOR = $request->valor_m3;
                        $pipeline->VOLUME = $request->volume_m3;
                        $pipeline->DT_ABERTURA = $request->dt_abertura;
                        $pipeline->DT_INICIO = $request->dt_inicio_op;
                        $pipeline->PRAZO = $request->prazo_contrato;
                        $pipeline->PROBAB = $request->probabilidade;
                        $pipeline->ID_TAB_SITUACAO = $request->id_tab_situacao;
                        $pipeline->TEMPO = $request->tempo;
                        $pipeline->DURACAO = ($request->prazo_contrato > 11) ? "Longo prazo" : "Curto prazo";
                        $pipeline->MUDANCA_STS = $request->mudanca_sts;
                        $request_sts = true;
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    $op = "sucess";
    $code = 200;

    if ($request_sts == true) {
      try {
        Pipeline::where('ID_PIPELINE', '=', $pipeline->ID_PIPELINE)
          ->where('SITUACAO', '=', '1')
          ->update(array(
            'CLIENTE' => $pipeline->CLIENTE,
            'PROJETO' => $pipeline->PROJETO,
            'VALOR' => $pipeline->VALOR,
            'VOLUME' => $pipeline->VOLUME,
            'DT_ABERTURA' => $pipeline->DT_ABERTURA,
            'DT_INICIO' => $pipeline->DT_INICIO,
            'PRAZO' => $pipeline->PRAZO,
            'PROBAB' => $pipeline->PROBAB,
            'ID_TAB_SITUACAO' => $pipeline->ID_TAB_SITUACAO,
            'TEMPO' => $pipeline->TEMPO,
            'DURACAO' => $pipeline->DURACAO,
          ));
        $op = "sucess";
        $code = 200;
      } catch (\Throwable $th) {
        $op = $th;
        $code = 500;
      }
    } else {
      $op = "error";
      $code = 500;
    }

    return response()->json([
      'status' => $op,
    ], $code);
  }

  // public function findBySituacao(Request $request) {
  //   $pipeline = Pipeline::all();

  //   return "sdsdfsdf";
  // }

  public static function findByDate($mes, $ano) {
    $pipeline = Pipeline::whereMonth('DT_ABERTURA', '=', $mes)
      ->whereYear('DT_ABERTURA', '=', $ano)
      ->where('SITUACAO', '=', '1')
      ->get();
    return $pipeline;
  }

  public static function findByDateFato($mes, $ano) {
    $pipeline = PipelineFato::whereMonth('DT_ABERTURA', '=', $mes)
      ->whereYear('DT_ABERTURA', '=', $ano)->get();
    return $pipeline;
  }

  public static function gravarPipelineFato($fechamento) {
    $dt_fechamento = str_replace(" 00:00:00.000", "", $fechamento->DT_REFERENCIA);
    list($ano, $mes, $dia) = explode("-", $dt_fechamento);

    $pipeline = Pipeline::whereMonth('DT_ABERTURA', '=', $mes)
      ->whereYear('DT_ABERTURA', '=', $ano)
      ->where('SITUACAO', '=', '1')
      ->get();
    foreach ($pipeline as $indice => $result) {
      $pipeline[$indice]->ID_TAB_FECHAMENTO = $fechamento->ID_TAB_FECHAMENTO;
      $pipeline[$indice]->DTOPEINC = date('Y-m-d H:i:s');
      $pipeline[$indice]->SITUACAO = "Processado";
    }

    $status_op = "sucess";
    try {
      foreach ($pipeline as $chave => $result) {
        PipelineFato::insert([
          [
            "CLIENTE" => $result->CLIENTE,
            "PROJETO" => $result->PROJETO,
            "VALOR" => $result->VALOR,
            "VOLUME" => $result->VOLUME,
            "DT_ABERTURA" => $result->DT_ABERTURA,
            "DT_INICIO" => $result->DT_INICIO,
            "DT_ENCERR" => $result->DT_ENCERRAMENTO,
            "PRAZO" => $result->PRAZO,
            "PROBAB" => $result->PROBAB,
            "ID_TAB_SITUACAO" => $result->ID_TAB_SITUACAO,
            "SITUACAO" => "Processado",
            "TEMPO" => $result->TEMPO,
            "REC_ESTIMADA" => $result->REC_ESTIMADA,
            "REC_ESPERADA" => $result->REC_ESPERADA,
            "IMPACTO" => $result->IMPACTO,
            "DURACAO" => $result->DURACAO,
            "DTOPEINC" => date('Y/m/d H:i:s'),
            "MUDANCA_STS" => $result->MUDANCA_STS,
            "ID_TAB_FECHAMENTO" => $fechamento->ID_TAB_FECHAMENTO,
          ],
        ]);
      }
    } catch (\Throwable $th) {$status_op = $th;}

    return $status_op;
  }

  public static function somaTotalReceitas($fechamento, $op) {
    if ($op == 0) {
      $tot_impac = PipelineFato::where("ID_TAB_FECHAMENTO", "=", $fechamento->ID_TAB_FECHAMENTO)
        ->selectRaw('sum(REC_ESTIMADA) as REC_ESTIMADA, sum(REC_ESPERADA) as REC_ESPERADA, sum(IMPACTO) as IMPACTO')
        ->get();
    } else {
      $dt_fechamento = str_replace(" 00:00:00.000", "", $fechamento->DT_REFERENCIA);
      list($ano, $mes, $dia) = explode("-", $dt_fechamento);

      $tot_impac = Pipeline::whereMonth("DT_ABERTURA", "=", $mes)
        ->whereYear('DT_ABERTURA', '=', $ano)
        ->where('SITUACAO', '=', '1')
        ->selectRaw('sum(REC_ESTIMADA) as REC_ESTIMADA, sum(REC_ESPERADA) as REC_ESPERADA, sum(IMPACTO) as IMPACTO')
        ->get();
    }

    return $tot_impac;
  }
}

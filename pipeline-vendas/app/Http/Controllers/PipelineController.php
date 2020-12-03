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

    $pipeline = Pipeline::all();
    $situacao = SituacaoController::find(1);
    $situacoes_lst = SituacaoController::getAll();

    $pipeline_lst = $pipeline;

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_SITUACAO);
      $value->ID_SITUACAO = $situacao;

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

  public function create(Request $request) {
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
                    if (isset($request->situacao)) {
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
                        $pipeline->ID_SITUACAO = $request->situacao;
                        $pipeline->TEMPO = $request->tempo;
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
    }

    return redirect()->action(
      'PipelineController@show', ['op' => $op]
    );
  }

  public function delete(Request $request) {
    $op = "sucess";
    $code = 200;
    try {
      Pipeline::where('ID_PIPELINE', '=', $request->id)->delete();
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
                    if (isset($request->situacao)) {
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
                        $pipeline->ID_SITUACAO = $request->situacao;
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
          ->update(array(
            'CLIENTE' => $pipeline->CLIENTE,
            'PROJETO' => $pipeline->PROJETO,
            'VALOR' => $pipeline->VALOR,
            'VOLUME' => $pipeline->VOLUME,
            'DT_ABERTURA' => $pipeline->DT_ABERTURA,
            'DT_INICIO' => $pipeline->DT_INICIO,
            'PRAZO' => $pipeline->PRAZO,
            'PROBAB' => $pipeline->PROBAB,
            'ID_SITUACAO' => $pipeline->ID_SITUACAO,
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

  public function findBySituacao(Request $request) {
    $pipeline = Pipeline::all();

    return "sdsdfsdf";
  }

  public static function findByMonth($mes, $ano) {
    $pipeline = Pipeline::whereMonth('DT_ABERTURA', '=', $mes)
    ->whereYear('DT_ABERTURA', '=', $ano)->get();
    return $pipeline;
  }

  public static function findByMonthFato($mes, $ano) {
    $pipeline = PipelineFato::whereMonth('DT_ABERTURA', '=', $mes)
    ->whereYear('DT_ABERTURA', '=', $ano)->get();
    return $pipeline;
  }  

  public static function gravarPipelineFato($fechamento) {
    
    date_default_timezone_set('America/Sao_Paulo');
    $dt_ref_fechamento = str_replace(" 00:00:00.000", "", $fechamento->DT_REFERENCIA);
    $dt_ref_fechamento = date("d-m-Y", strtotime($dt_ref_fechamento));
    list($dia, $mes, $ano) = explode("-", $dt_ref_fechamento);
    
    $pipeline = Pipeline::whereMonth('DT_ABERTURA', '=', $mes)
      ->whereYear('DT_ABERTURA', '=', $ano)->get();
      
    foreach ($pipeline as $result) {
      $pipeline_fato = new PipelineFato();
      $pipeline_fato->CLIENTE = $result->CLIENTE;
      $pipeline_fato->PROJETO = $result->PROJETO;
      $pipeline_fato->VALOR = $result->VALOR;
      $pipeline_fato->VOLUME = $result->VOLUME;
      $pipeline_fato->DT_ABERTURA = $result->DT_ABERTURA;
      if (isset($result->DT_ENCERRAMENTO)) {
        $pipeline_fato->DT_ENCERR = $result->DT_ENCERRAMENTO;
      }      
      $pipeline_fato->DT_INICIO = $result->DT_INICIO;
      $pipeline_fato->PRAZO = $result->PRAZO;
      $pipeline_fato->PROBAB = $result->PROBAB;
      $pipeline_fato->ID_SITUACAO = $result->ID_SITUACAO;
      $pipeline_fato->DESCR_SITUACAO = "Processado";
      $pipeline_fato->TEMPO = $result->TEMPO;
      $pipeline_fato->REC_ESTIMADA = $result->REC_ESTIMADA;
      $pipeline_fato->REC_ESPERADA = $result->REC_ESPERADA;
      $pipeline_fato->IMPACTO = $result->IMPACTO;
      $pipeline_fato->DURACAO = $result->DURACAO;
      $pipeline_fato->DTOPEINC = date('Y/m/d H:i:s');
      $pipeline_fato->MUDANCA_STS = $result->MUDANCA_STS;
      $pipeline_fato->ID_FECHAMENTO = $fechamento->id;
      $pipeline_fato->save();
    }
    return $pipeline_fato;
  }
}
